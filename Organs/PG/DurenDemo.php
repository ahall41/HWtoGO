<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\PG;
require_once __DIR__ . "/Duren.php";

/**
 * Import Gogh Demo
 */

class DurenDemo extends Duren {

    const ROOT="/GrandOrgue/Organs/PG/Duren/";
    const ODF="Duren (demo).Organ_Hauptwerk_xml";
    const COMMENTS=
              "Annakirche in Düren, Germany (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/duren/\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Duren (demo - %s) 1.0.organ";
    
    public static function DurenDemo(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=
                \GOClasses\Ambience::$blankloop=
                "OrganInstallationPackages/002526/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new DurenDemo(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            foreach($hwi->getStops() as $id=>$stop) {
                for ($i=1; $i<6; $i++) {
                    $stop->unset("Rank00${i}PipeCount");
                    $stop->unset("Rank00${i}FirstAccessibleKeyNumber");
                    $stop->unset("Rank00${i}FirstPipeNumber");
                }
                
                for ($rankid=1; $rankid<=$stop->NumberOfRanks; $rankid++) {
                    $r=$stop->int2str($rankid);
                    switch ($id) {
                        case 32: // HW Cornet V
                            $stop->set("Rank{$r}FirstAccessibleKeyNumber",25);
                            break;
                        case 38: // SW Celeste
                            $stop->set("Rank{$r}FirstAccessibleKeyNumber",13);
                            break;
                    } 
                }
            }
            /* foreach([80, 1080, 2080] as $stopid)
                echo $hwi->getStop($stopid); */
            $hwi->saveODF(sprintf(self::TARGET, $target));
            echo $hwi->getOrgan()->ChurchName, "\n";
        }
        else {
            self::DurenDemo(
                    [1=>"(close)"],
                    "close");
            self::DurenDemo( 
                    [2=>"(front)"],
                    "front");
            self::DurenDemo(
                    [3=>"(rear)"],
                    "rear");
            self::DurenDemo( 
                    [1=>"(close)", 2=>"(front)", 3=>"(rear)"],
                    "surround");
        }
    }   
    
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\PG\ErrorHandler");
DurenDemo::DurenDemo();