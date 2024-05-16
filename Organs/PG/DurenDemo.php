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
 * Import Duren Demo
 */

class DurenDemo extends Duren {

    const ROOT="/GrandOrgue/Organs/PG/Duren/";
    const ODF="Duren (demo).Organ_Hauptwerk_xml";
    const COMMENTS=
              "Annakirche in Düren, Germany (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/duren/\n"
            . "\n"
            . "1.1 Pitch correction for other temperaments\n"
            . "1.2 Cross fades corrected for GO 3.14\n"
            . "\n";
    
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Duren (demo - %s) 1.2.organ";
    
    public static function DurenDemo(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=
                "OrganInstallationPackages/002526/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new DurenDemo(self::SOURCE);
            self::Duren($hwi, $positions, $target);
            $hwi->saveODF(sprintf(self::TARGET, $target));
            $hwi->getOrgan()->ChurchName.=sprintf(" (%s)", $target);
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