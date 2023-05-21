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

class DurenFull extends Duren {

    const ROOT="/GrandOrgue/Organs/PG/DurenFull/";
    const ODF="Duren.Organ_Hauptwerk_xml";
    const COMMENTS=
              "Annakirche in Düren, Germany (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/duren/\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Duren (%s) 0.2.organ";
    
    public function configureKeyboardKey(\GOClasses\Manual $manual, $switchid, $midikey): void {
        $switch=$this->hwdata->switch($switchid);
        if (!empty($switch["Disp_ImageSetIndexEngaged"])) 
            parent::configureKeyboardKey($manual, $switchid, $midikey);
    }

   // Create dummy sample file for testing ...
    public function createSample($hwdata) {
        $file=getenv("HOME") . self::ROOT . $this->sampleFilename($hwdata);
        if (!file_exists($file)) {
            $dir=dirname($file);
            if (!is_dir($dir)) mkdir($dir, 0777, TRUE);
            $blank=getenv("HOME") . self::ROOT . \GOClasses\Ambience::$blankloop;
            symlink($blank, $file);
        }
    }

    public function processNoise(array $hwdata, $isattack): ?\GOClasses\Noise {
        $noise=parent::processNoise($hwdata, $isattack);
        if ($noise) $this->createSample($hwdata);
        return $noise;
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe) $this->createSample($hwdata);
        return $pipe;
    }

    public static function DurenFull(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=
                \GOClasses\Ambience::$blankloop=
                "OrganInstallationPackages/002525/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new DurenFull(self::SOURCE);
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
                        case 32: // HW Cornet V
                            $stop->set("Rank{$r}FirstAccessibleKeyNumber",25);
                            break;
                        case 38: // SW Celeste
                            $stop->set("Rank{$r}FirstAccessibleKeyNumber",13);
                            break;
                    } 
                }
            }
            
            /* foreach($hwi->getRanks() as $rankid=>$rank) {
                $pipes=$rank->Pipes();
                $min=(sizeof($pipes)>0) ? min(array_keys($pipes)) : 0;
                echo $rankid, " ", $min, " ", $rank->Name, "\n";
            } */
            
            $hwi->saveODF(sprintf(self::TARGET, $target));
            echo $hwi->getOrgan()->ChurchName, "\n";
        }
        else {
            self::DurenFull(
                    [1=>"(close)"],
                    "close");
            self::DurenFull( 
                    [2=>"(front)"],
                    "front");
            self::DurenFull(
                    [3=>"(rear)"],
                    "rear");
            self::DurenFull( 
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
DurenFull::DurenFull();