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
 * Import Sonus Paradisi Arnstadt, Bach organ (Wender 1703) Demo to GrandOrgue
 * 
 * @author andrew
 */

class Arnstadt extends SPOrganV2 {
    const ROOT="/GrandOrgue/Organs/SP/ArnstadtFull/";
    const VERSION="1.1";
    const REVISIONS="\n"
            . "1.1 Added full surround\n"
            . "\n";
    
    const RANKS_DIRECT=1;
    const RANKS_DIFFUSE=2;
    const RANKS_DISTANT=3;
    const RANKS_REAR=4;

    protected ?int $releaseCrossfadeLengthMs=0;
    
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,      9=>self::RANKS_DIRECT,
        1=>self::RANKS_DIFFUSE,     7=>self::RANKS_DIFFUSE,
        2=>self::RANKS_DISTANT,     6=>self::RANKS_DISTANT,
        4=>self::RANKS_REAR,        8=>self::RANKS_REAR,
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030] // Console
               ],
            2=>[
                0=>["SetID"=>1035, "Instance"=>11000], // Simple
               ],
            3=>"DELETE",
            4=>"DELETE",
            5=>[
                0=>["SetID"=>1036, "Instance"=>800] // Mixer
               ],
            6=>[
                0=>["SetID"=>1038, "Instance"=>13000] // Stops
               ],
    ];

    protected $patchDivisions=[
            6=>["DivisionID"=>6, "Name"=>"Tremulant"],
            7=>["DivisionID"=>7, "Name"=>"Stops"],
            8=>["DivisionID"=>8, "Name"=>"Ambience"],
            9=>["DivisionID"=>9, "Name"=>"Tracker"],
           10=>["DivisionID"=>10, "Name"=>"Vogelsang"],
           11=>["DivisionID"=>11, "Name"=>"Glocken"],
    ];

    protected $patchTremulants=[
        26=>["Type"=>"Switched", "DivisionID"=>3], 
    ];

    protected $patchEnclosures=[
        // NB There is a bug in GO when AmpMinimumLevel is set to 0!
        901=>["Panels"=>[5=>[1600]], "EnclosureID"=>901, "Name"=>"Semi-Dry",
            "GroupIDs"=>[101,201,301,601,701,801,901], "AmpMinimumLevel"=>1],
        902=>["Panels"=>[5=>[1610]], "EnclosureID"=>902, "Name"=>"Diffuse",
            "GroupIDs"=>[102,202,302,602,702,802,902], "AmpMinimumLevel"=>1],
        903=>["Panels"=>[5=>[1620]], "EnclosureID"=>903,"Name"=>"Distant",
            "GroupIDs"=>[103,203,303,603,703,803,903], "AmpMinimumLevel"=>1],
        904=>["Panels"=>[5=>[1630]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304,604,704,904,904], "AmpMinimumLevel"=>1],
        906=>["Panels"=>[5=>[1695]], "EnclosureID"=>906,"Name"=>"Tremulant",
            "GroupIDs"=>[601,602,603,604], "AmpMinimumLevel"=>1],
        907=>["Panels"=>[5=>[1690]], "EnclosureID"=>907,"Name"=>"Stops",
            "GroupIDs"=>[701,702,703,704], "AmpMinimumLevel"=>1],
        908=>["Panels"=>[5=>[1691]], "EnclosureID"=>908,"Name"=>"Ambience",
            "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>1],
        909=>["Panels"=>[5=>[1692]], "EnclosureID"=>909,"Name"=>"Tracker",
            "GroupIDs"=>[901,902,903,904], "AmpMinimumLevel"=>1],
        910=>["Panels"=>[5=>[1694]], "EnclosureID"=>910,"Name"=>"Vogelsang",
            "GroupIDs"=>[1001,1002,1003,1004], "AmpMinimumLevel"=>1],
        911=>["Panels"=>[5=>[1693]], "EnclosureID"=>911,"Name"=>"Glocken",
            "GroupIDs"=>[1101,1102,1103,1104], "AmpMinimumLevel"=>1],
    ];

    protected $patchKeyActions=[
        1=>"DELETE",
        2=>["MIDINoteNumOfFirstSourceKey"=>36, "NumberOfKeys"=>30],
        3=>"DELETE",
        4=>"DELETE",
    ];
    
    protected $patchStops=[
        -101=>["StopID"=>-101, "DivisionID"=>1, "Name"=>"Blower (Semi Dry)",    "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>801],
        -102=>["StopID"=>-102, "DivisionID"=>1, "Name"=>"Blower (Diffuse)",     "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>802],
        -103=>["StopID"=>-103, "DivisionID"=>1, "Name"=>"Blower (Distant)",     "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>803],
        -104=>["StopID"=>-104, "DivisionID"=>1, "Name"=>"Blower (Rear)",        "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>804],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"Tremulant (Semi Dry)", "ControllingSwitchID"=>26, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>601],
        -112=>["StopID"=>-112, "DivisionID"=>1, "Name"=>"Tremulant (Diffuse)",  "ControllingSwitchID"=>26, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>602],
        -113=>["StopID"=>-113, "DivisionID"=>1, "Name"=>"Tremulant (Distant)",  "ControllingSwitchID"=>26, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>603],
        -114=>["StopID"=>-114, "DivisionID"=>1, "Name"=>"Tremulant (Rear)",     "ControllingSwitchID"=>26, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>604],

        -121=>["StopID"=>-121, "DivisionID"=>2, "Name"=>"HW Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>3, "Name"=>"OW Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-123, "DivisionID"=>1, "Name"=>"P Key On",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -131=>["StopID"=>-131, "DivisionID"=>2, "Name"=>"HW Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -132=>["StopID"=>-132, "DivisionID"=>3, "Name"=>"OW Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -133=>["StopID"=>-133, "DivisionID"=>1, "Name"=>"P Key Off",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        
          -7=>["StopID"=>  -7, "DivisionID"=>1, "Name"=>"Glocken C (Semi Dry)", "ControllingSwitchID"=>19007, "Ambient"=>TRUE, "GroupID"=>1101],
        -107=>["StopID"=>-107, "DivisionID"=>1, "Name"=>"Glocken C (Diffuse)",  "ControllingSwitchID"=>19007, "Ambient"=>TRUE, "GroupID"=>1102],
        -207=>["StopID"=>-207, "DivisionID"=>1, "Name"=>"Glocken C (Distant)",  "ControllingSwitchID"=>19007, "Ambient"=>TRUE, "GroupID"=>1103],
        -407=>["StopID"=>-407, "DivisionID"=>1, "Name"=>"Glocken C (Rear)",     "ControllingSwitchID"=>19007, "Ambient"=>TRUE, "GroupID"=>1104],
         -15=>["StopID"=> -15, "DivisionID"=>1, "Name"=>"Glocken G (Semi Dry)", "ControllingSwitchID"=>19015, "Ambient"=>TRUE, "GroupID"=>1101],
        -115=>["StopID"=>-115, "DivisionID"=>1, "Name"=>"Glocken G (Diffuse)",  "ControllingSwitchID"=>19015, "Ambient"=>TRUE, "GroupID"=>1102],
        -215=>["StopID"=>-215, "DivisionID"=>1, "Name"=>"Glocken G (Distant)",  "ControllingSwitchID"=>19015, "Ambient"=>TRUE, "GroupID"=>1103],
        -415=>["StopID"=>-415, "DivisionID"=>1, "Name"=>"Glocken G (Rear)",     "ControllingSwitchID"=>19015, "Ambient"=>TRUE, "GroupID"=>1104],
         -28=>["StopID"=> -28, "DivisionID"=>1, "Name"=>"Calcant (Semi Dry)",   "ControllingSwitchID"=>19028, "Ambient"=>TRUE, "GroupID"=>1001],
        -128=>["StopID"=>-128, "DivisionID"=>1, "Name"=>"Calcant (Diffuse)",    "ControllingSwitchID"=>19028, "Ambient"=>TRUE, "GroupID"=>1002],
        -228=>["StopID"=>-228, "DivisionID"=>1, "Name"=>"Calcant (Distant)",    "ControllingSwitchID"=>19028, "Ambient"=>TRUE, "GroupID"=>1003],
        -428=>["StopID"=>-428, "DivisionID"=>1, "Name"=>"Calcant (Rear)",       "ControllingSwitchID"=>19028, "Ambient"=>TRUE, "GroupID"=>1004],
         -29=>["StopID"=> -29, "DivisionID"=>1, "Name"=>"Vogelsang (Semi Dry)", "ControllingSwitchID"=>19029, "Ambient"=>TRUE, "GroupID"=>1001],
        -129=>["StopID"=>-129, "DivisionID"=>1, "Name"=>"Vogelsang (Diffuse)",  "ControllingSwitchID"=>19029, "Ambient"=>TRUE, "GroupID"=>1002],
        -229=>["StopID"=>-339, "DivisionID"=>1, "Name"=>"Vogelsang (Distant)",  "ControllingSwitchID"=>19029, "Ambient"=>TRUE, "GroupID"=>1003],
        -429=>["StopID"=>-429, "DivisionID"=>1, "Name"=>"Vogelsang (Rear)",     "ControllingSwitchID"=>19029, "Ambient"=>TRUE, "GroupID"=>1004],
 ];
    
    protected $patchRanks=[
        999800=>["Noise"=>"StopOn",  "GroupID"=>701, "StopIDs"=>[]], // Front A (direct): Stop noise Off
        999810=>["Noise"=>"StopOn",  "GroupID"=>702, "StopIDs"=>[]], // Front B (diffuse): Stop noise Off
        999820=>["Noise"=>"StopOn",  "GroupID"=>703, "StopIDs"=>[]], // Front C (distant): Stop noise Off
        999840=>["Noise"=>"StopOn",  "GroupID"=>704, "StopIDs"=>[]], // Rear: Stop noise Off
        999900=>["Noise"=>"StopOff", "GroupID"=>701, "StopIDs"=>[]], // Front A (direct): Stop noise On
        999910=>["Noise"=>"StopOff", "GroupID"=>702, "StopIDs"=>[]], // Front B (diffuse): Stop noise On
        999920=>["Noise"=>"StopOff", "GroupID"=>703, "StopIDs"=>[]], // Front C (distant): Stop noise On
        999940=>["Noise"=>"StopOff", "GroupID"=>704, "StopIDs"=>[]], // Rear: Stop noise On
        998102=>["Noise"=>"KeyOn",   "GroupID"=>901, "StopIDs"=>[-121]], // Front A (direct): Keyboard noise On 1. man.
        998112=>["Noise"=>"KeyOn",   "GroupID"=>902, "StopIDs"=>[-121]], // Front B (diffuse): Keyboard noise On 1. man.
        998122=>["Noise"=>"KeyOn",   "GroupID"=>903, "StopIDs"=>[-121]], // Front C (distant): Keyboard noise On 1. man.
        998142=>["Noise"=>"KeyOn",   "GroupID"=>904, "StopIDs"=>[-121]], // Rear: Keyboard noise On 1. man.
        998152=>["Noise"=>"KeyOff",  "GroupID"=>901, "StopIDs"=>[-131]], // Front A (direct): Keyboard noise Off 1. man.
        998162=>["Noise"=>"KeyOff",  "GroupID"=>902, "StopIDs"=>[-131]], // Front B (diffuse): Keyboard noise Off 1. man.
        998172=>["Noise"=>"KeyOff",  "GroupID"=>903, "StopIDs"=>[-131]], // Front C (distant): Keyboard noise Off 1. man.
        998192=>["Noise"=>"KeyOff",  "GroupID"=>904, "StopIDs"=>[-131]], // Rear: Keyboard noise Off 1. man.
        998202=>["Noise"=>"KeyOn",   "GroupID"=>901, "StopIDs"=>[-122]], // Front A (direct): Keyboard noise On 2. man.
        998212=>["Noise"=>"KeyOn",   "GroupID"=>902, "StopIDs"=>[-122]], // Front B (diffuse): Keyboard noise On 2. man.
        998222=>["Noise"=>"KeyOn",   "GroupID"=>903, "StopIDs"=>[-122]], // Front C (distant): Keyboard noise On 2. man.
        998242=>["Noise"=>"KeyOn",   "GroupID"=>904, "StopIDs"=>[-122]], // Rear: Keyboard noise On 2. man.
        998252=>["Noise"=>"KeyOff",  "GroupID"=>901, "StopIDs"=>[-132]], // Front A (direct): Keyboard noise Off 2. man.
        998262=>["Noise"=>"KeyOff",  "GroupID"=>902, "StopIDs"=>[-132]], // Front B (diffuse): Keyboard noise Off 2. man.
        998272=>["Noise"=>"KeyOff",  "GroupID"=>903, "StopIDs"=>[-132]], // Front C (distant): Keyboard noise Off 2. man.
        998292=>["Noise"=>"KeyOff",  "GroupID"=>904, "StopIDs"=>[-132]], // Rear: Keyboard noise Off 2. man.
        998602=>["Noise"=>"KeyOn",   "GroupID"=>901, "StopIDs"=>[-113]], // Front A (direct): Keyboard noise On Pedal
        998612=>["Noise"=>"KeyOn",   "GroupID"=>902, "StopIDs"=>[-123]], // Front B (diffuse): Keyboard noise On Pedal
        998622=>["Noise"=>"KeyOn",   "GroupID"=>903, "StopIDs"=>[-123]], // Front C (distant): Keyboard noise On Pedal
        998642=>["Noise"=>"KeyOn",   "GroupID"=>904, "StopIDs"=>[-123]], // Rear: Keyboard noise On Pedal
        998652=>["Noise"=>"KeyOff",  "GroupID"=>901, "StopIDs"=>[-133]], // Front A (direct): Keyboard noise Off Pedal
        998662=>["Noise"=>"KeyOff",  "GroupID"=>902, "StopIDs"=>[-133]], // Front B (diffuse): Keyboard noise Off Pedal
        998672=>["Noise"=>"KeyOff",  "GroupID"=>903, "StopIDs"=>[-133]], // Front C (distant): Keyboard noise Off Pedal
        998692=>["Noise"=>"KeyOff",  "GroupID"=>904, "StopIDs"=>[-133]], // Rear: Keyboard noise Off Pedal
        999901=>["Noise"=>"Ambient", "GroupID"=>801, "StopIDs"=>[+250]], // Front A (direct): Blower noise
        999903=>["Noise"=>"Ambient", "GroupID"=>1101, "StopIDs"=>[]], // Front A (direct): Glocken
        999904=>["Noise"=>"Ambient", "GroupID"=>1001, "StopIDs"=>[]], // Front A (direct): Vogelsang
        999905=>["Noise"=>"Ambient", "GroupID"=>601, "StopIDs"=>[-111]], // Front A (direct): Tremulant noise
        999911=>["Noise"=>"Ambient", "GroupID"=>802, "StopIDs"=>[+251]], // Front B (diffuse): Blower noise
        999913=>["Noise"=>"Ambient", "GroupID"=>1102, "StopIDs"=>[]], // Front B (diffuse): Glocken
        999914=>["Noise"=>"Ambient", "GroupID"=>1002, "StopIDs"=>[]], // Front B (diffuse): Vogelsang
        999915=>["Noise"=>"Ambient", "GroupID"=>602, "StopIDs"=>[-112]], // Front B (diffuse): Tremulant noise
        999921=>["Noise"=>"Ambient", "GroupID"=>803, "StopIDs"=>[+252]], // Front C (distant): Blower noise
        999923=>["Noise"=>"Ambient", "GroupID"=>1003, "StopIDs"=>[]], // Front C (distant): Glocken
        999924=>["Noise"=>"Ambient", "GroupID"=>1003, "StopIDs"=>[]], // Front C (distant): Vogelsang
        999925=>["Noise"=>"Ambient", "GroupID"=>603, "StopIDs"=>[-113]], // Front C (distant): Tremulant noise
        999941=>["Noise"=>"Ambient", "GroupID"=>804, "StopIDs"=>[+253]], // Rear: Blower noise
        999943=>["Noise"=>"Ambient", "GroupID"=>1004, "StopIDs"=>[]], // Rear: Glocken
        999944=>["Noise"=>"Ambient", "GroupID"=>1004, "StopIDs"=>[]], // Rear: Vogelsang
        999945=>["Noise"=>"Ambient", "GroupID"=>604, "StopIDs"=>[-114]], // Rear: Tremulant noise
    ];

    public function import(): void {
        $this->releaseCrossfadeLengthMs=NULL;
        $this->root=self::ROOT;
        parent::import();
        foreach ($this->getStops() as $stop) {
            for ($rn=1; $rn<10; $rn++) {
                $r=sprintf("Rank%03dPipeCount", $rn);
                if ($stop->isset($r) && $stop->get($r)>61)
                    $stop->set($r, 61);
            }
        }
    }

    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        // The one tremulant switch needs to act across all divisions
        $this->patchTremulants[26]["DivisionID"]=$hwdata["DivisionID"];
        return parent::createStop($hwdata);
    }

    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (isset($hwdata["RankID"])) {
            return parent::createRank($hwdata, $keynoise);
        }
        return NULL;
    }
   
    public function addImages() : void {
        $this->addPanelImages(5, 200000); // Coupling
        $this->addPanelImages(5, 200010); // Detune
    }

    
    public function createOrgan(array $hwdata): \GOClasses\Organ {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 2:
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
        $hwdata["Identification_UniqueOrganID"]=2310;
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
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        static $layouts=[0=>"", 1=>"AlternateScreenLayout1_",
            2=>"AlternateScreenLayout2_", 3=>"AlternateScreenLayout3_"];

        if (isset($data["ShutterPositionContinuousControlID"]) 
                && !empty($data["ShutterPositionContinuousControlID"])) {
            $hwd=$this->hwdata;
            $slink=$hwd->continuousControlLink($data["ShutterPositionContinuousControlID"])["D"][0];
            $dlinks=$hwd->continuousControlLink($slink["SourceControlID"]);
            foreach($dlinks["S"] as $dlink) {
                $control=$hwd->continuousControl($dlink["DestControlID"]);
                if (isset($control["ImageSetInstanceID"])) {
                    $instance=$hwd->imageSetInstance($control["ImageSetInstanceID"]);
                    if ($instance!==NULL
                            && isset($this->patchDisplayPages[$instance["DisplayPageID"]])) {
                        foreach($layouts as $layoutid=>$layout) {
                            if (isset($instance["${layout}ImageSetID"])
                                && !empty($instance["${layout}ImageSetID"])) {
                                $panel=$this->getPanel(($instance["DisplayPageID"]*10)+$layoutid);
                                if ($panel!==NULL) {
                                    $pe=$panel->GUIElement($enclosure);
                                    $this->configureEnclosureImage($pe, ["InstanceID"=>$instance["ImageSetInstanceID"]], $layoutid);
                                }
                            }
                        }
                    }
                }
            }
        }
        else
            parent::configurePanelEnclosureImages($enclosure, $data);
    }
    
    protected function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        if (isset($data["ControllingSwitchID"]))
            switch ($data["ControllingSwitchID"]) {
                case 1050:
                    $data["StopID"]=250;
                    break;
               
                case 19007:
                case 19015:
                case 19028:
                case 19029:
                    $data["StopID"]=$data["ControllingSwitchID"]-19000;
                    break;
        }
        parent::configurePanelSwitchImages($switch, $data);
    }
            
    protected function sampleTuning(array $hwdata) : ?float {
        return NULL;
    }
    
    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        if (strpos($hwdata["SampleFilename"],"Calcant") ||
            in_array($hwdata["RankID"]%100,[3,4,13,14,23,24,43,44])) {
            $stop=$this->getStop(-$hwdata["PipeID"] % 1000);
            if ($stop!==NULL) {
                $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
                $ambience=$stop->Ambience();
                $this->configureAttack($hwdata, $ambience);
                return $ambience;
            }
            return NULL;
        }
        return parent::processNoise($hwdata, $isattack);
    }
    
}

class ArnstadtDemo extends Arnstadt {
    const SOURCE="OrganDefinitions/Arnstadt, Bachkirche Wender Organ, Demo.Organ_Hauptwerk_xml";
    const TARGET=Arnstadt::ROOT . "Arnstadt, Bachkirche Wender Organ %s Demo " . Arnstadt::VERSION . ".organ";
    
    public function patchData(\HWClasses\HWData $hwd): void {
        unset ($this->patchDivisions[11]);
        unset ($this->patchEnclosures[910]);
        unset ($this->patchEnclosures[911]);
        parent::patchData($hwd);
    }
    
    /**
     * Run the import
     */
    public static function Arnstadt(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
        if (sizeof($positions)>0) {
            $hwi=new ArnstadtDemo(Arnstadt::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("Surround", "$target", $hwi->getOrgan()->ChurchName);
            foreach($hwi->getStops() as $stop) {
                foreach (["001","002","003","004"] as $r) {
                    $pc="Rank{$r}PipeCount";
                    unset($stop->$pc);
                }
            }
            echo $hwi->getOrgan()->ChurchName, "\n";
            unset($hwi->getSwitch(26)->GCState);
            $hwi->addImages();
            $hwi->saveODF(sprintf(self::TARGET, $target), Arnstadt::REVISIONS);
        }
        else { 
            self::Arnstadt(
                    [Arnstadt::RANKS_DIRECT=>"Direct"],
                    "Semi-Dry");
            self::Arnstadt(
                    [Arnstadt::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Arnstadt(
                    [Arnstadt::RANKS_DISTANT=>"Distant"],
                     "Distant");
            self::Arnstadt(
                    [Arnstadt::RANKS_REAR=>"Rear"],
                    "Rear");
            self::Arnstadt(
                    [
                        Arnstadt::RANKS_DIRECT=>"Direct", 
                        Arnstadt::RANKS_DIFFUSE=>"Diffuse", 
                        Arnstadt::RANKS_DISTANT=>"Distant", 
                        Arnstadt::RANKS_REAR=>"Rear"
                    ],
                   "Surround");
        }
    }
}

class ArnstadtFull extends Arnstadt {
    const SOURCE="OrganDefinitions/Arnstadt, Bachkirche Wender Organ, Surround.Organ_Hauptwerk_xml";
    const TARGET=Arnstadt::ROOT . "Arnstadt, Bachkirche Wender Organ %s " . Arnstadt::VERSION . ".organ";
    
    /**
     * Run the import
     */
    public static function Arnstadt(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
        if (sizeof($positions)>0) {
            $hwi=new ArnstadtFull(Arnstadt::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("Surround", "$target", $hwi->getOrgan()->ChurchName);
            foreach($hwi->getStops() as $stop) {
                foreach (["001","002","003","004"] as $r) {
                    $pc="Rank{$r}PipeCount";
                    unset($stop->$pc);
                }
            }
            echo $hwi->getOrgan()->ChurchName, "\n";
            unset($hwi->getSwitch(26)->GCState);
            $hwi->addImages();
            $hwi->saveODF(sprintf(self::TARGET, $target), Arnstadt::REVISIONS);
        }
        else {
            self::Arnstadt(
                    [Arnstadt::RANKS_DIRECT=>"Direct"],
                    "Semi-Dry");
            self::Arnstadt(
                    [Arnstadt::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Arnstadt(
                    [Arnstadt::RANKS_DISTANT=>"Distant"],
                     "Distant");
            self::Arnstadt(
                    [Arnstadt::RANKS_REAR=>"Rear"],
                    "Rear");
            self::Arnstadt(
                    [
                        Arnstadt::RANKS_DIRECT=>"Direct", 
                        Arnstadt::RANKS_DIFFUSE=>"Diffuse", 
                        Arnstadt::RANKS_DISTANT=>"Distant", 
                        Arnstadt::RANKS_REAR=>"Rear"
                    ],
                   "Surround");
        }
    }
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}

ArnstadtFull::Arnstadt();
ArnstadtDemo::Arnstadt();