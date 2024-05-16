<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\PG;
require_once __DIR__ . "/Nitra.php";

/**
 * Transform Nitra Demo
 * 
 * @todo: Pauke, Alt console, Short octave. 
 */

class NitraDemo extends Nitra {

    const ROOT="/GrandOrgue/Organs/PG/Nitra/";
    const ODF="Nitra (demo).Organ_Hauptwerk_xml";
    const COMMENTS=
              "Nitra, Katedrála sv. Emeráma, Slovakia (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/nitra/\n"
            . "\n"
            . "1.1 Wave based tremulant\n"
            . "    Corrected pitch for other temperaments\n"
            . "1.2 Cross fades corrected for GO 3.14\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Nitra (demo - %s) 1.2.organ";
    
    public static function NitraDemo(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="./OrganInstallationPackages/002516/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new NitraDemo(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getStops() as $stop) {
                for ($i=1; $i<=6; $i++) {
                    $stop->unset("Rank00${i}PipeCount");
                    $stop->unset("Rank00${i}FirstAccessibleKeyNumber");
                }
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::NitraDemo(
                    [1=>"(close)"],
                    "close");
            self::NitraDemo( 
                    [2=>"(front)"],
                    "front");
            self::NitraDemo(
                    [3=>"(rear)"],
                    "rear");
            self::NitraDemo( 
                    [1=>"(close)", 2=>"(front)", 3=>"(rear)"],
                    "surround");
        }
    }   
    
}
NitraDemo::NitraDemo();