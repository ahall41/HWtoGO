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
 * Import Sonus Paradisi Aristide Cavaillé-Coll, Warrington organ (1870)
 * 
 * @todo: Trumpet vs blower
 * 
 * @author andrewZ`
 */
class Warrington extends SPOrganV2 {
    const ROOT="/GrandOrgue/Organs/SP/Warrington/";
    const SOURCE=self::ROOT . "OrganDefinitions/Warrington, Parr Hall, Cavaille-Coll, Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Warrington, Parr Hall, Cavaille-Coll, Demo (%s).1.1.organ";
    const REVISIONS="\n1.1 Added tuning information; remove spurious switches for Ped Trumpet\n";
    
    
    const RANKS_DIRECT=1;
    const RANKS_DIFFUSE=2;
    const RANKS_DISTANT=3;
    const RANKS_REAR=4;
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,   9=>self::RANKS_DIRECT,
        1=>self::RANKS_DIFFUSE,  7=>self::RANKS_DIFFUSE,
        2=>self::RANKS_DISTANT,  6=>self::RANKS_DISTANT,
        4=>self::RANKS_REAR,     8=>self::RANKS_REAR
    ];
    
    public $positions=[];
    protected $switchGroups=[801, 802, 803, 804];
    
    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>[
                0=>["Group"=>"Simple", "Name"=>"Landscape", "Instance"=>11000, "SetID"=>1036],
                1=>[],
                2=>["Group"=>"Simple", "Name"=>"Portrait", "Instance"=>11000, "SetID"=>1037],
               ],
            3=>[
                0=>["Group"=>"Left", "Name"=>"Landscape", "Instance"=>12000, "SetID"=>1031],
                1=>[],
                2=>["Group"=>"Left", "Name"=>"Portrait", "Instance"=>12000, "SetID"=>1033],
               ],
            4=>[
                0=>["Group"=>"Right", "Name"=>"Landscape", "Instance"=>12000, "SetID"=>1032],
                1=>[],
                2=>["Group"=>"Right", "Name"=>"Portrait", "Instance"=>12000, "SetID"=>1034],
               ],
            5=>[
                0=>["Name"=>"Mixer", "Instance"=>1000, "SetID"=>1035],
               ],
            6=>"DELETE", // Pedal Matrix
            7=>[
                0=>["Instance"=>13000, "SetID"=>1039],
               ],
    ];

    protected $patchDivisions=[
            6=>["DivisionID"=>6, "Name"=>"Blower"],
            7=>["DivisionID"=>7, "Name"=>"Tracker"],
            8=>["DivisionID"=>8, "Name"=>"Stop"],
            9=>["DivisionID"=>9, "Name"=>"Tremulant"]
    ];

    protected $patchTremulants=[
        25=>["Type"=>"Wave",  "DivisionID"=>3, "GroupIDs"=>[301,302,303,304]],
        45=>["Type"=>"Wave",  "DivisionID"=>4, "GroupIDs"=>[401,402,403,404]],
    ];

    protected $patchEnclosures=[
        901=>["Panels"=>[5=>[800]], "EnclosureID"=>901, "Name"=>"Direct",
            "GroupIDs"=>[101,201,301,401,601,701,801.901], "AmpMinimumLevel"=>1],
        902=>["Panels"=>[5=>[810]], "EnclosureID"=>903,"Name"=>"Diffuse",
            "GroupIDs"=>[102,202,302,402,602,702,802.902], "AmpMinimumLevel"=>1],
        903=>["Panels"=>[5=>[820]], "EnclosureID"=>903,"Name"=>"Distant",
            "GroupIDs"=>[103,203,303,403,603,703,803.903], "AmpMinimumLevel"=>1],
        904=>["Panels"=>[5=>[830]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304,404,604,704,804.904], "AmpMinimumLevel"=>1],

        906=>["Panels"=>[5=>[1691]], "EnclosureID"=>906,"Name"=>"Blower",
            "GroupIDs"=>[601,602,603,604], "AmpMinimumLevel"=>1],
        /* 907=>["Panels"=>[5=>[1692]], "EnclosureID"=>907,"Name"=>"Tracker",
            "GroupIDs"=>[701,702,703,704], "AmpMinimumLevel"=>1], */
        908=>["Panels"=>[5=>[1690]], "EnclosureID"=>908,"Name"=>"Stops",
            "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>1],
        909=>["Panels"=>[5=>[1692]], "EnclosureID"=>909,"Name"=>"Tremulant",
            "GroupIDs"=>[901,902,903,904], "AmpMinimumLevel"=>1],
        
        998=>["Panels"=>[2=>[987, NULL, 987], 3=>[978, NULL, 978], 7=>[985]], 
            "GroupIDs"=>[301,302,303,304], "AmpMinimumLevel"=>20], // sw
        997=>["Panels"=>[2=>[981, NULL, 981], 4=>[984, NULL, 984], 7=>[979]], 
            "GroupIDs"=>[401,402,403,404], "AmpMinimumLevel"=>20], // Pos
   ];

    protected $patchStops=[
        -101=>["StopID"=>-101, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>1050, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>601],
        -102=>["StopID"=>-102, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>1050, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>602],
        -103=>["StopID"=>-103, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>1050, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>603],
        -104=>["StopID"=>-104, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>1050, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>604],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"GO Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -112=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"R Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -113=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"P key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"GO Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"R Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"P key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],

        -131=>["StopID"=>-131, "DivisionID"=>1, "Name"=>"Tremulant",  "ControllingSwitchID"=>NULL,   "Ambient"=>TRUE, "GroupID"=>901],
        -132=>["StopID"=>-132, "DivisionID"=>1, "Name"=>"Tremulant",  "ControllingSwitchID"=>NULL,   "Ambient"=>TRUE, "GroupID"=>902],
        -133=>["StopID"=>-133, "DivisionID"=>1, "Name"=>"Tremulant",  "ControllingSwitchID"=>NULL,   "Ambient"=>TRUE, "GroupID"=>903],
        -134=>["StopID"=>-134, "DivisionID"=>1, "Name"=>"Tremulant",  "ControllingSwitchID"=>NULL,   "Ambient"=>TRUE, "GroupID"=>904],
    ];
    
    protected $patchRanks=[
        999901=>["Noise"=>"Ambient", "GroupID"=>601, "StopIDs"=>[-101]],
        999911=>["Noise"=>"Ambient", "GroupID"=>602, "StopIDs"=>[-102]],
        999921=>["Noise"=>"Ambient", "GroupID"=>603, "StopIDs"=>[-103]],
        999941=>["Noise"=>"Ambient", "GroupID"=>604, "StopIDs"=>[-104]],
        
        999800=>["Noise"=>"StopOff", "GroupID"=>801, "StopIDs"=>[]],
        999810=>["Noise"=>"StopOff", "GroupID"=>802, "StopIDs"=>[]],
        999820=>["Noise"=>"StopOff", "GroupID"=>803, "StopIDs"=>[]],
        999840=>["Noise"=>"StopOff", "GroupID"=>804, "StopIDs"=>[]],
        999900=>["Noise"=>"StopOn",  "GroupID"=>801, "StopIDs"=>[]],
        999910=>["Noise"=>"StopOn",  "GroupID"=>802, "StopIDs"=>[]],
        999940=>["Noise"=>"StopOn",  "GroupID"=>803, "StopIDs"=>[]],
        999920=>["Noise"=>"StopOn",  "GroupID"=>804, "StopIDs"=>[]],
        
        999902=>["Noise"=>"Ambient", "GroupID"=>901, "StopIDs"=>[-131]],
        999912=>["Noise"=>"Ambient", "GroupID"=>902, "StopIDs"=>[-132]],
        999922=>["Noise"=>"Ambient", "GroupID"=>903, "StopIDs"=>[-133]],
        999942=>["Noise"=>"Ambient", "GroupID"=>904, "StopIDs"=>[-134]],
    
        999903=>"DELETE", // Orage
        999913=>"DELETE",
        999923=>"DELETE",
        999943=>"DELETE"
    ];

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 7:
                    echo ($instanceID=$instance["ImageSetInstanceID"]), " ",
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
        $hwdata["Identification_UniqueOrganID"]=2711;
        return parent::createOrgan($hwdata);
    }

    public function configureImage(\GOClasses\GOObject $object, array $data, int $layout=0) : void {
        parent::configureImage($object, $data, $layout);
        unset($object->MouseRectWidth);
        unset($object->MouseRectHeight);
    }
    
    public function createStops(array $stopsdata) : void {
        parent::createStops($stopsdata);
        
        $switch=$this->getSwitch(1050); // Blower
        $panel=$this->getPanel(50);
        $pe=$panel->GUIElement($switch);
        $this->configureImage($pe, ["SwitchID"=>1050], 0);
    }

    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (($hwdata["RankID"] % 10)>4) return NULL;
        return parent::createRank($hwdata, $keynoise);
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
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        SPOrgan::ConfigurePanelEnclosureImages($enclosure, $data);
    }
    
    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $rankdata=$this->patchRanks[$hwdata["RankID"]];
        
        if ($rankdata["Noise"]=="Ambient") {
            $stop=$this->getStop($rankdata["StopIDs"][0]);
            if ($stop!==NULL) {
                $ambience=$stop->Ambience();
                if ($isattack) {
                    $this->configureAttack($hwdata, $ambience);
                    $ambience->LoadRelease="Y";
                }
                else {
                    $this->configureRelease($hwdata, $ambience);
                    $ambience->LoadRelease="N";
                }
                return $ambience;
            }
        }
        else {
            $stopid=($rankdata["Noise"]=="StopOn" ? +1 : -1) * (100*($hwdata["PipeID"] % 100)+$rankdata["GroupID"]-800);
            $stop=$this->getSwitchNoise($stopid, FALSE);
            if ($stop!==NULL) {
                $noise=$stop->Noise();
                $this->configureAttack($hwdata, $noise);
                return $noise;
            }
        }
        return NULL;
    }
    
    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
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
            case 6:
                $hwdata["RankID"]-=4;
                $hwdata["IsTremulant"]=1;
                break;
        }
        $pipe=parent::processSample($hwdata, $isattack);
        // if ($pipe) unset($pipe->ReleaseCrossfadeLength);
        return $pipe;
    }
    
    /**
     * Run the import
     */
    public static function Warrington(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new Warrington(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
                unset($stop->Rank004PipeCount);
            }
            
            // Tremulant needs to be activated by switch 25 or switch 45
            $sw25=$hwi->getSwitch(25);
            $sw45=$hwi->getSwitch(45);
            foreach ([-131,-132,-133,-134] as $stopid) {
                $stop=$hwi->getStop($stopid);
                if ($stop) {
                    $stop->Function="Or";
                    $stop->Switch($sw25);
                    $stop->Switch($sw45);
                }
            }

            $hwi->getOrgan()->ChurchName=str_replace("Dem", "Demo ($target)", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            self::Warrington(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Warrington(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Warrington(
                    [self::RANKS_DISTANT=>"Distant"],
                     "Distant");
            self::Warrington(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::Warrington(
                    [
                        self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_DISTANT=>"Distant",
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

Warrington::Warrington();