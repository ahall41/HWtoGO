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
 * Import Sonus Paradisi Segovia Cathedral: Echevarria organ (1772)
 * 
 * @todo: Trumpet vs blower
 * 
 * @author andrewZ`
 */
class Segovia extends SPOrganV2 {
    const ROOT="/GrandOrgue/Organs/SP/Segovia/";
    const SOURCE=self::ROOT . "OrganDefinitions/Segovia, Echevarria Organ, Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Segovia, Echevarria Organ, Demo (%s).1.2.organ";
    const REVISIONS="\n"
            . "1.1 Corrected releases on ranks without tremmed samples\n"
            . "1.2 Corrected panel image placement\n"
            . "\n";
    
    const RANKS_DIRECT=1;
    const RANKS_DIFFUSE=2;
    const RANKS_DISTANT=3;
    const RANKS_REAR=4;
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,   9=>self::RANKS_DIRECT,
        1=>self::RANKS_DIFFUSE,  7=>self::RANKS_DIFFUSE,
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
            6=>[
                0=>["Group"=>"Single", "Name"=>"Standard", "Instance"=>13000, "SetID"=>1038],
                1=>["Group"=>"Single", "Name"=>"Wide", "Instance"=>13000, "SetID"=>1039],
                ],
    ];

    protected $patchDivisions=[
            6=>["DivisionID"=>6, "Name"=>"Blower"],
            7=>["DivisionID"=>7, "Name"=>"Tracker"],
            8=>["DivisionID"=>8, "Name"=>"Stop"]
    ];

    protected $patchTremulants=[
        67=>["Type"=>"Wave",  "DivisionID"=>2, "GroupIDs"=>[201,202,203,204,301,302,303,304,401,402,403,404]],
    ];

    protected $patchEnclosures=[
        901=>["Panels"=>[5=>[800]], "EnclosureID"=>901, "Name"=>"Direct",
            "GroupIDs"=>[101,201,301,401,601,701,801], "AmpMinimumLevel"=>1],
        902=>["Panels"=>[5=>[810]], "EnclosureID"=>903,"Name"=>"Diffuse",
            "GroupIDs"=>[102,202,302,402,602,702,802], "AmpMinimumLevel"=>1],
        904=>["Panels"=>[5=>[820]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304,404,604,704,804], "AmpMinimumLevel"=>1],

        906=>["Panels"=>[5=>[1691]], "EnclosureID"=>906,"Name"=>"Blower",
            "GroupIDs"=>[601,602,604], "AmpMinimumLevel"=>1],
        907=>["Panels"=>[5=>[1692]], "EnclosureID"=>907,"Name"=>"Tracker",
            "GroupIDs"=>[701,702,704], "AmpMinimumLevel"=>1],
        908=>["Panels"=>[5=>[1690]], "EnclosureID"=>908,"Name"=>"Stops",
            "GroupIDs"=>[801,802,804], "AmpMinimumLevel"=>1],
        
        998=>["Panels"=>[2=>[988, NULL, 988], 3=>[984, NULL, 984], 6=>[986, 986]], 
            "GroupIDs"=>[201,202,204], "AmpMinimumLevel"=>20], // Echo
   ];

    protected $patchStops=[
        -101=>["StopID"=>-101, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>1050, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>601],
        -102=>["StopID"=>-102, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>1050, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>602],
        -104=>["StopID"=>-104, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>1050, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>604],
        -110=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"P Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -111=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"1 Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -112=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"2 key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -113=>["StopID"=>-114, "DivisionID"=>4, "Name"=>"2 key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -120=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"P Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -121=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"1 Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"2 key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-124, "DivisionID"=>4, "Name"=>"3 key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
     ];
    
    protected $patchRanks=[
        999901=>["Noise"=>"Ambient", "GroupID"=>601, "StopIDs"=>[-101]],
        999911=>["Noise"=>"Ambient", "GroupID"=>602, "StopIDs"=>[-102]],
        999941=>["Noise"=>"Ambient", "GroupID"=>604, "StopIDs"=>[-104]],
        
        999800=>["Noise"=>"StopOff", "GroupID"=>801, "StopIDs"=>[]],
        999810=>["Noise"=>"StopOff", "GroupID"=>802, "StopIDs"=>[]],
        999840=>["Noise"=>"StopOff", "GroupID"=>803, "StopIDs"=>[]],
        999900=>["Noise"=>"StopOn",  "GroupID"=>801, "StopIDs"=>[]],
        999910=>["Noise"=>"StopOn",  "GroupID"=>802, "StopIDs"=>[]],
        999940=>["Noise"=>"StopOn",  "GroupID"=>803, "StopIDs"=>[]],

        998602=>["Noise"=>"KeyOn",   "GroupID"=>901, "StopIDs"=>[-110]],
        998612=>["Noise"=>"KeyOn",   "GroupID"=>902, "StopIDs"=>[-110]],
        998642=>["Noise"=>"KeyOn",   "GroupID"=>904, "StopIDs"=>[-110]],
        998102=>["Noise"=>"KeyOn",   "GroupID"=>901, "StopIDs"=>[-111]],
        998112=>["Noise"=>"KeyOn",   "GroupID"=>902, "StopIDs"=>[-111]],
        998142=>["Noise"=>"KeyOn",   "GroupID"=>904, "StopIDs"=>[-111]],
        998202=>["Noise"=>"KeyOn",   "GroupID"=>901, "StopIDs"=>[-112]],
        998212=>["Noise"=>"KeyOn",   "GroupID"=>902, "StopIDs"=>[-112]],
        998242=>["Noise"=>"KeyOn",   "GroupID"=>904, "StopIDs"=>[-112]],
        998302=>["Noise"=>"KeyOn",   "GroupID"=>901, "StopIDs"=>[-113]],
        998312=>["Noise"=>"KeyOn",   "GroupID"=>902, "StopIDs"=>[-113]],
        998342=>["Noise"=>"KeyOn",   "GroupID"=>904, "StopIDs"=>[-113]],

        998652=>["Noise"=>"KeyOff",  "GroupID"=>901, "StopIDs"=>[-120]],
        998662=>["Noise"=>"KeyOff",  "GroupID"=>902, "StopIDs"=>[-120]],
        998692=>["Noise"=>"KeyOff",  "GroupID"=>904, "StopIDs"=>[-120]],
        998152=>["Noise"=>"KeyOff",  "GroupID"=>901, "StopIDs"=>[-121]],
        998162=>["Noise"=>"KeyOff",  "GroupID"=>902, "StopIDs"=>[-121]],
        998192=>["Noise"=>"KeyOff",  "GroupID"=>904, "StopIDs"=>[-121]],
        998252=>["Noise"=>"KeyOff",  "GroupID"=>901, "StopIDs"=>[-122]],
        998262=>["Noise"=>"KeyOff",  "GroupID"=>902, "StopIDs"=>[-122]],
        998292=>["Noise"=>"KeyOff",  "GroupID"=>904, "StopIDs"=>[-122]],
        998352=>["Noise"=>"KeyOff",  "GroupID"=>901, "StopIDs"=>[-123]],
        998362=>["Noise"=>"KeyOff",  "GroupID"=>902, "StopIDs"=>[-123]],
        998392=>["Noise"=>"KeyOff",  "GroupID"=>904, "StopIDs"=>[-123]],

        ];

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 6:
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
        $hwdata["Identification_UniqueOrganID"]=2716;
        return parent::createOrgan($hwdata);
    }

    public function configureImage(\GOClasses\GOObject $object, array $data, int $layout=0) : void {
        parent::configureImage($object, $data, $layout);
        $imagedata=$this->getImageData($data, $layout);
        $object->MouseRectWidth=$imagedata["ImageWidthPixels"];
        $object->MouseRectHeight=$imagedata["ImageHeightPixels"];
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
        if (isset($data["Panels"])) {
            foreach ($data["Panels"] as $panelid=>$instances) {
                foreach ($instances as $layout=>$instanceid) {
                    if ($instanceid!==NULL) {
                        $panel=$this->getPanel(($panelid*10)+$layout, FALSE);
                        if ($panel!==NULL) {
                            $pe=$this->getPanel(($panelid*10)+$layout)->GUIElement($enclosure);
                            $this->configureEnclosureImage($pe, ["InstanceID"=>$instanceid], $layout);
                        }
                    }
                }
            }
        }
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
        return $pipe;
    }
    
    /**
     * Run the import
     */
    public static function Segovia(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
        if (sizeof($positions)>0) {
            $hwi=new Segovia(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
                unset($stop->Rank004PipeCount);
                unset($stop->Rank005PipeCount);
                unset($stop->Rank006PipeCount);
            }
            
            $hwi->getManual(1)->NumberOfLogicalKeys=
                     $hwi->getManual(1)->NumberOfAccessibleKeys=32;
            $hwi->getOrgan()->ChurchName.= " ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            self::Segovia(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Segovia(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Segovia(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::Segovia(
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

Segovia::Segovia();