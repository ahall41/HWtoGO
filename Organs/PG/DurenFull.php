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
            . "\n"
            . "1.1 Added crescendo program\n"
            . "1.2 Pitch correction for other temperaments\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Duren (%s) 1.2";
 
    protected $combinations=[
        "crescendos"=>[
            "A"=>[1000,1001,1001,1002,1002,1003,1004,1005,1006,1007,
                  1008,1009,1010,1011,1012,1013,1014,1015,1016,1017,
                  1018,1019,1020,1021,1022,1023,1024,1025,1026,1027,
                  1028,1029]
                ]
        ];
    
    public function import(): void {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 4:
                    echo ($instanceID=$instance["ImageSetInstanceID"]), "\t",
                         isset($instance["AlternateScreenLayout1_ImageSetID"]) ? 1 : "", "\t",
                         isset($instance["AlternateScreenLayout2_ImageSetID"]) ? 2 : "", "\t",
                         $instance["Name"], ": ";
                    foreach ($this->hwdata->switches() as $switch) {
                        if (isset($switch["Disp_ImageSetInstanceID"])  && 
                               $switch["Disp_ImageSetInstanceID"]==$instanceID)
                            echo $switch["SwitchID"], " ",
                                 $switch["Name"], ", ";
                    }
                    echo "\n";
            }
        } 
        exit(); //*/
        
        parent::import();
        foreach ([40=>13693] as $pageid=>$instanceid) {
            foreach([0,1,2] as $layoutid) {
                if (($panel=$this->getPanel($pageid+$layoutid, FALSE))) {
                    $cr=$panel->Element();
                    $cr->Type="Swell";
                    $this->configureEnclosureImage($cr, ["InstanceID"=>$instanceid], $layoutid);
                }
            }
        } 
    }
    
    // Create dummy sample file for testing ...
    public function createSample($hwdata) {
        $file=getenv("HOME") . self::ROOT . $this->sampleFilename($hwdata);
        if (!file_exists($file)) {
            $dir=dirname($file);
            if (!is_dir($dir)) mkdir($dir, 0777, TRUE);
            $blank=getenv("HOME") . self::ROOT . \GOClasses\Noise::$blankloop;
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
                "OrganInstallationPackages/002525/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new DurenFull(self::SOURCE);
            self::Duren($hwi, $positions, $target);
            $hwi->getOrgan()->ChurchName.=sprintf(" (%s)", $target);
            $hwi->save(sprintf(self::TARGET, $target));
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