<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\SP;
require_once __DIR__ . "/SPOrganV2.php";

/**
 * Import Sonus Paradisi Rosales, opus 11, Portland, Oregon Demo to GrandOrgue
 * 
 * @author andrewZ`
 */
class PortlandRosales extends SPOrganV2 {
    const ROOT="/GrandOrgue/Organs/SP/PortlandRosales/";
    const SOURCE="OrganDefinitions/Portland, Rosales op.11, DEMO.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Portland, Rosales op.11 %s Demo 0.1.organ";
    const REVISIONS="";
    
    const RANKS_DIRECT=1;
    const RANKS_DIFFUSE=2;
    const RANKS_REAR=3;
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIFFUSE, 9=>self::RANKS_DIFFUSE,
        1=>self::RANKS_DIRECT,  7=>self::RANKS_DIRECT,
        4=>self::RANKS_REAR,    8=>self::RANKS_REAR,
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
        1=>[
            0=>["SetID"=>1030] // Console
           ],
        2=>[
            0=>["Group"=>"Landscape", "SetID"=>1035, "Instance"=>11000], // Simple
            1=>[],
            2=>["Group"=>"Portrait",  "SetID"=>1042, "Instance"=>11000], // Simple (P)
           ],
        3=>[
            0=>["Group"=>"Landscape", "SetID"=>1031, "Instance"=>12000], // Left
            1=>[],
            2=>["Group"=>"Portrait",  "SetID"=>1033, "Instance"=>12000], // Left (P)
           ],
        4=>[
            0=>["Group"=>"Landscape", "SetID"=>1032, "Instance"=>12000], // Right
            1=>[],
            2=>["Group"=>"Portrait",  "SetID"=>1034, "Instance"=>12000], // Right (P)
           ],
        5=>"DELETE", // Mixer
        6=>[
            0=>["SetID"=>1037, "Instance"=>13000], // Stops
           ],
        7=>"DELETE", // Crescendo
        8=>"DELETE", // Crescendo
        9=>"DELETE"  // Combinations
    ];

    protected $patchDivisions=[
        8=>["DivisionID"=>8, "Name"=>"Blower",  "Noise"=>TRUE],
        9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    protected $patchTremulants=[
         4=>"DELETE", // Pedal
        40=>"DELETE",
         3=>["Type"=>"Wave", "GroupIDs"=>[301,302,303,401,402,403]], // Manual
    ];

    protected $patchEnclosures=[
        997=>"DELETE", // Sw Oktav 4'
        998=>["Panels"=>[2=>[986,NULL,986], 3=>[984,NULL,984], 6=>[982, NULL, 982]], 
            "GroupIDs"=>[401,402,403], "AmpMinimumLevel"=>20], // Sw
        
        /* 901=>["Panels"=>[5=>[810]], "EnclosureID"=>901, "Name"=>"Direct",
            "GroupIDs"=>[101,201,301,401,701,801], "AmpMinimumLevel"=>0],
        902=>["Panels"=>[5=>[820]], "EnclosureID"=>902, "Name"=>"Diffuse",
            "GroupIDs"=>[102,202,302,402,702,802], "AmpMinimumLevel"=>0],
        903=>["Panels"=>[5=>[800]], "EnclosureID"=>903,"Name"=>"Semi-Dry",
            "GroupIDs"=>[103,203,303,403,703,803], "AmpMinimumLevel"=>0],
        904=>["Panels"=>[5=>[830]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304,404,704,804], "AmpMinimumLevel"=>0],
        908=>["Panels"=>[5=>[1595]], "EnclosureID"=>908,"Name"=>"Blower",
            "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>0],
        909=>["Panels"=>[5=>[1599]], "EnclosureID"=>909,"Name"=>"Tracker",
            "GroupIDs"=>[901,902,903,904], "AmpMinimumLevel"=>0] */
    ];

    protected $patchKeyActions=[
    ];
    
    protected $patchStops=[
         250=>["StopID"=> 250, "DivisionID"=>1, "Name"=>"Blower",      "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>800],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"P Key On",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -112=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"Pos Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -113=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"HW key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -114=>["StopID"=>-114, "DivisionID"=>4, "Name"=>"SW key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"P Key Off",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"Pos Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"HW key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -124=>["StopID"=>-124, "DivisionID"=>4, "Name"=>"SW key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[
          5=>"DELETE", /** toys - @todo */
          6=>"DELETE", /** toys - @todo */
          7=>"DELETE", /** toys - @todo */
          8=>["Noise"=>"StopOn",  "GroupID"=>900, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>900, "StopIDs"=>[]],
        950=>["Noise"=>"Ambient", "GroupID"=>800, "StopIDs"=>[250]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-111]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-112]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-113]],
        984=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-114]],
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-121]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-122]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-123]],
        994=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-124]],
    ];
    
    /* public function import(): void {
        parent::import();
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (strpos(strtolower($instance["Name"]), "swl")!==FALSE) {
            //if ($instance["DisplayPageID"]==5) {
                echo $instance["ImageSetInstanceID"], " ",
                     $instance["DisplayPageID"], " ",
                     $instance["Name"], "\n";
            }
        }
    } */

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        SPOrgan::configurePanelEnclosureImages($enclosure, $data);
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2051;
        return parent::createOrgan($hwdata);
    }

    private function treeWalk($root, $dir="", &$results=[]) {
        $files=scandir("$root$dir");
        foreach ($files as $key => $value) {
            if (!is_dir("$root$dir/$value")) {
                $results[strtolower("$dir/$value")] = "$dir/$value";
            } else if ($value != "." && $value != "..") {
                $this->treeWalk($root, ltrim("$dir/$value", "/"), $results);
            }
        }
        return $results;
    }

    protected function correctFileName(string $filename): string {
        static $files=[];
        if (sizeof($files)==0)
            $files=$this->treeWalk(getenv("HOME") . self::ROOT);
        
        $filename=str_replace("//", "/", $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    
    protected function sampleTuning(array $hwdata) : ?float {
        return NULL;
    }

    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        // unset($hwdata["ReleaseCrossfadeLengthMs"]);
        $hwdata["IsTremulant"]=0;
        switch ($hwdata["RankID"] % 10) {
            case 9:
                $hwdata["RankID"]-=9;
                $hwdata["IsTremulant"]=1;
                break;
            case 8:
                $hwdata["RankID"]-=4;
                $hwdata["IsTremulant"]=1;
                break;
            case 7:
                $hwdata["RankID"]-=6;
                $hwdata["IsTremulant"]=1;
                break;
        }
        return parent::processSample($hwdata, $isattack);
    }
    
    /**
     * Run the import
     */
    public static function PortlandRosales(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new PortlandRosales(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            foreach($hwi->getStops() as $stop) {
                foreach (["001","002","003","004"] as $r) {
                    $pc="Rank{$r}PipeCount";
                    unset($stop->$pc);
                }
            }
            /* $pedals=$hwi->getManual(1);
            $pedals->PositionX=$pedals->Key004Width;
            $pedals->PositionY=$hwi->getPanel(10)->DispScreenSizeVert-272;
            $pedals->Key004Width=0;
            $pedals->Key022Width=0; */
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            self::PortlandRosales(
                    [self::RANKS_DIRECT=>"Direct"],
                     "Direct");
            self::PortlandRosales(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::PortlandRosales(
                    [self::RANKS_REAR=>"Rear"],
                     "Rear");
            self::PortlandRosales(
                    [
                        self::RANKS_DIRECT=>"Direct",
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "Surround");
        }
    }
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\SP\ErrorHandler");
PortlandRosales::PortlandRosales();