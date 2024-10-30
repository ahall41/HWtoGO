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
 * Import Sonus Paradisi Este, Mascioni, op. 498 Organ to GrandOrgue
 * 
 * @todo: Noise effects, Stops panels
 * 
 * @author andrewZ`
 */
class Este extends SPOrganV2 {
    const ROOT="/GrandOrgue/Organs/SP/Este/";
    const SOURCE="OrganDefinitions/Este, Mascioni, op. 498, Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Este, Mascioni, op. 498, Demo (%s) 1.3.organ";
    const REVISIONS=
            "1.1 Corrected tremulant\n" .
            "1.2 Add switches to manuals for divisional combinations\n" .
            "1.3 Cross fades corrected for GO 3.14\n";
    
    const RANKS_DISTANT=2;
    
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
                2=>["Group"=>"Simple", "Name"=>"Portrait", "Instance"=>11000, "SetID"=>1038],
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
            7=>"DELETE", /* [
                0=>["Group"=>"Stops", "Name"=>"A", "Instance"=>13000, "SetID"=>1040],
                1=>["Group"=>"Stops", "Name"=>"B", "Instance"=>13000, "SetID"=>1041],
               ], */
            8=>"DELETE" // Crescendo
    ];

    protected $patchDivisions=[
            6=>["DivisionID"=>6, "Name"=>"Blower"],
            7=>["DivisionID"=>7, "Name"=>"Tracker"],
            8=>["DivisionID"=>8, "Name"=>"Stop"],
            9=>["DivisionID"=>9, "Name"=>"Tremulant"]
    ];

    protected $patchTremulants=[
        33=>["Type"=>"Wave",  "DivisionID"=>3, "GroupIDs"=>[301,302,303,304]],
    ];

    protected $patchEnclosures=[
        901=>["Panels"=>[5=>[800]], "EnclosureID"=>901, "Name"=>"Direct",
            "GroupIDs"=>[101,201,301,601,701,801,901], "AmpMinimumLevel"=>1],
        902=>["Panels"=>[5=>[810]], "EnclosureID"=>903,"Name"=>"Diffuse",
            "GroupIDs"=>[102,202,302,602,702,802,902], "AmpMinimumLevel"=>1],
        903=>["Panels"=>[5=>[820]], "EnclosureID"=>903,"Name"=>"Distant",
            "GroupIDs"=>[103,203,303,603,703,803,903], "AmpMinimumLevel"=>1],
        904=>["Panels"=>[5=>[830]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304,604,704,804,904], "AmpMinimumLevel"=>1],

        906=>["Panels"=>[5=>[1691]], "EnclosureID"=>906,"Name"=>"Blower",
            "GroupIDs"=>[601,602,603,604], "AmpMinimumLevel"=>1],
        907=>["Panels"=>[5=>[1692]], "EnclosureID"=>907,"Name"=>"Tracker",
            "GroupIDs"=>[701,702,703,704], "AmpMinimumLevel"=>1],
        908=>["Panels"=>[5=>[1690]], "EnclosureID"=>908,"Name"=>"Stops",
            "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>1],
        909=>["Panels"=>[5=>[1695]], "EnclosureID"=>909,"Name"=>"Tremulant",
            "GroupIDs"=>[901,902,903,904], "AmpMinimumLevel"=>1],
        
        998=>["Panels"=>[2=>[987, NULL, 987], 4=>[983, NULL, 983], /* 7=>[985, 985] */], 
            "GroupIDs"=>[301,302,303,304], "AmpMinimumLevel"=>30], // sw
       
    ];

    protected $patchStops=[
        -101=>["StopID"=>-101, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>50,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>601],
        -102=>["StopID"=>-102, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>50,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>602],
        -103=>["StopID"=>-103, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>50,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>603],
        -104=>["StopID"=>-104, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>50,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>604],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"GO Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -112=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"R Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -113=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"P key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"GO Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"R Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"P key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -131=>["StopID"=>-131, "DivisionID"=>1, "Name"=>"Tremulant",  "ControllingSwitchID"=>33,   "Ambient"=>TRUE, "GroupID"=>901],
        -132=>["StopID"=>-132, "DivisionID"=>1, "Name"=>"Tremulant",  "ControllingSwitchID"=>33,   "Ambient"=>TRUE, "GroupID"=>902],
        -133=>["StopID"=>-133, "DivisionID"=>1, "Name"=>"Tremulant",  "ControllingSwitchID"=>33,   "Ambient"=>TRUE, "GroupID"=>903],
        -134=>["StopID"=>-134, "DivisionID"=>1, "Name"=>"Tremulant",  "ControllingSwitchID"=>33,   "Ambient"=>TRUE, "GroupID"=>904],
    ];
    
    protected $patchRanks=[
        999901=>["Noise"=>"Ambient", "GroupID"=>601, "StopIDs"=>[-101]],
        999911=>["Noise"=>"Ambient", "GroupID"=>602, "StopIDs"=>[-102]],
        999921=>["Noise"=>"Ambient", "GroupID"=>603, "StopIDs"=>[-103]],
        999941=>["Noise"=>"Ambient", "GroupID"=>604, "StopIDs"=>[-104]],
        
        998102=>["Noise"=>"KeyOn",   "GroupID"=>701, "StopIDs"=>[-111]],
        998112=>["Noise"=>"KeyOn",   "GroupID"=>702, "StopIDs"=>[-111]],
        998122=>["Noise"=>"KeyOn",   "GroupID"=>703, "StopIDs"=>[-111]],
        998142=>["Noise"=>"KeyOn",   "GroupID"=>704, "StopIDs"=>[-111]],
        998152=>["Noise"=>"KeyOff",  "GroupID"=>701, "StopIDs"=>[-121]],
        998162=>["Noise"=>"KeyOff",  "GroupID"=>702, "StopIDs"=>[-121]],
        998172=>["Noise"=>"KeyOff",  "GroupID"=>703, "StopIDs"=>[-121]],
        998192=>["Noise"=>"KeyOff",  "GroupID"=>704, "StopIDs"=>[-121]],
        
        998202=>["Noise"=>"KeyOn",   "GroupID"=>701, "StopIDs"=>[-112]],
        998212=>["Noise"=>"KeyOn",   "GroupID"=>702, "StopIDs"=>[-112]],
        998222=>["Noise"=>"KeyOn",   "GroupID"=>703, "StopIDs"=>[-112]],
        998242=>["Noise"=>"KeyOn",   "GroupID"=>704, "StopIDs"=>[-112]],
        998252=>["Noise"=>"KeyOff",  "GroupID"=>701, "StopIDs"=>[-122]],
        998262=>["Noise"=>"KeyOff",  "GroupID"=>702, "StopIDs"=>[-122]],
        998272=>["Noise"=>"KeyOff",  "GroupID"=>703, "StopIDs"=>[-122]],
        998292=>["Noise"=>"KeyOff",  "GroupID"=>704, "StopIDs"=>[-122]],
        
        998602=>["Noise"=>"KeyOn",   "GroupID"=>701, "StopIDs"=>[-113]],
        998612=>["Noise"=>"KeyOn",   "GroupID"=>702, "StopIDs"=>[-113]],
        998622=>["Noise"=>"KeyOn",   "GroupID"=>703, "StopIDs"=>[-113]],
        998642=>["Noise"=>"KeyOn",   "GroupID"=>704, "StopIDs"=>[-113]],
        998652=>["Noise"=>"KeyOff",  "GroupID"=>701, "StopIDs"=>[-123]],
        998662=>["Noise"=>"KeyOff",  "GroupID"=>702, "StopIDs"=>[-123]],
        998672=>["Noise"=>"KeyOff",  "GroupID"=>703, "StopIDs"=>[-123]],
        998692=>["Noise"=>"KeyOff",  "GroupID"=>704, "StopIDs"=>[-123]],
        
        999900=>["Noise"=>"StopOn",  "GroupID"=>801, "StopIDs"=>[]],
        999910=>["Noise"=>"StopOn",  "GroupID"=>802, "StopIDs"=>[]],
        999940=>["Noise"=>"StopOn",  "GroupID"=>803, "StopIDs"=>[]],
        999920=>["Noise"=>"StopOn",  "GroupID"=>804, "StopIDs"=>[]],
        999800=>["Noise"=>"StopOff", "GroupID"=>801, "StopIDs"=>[]],
        999810=>["Noise"=>"StopOff", "GroupID"=>802, "StopIDs"=>[]],
        999820=>["Noise"=>"StopOff", "GroupID"=>803, "StopIDs"=>[]],
        999840=>["Noise"=>"StopOff", "GroupID"=>804, "StopIDs"=>[]],
        
        999905=>["Noise"=>"Ambient", "GroupID"=>901, "StopIDs"=>[-131]],
        999915=>["Noise"=>"Ambient", "GroupID"=>902, "StopIDs"=>[-132]],
        999925=>["Noise"=>"Ambient", "GroupID"=>903, "StopIDs"=>[-133]],
        999945=>["Noise"=>"Ambient", "GroupID"=>904, "StopIDs"=>[-134]],
    ];

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 5:
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
        $hwdata["Identification_UniqueOrganID"]=2709;
        return parent::createOrgan($hwdata);
    }
    
    public function configureImage(\GOClasses\GOObject $object, array $data, int $layout=0) : void {
        parent::configureImage($object, $data, $layout);
        if ($object->section()=="Panel001Element" && $object->instance()==9){
            $width=$object->MouseRectWidth;
            $object->MouseRectWidth=$object->MouseRectHeight;
            $object->MouseRectHeight=$width;
        }
    }
    
    public function createStops(array $stopsdata) : void {
        parent::createStops($stopsdata);
        $switch=$this->getSwitch(50);
        
        $this->configurePanelSwitchImages($switch, ["StopID"=>50]);
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
                                $panel=$this->getPanel(($instance["DisplayPageID"]*10)+$layoutid, FALSE);
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
    
    protected function sampleTuning(array $hwdata) : ?float {
        return NULL;
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
        // if ($pipe) unset($pipe->PitchTuning);
        return $pipe;
    }
    
    /**
     * Run the import
     */
    public static function Este(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new Este(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
                unset($stop->Rank004PipeCount);
            }
            $hwi->getOrgan()->ChurchName=str_replace("Demo", "Demo ($target)", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            self::Este(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Este(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Este(
                    [self::RANKS_DISTANT=>"Distant"],
                     "Distant");
            self::Este(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::Este(
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

Este::Este();