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

    const ROOT="/GrandOrgue/Organs/PG/Ermelo/";
    const ODF="Ermelo.Organ_Hauptwerk_xml";
    const COMMENTS=
              "Immanuelkerk in Ermelo, Netherlands (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/ermelo/\n"
            . "\n"
            . "1.1 Corrected pitch for other temperaments\n"
            . "1.2 Cross fades corrected for GO 3.14\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Ermelo (%s) 1.2.organ";
    
    public static function ErmeloFull(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=
                "OrganInstallationPackages/002521/Noises/BlankLoop.wav";
        
        if (sizeof($positions)>0) {
            $hwi=new ErmeloFull(self::SOURCE);
            self::Ermelo($hwi, $positions, $target);
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