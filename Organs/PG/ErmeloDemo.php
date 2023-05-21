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

class ErmeloDemo extends Ermelo {

    const ROOT="/GrandOrgue/Organs/PG/Ermelo/";
    const ODF="Ermelo (demo).Organ_Hauptwerk_xml";
    const COMMENTS=
              "Immanuelkerk in Ermelo, Netherlands (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/ermelo/\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Ermelo (demo - %s) 1.0.organ";
    
    public static function ErmeloDemo(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=
                \GOClasses\Ambience::$blankloop=
                "OrganInstallationPackages/002522/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new ErmeloDemo(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            foreach($hwi->getStops() as $stop) {
                for ($i=1; $i<6; $i++) {
                    $stop->unset("Rank00${i}PipeCount");
                    $stop->unset("Rank00${i}FirstAccessibleKeyNumber");
                    $stop->unset("Rank00${i}FirstPipeNumber");
                }
            }
            $hwi->saveODF(sprintf(self::TARGET, $target));
            echo $hwi->getOrgan()->ChurchName, "\n";
        }
        else {
            self::ErmeloDemo(
                    [1=>"(close)"],
                    "close");
            self::ErmeloDemo( 
                    [2=>"(front)"],
                    "front");
            self::ErmeloDemo(
                    [3=>"(rear)"],
                    "rear");
            self::ErmeloDemo( 
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
ErmeloDemo::ErmeloDemo();