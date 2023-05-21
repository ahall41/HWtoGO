<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\PG;
require_once __DIR__ . "/Ermelo.php";

/**
 * Import Gogh Demo
 */

class ErmeloFull extends Ermelo {

    const ROOT="/GrandOrgue/Organs/PG/ErmeloFull/";
    const ODF="Ermelo.Organ_Hauptwerk_xml";
    const COMMENTS=
              "Immanuelkerk in Ermelo, Netherlands (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/ermelo/\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Ermelo (%s) 0.3.organ";
    
    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        if (isset($hwdata["LoopCrossfadeLengthInSrcSampleMs"]) 
                && $hwdata["LoopCrossfadeLengthInSrcSampleMs"]>120) $hwdata["LoopCrossfadeLengthInSrcSampleMs"]=120;
        return parent::processSample($hwdata, $isattack);
    }
   
    public static function ErmeloFull(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=
                \GOClasses\Ambience::$blankloop=
                "OrganInstallationPackages/002521/Noises/BlankLoop.wav";
        \GOClasses\Manual::$keys=58;
        \GOClasses\Manual::$pedals=29;
        if (sizeof($positions)>0) {
            $hwi=new ErmeloFull(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=sprintf(" (%s)", $target);
            foreach($hwi->getStops() as $id=>$stop) {
                for ($i=1; $i<6; $i++) {
                    $stop->unset("Rank00${i}PipeCount");
                    $stop->unset("Rank00${i}FirstAccessibleKeyNumber");
                    $stop->unset("Rank00${i}FirstPipeNumber");
                }
                
                for ($rankid=1; $rankid<=$stop->NumberOfRanks; $rankid++) {
                    $r=$stop->int2str($rankid);
                    switch ($id) {
                        case 14: // HW Cornet III
                            $stop->set("Rank{$r}FirstAccessibleKeyNumber",25);
                            break;

                        case 15: // HW Mix bass etc
                        case 17:
                        case 19:#
                            $stop->set("Rank{$r}PipeCount",23);
                            break;

                        case 16: // HW Mix desc etc
                        case 18:
                        case 20:
                            $stop->set("Rank{$r}FirstAccessibleKeyNumber",25);
                            $stop->set("Rank{$r}FirstPipeNumber",25);
                            break;
                    } 
                }
            }
            $hwi->saveODF(sprintf(self::TARGET, $target));
            echo $hwi->getOrgan()->ChurchName, "\n";
        }
        else {
            self::ErmeloFull(
                    [1=>"(close)"],
                    "close");
            self::ErmeloFull( 
                    [2=>"(front)"],
                    "front");
            self::ErmeloFull(
                    [3=>"(rear)"],
                    "rear");
            self::ErmeloFull( 
                    [1=>"(close)", 2=>"(front)", 3=>"(rear)"],
                    "surround");
        }
    }   
    
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
//set_error_handler("Organs\PG\ErrorHandler");
ErmeloFull::ErmeloFull();