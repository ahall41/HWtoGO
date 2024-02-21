<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\SP;
require_once __DIR__ . "/SPOrgan.php";

/**
 * Import Sonus Paradisi Skinner Op 497 to GrandOrgue
 * 
 * Version 1.1
 * - Blower on/off
 * - Enclosure MinAmp=20 + add to Console and Left pages
 * - Manual Bass
 * - Mixer Panel
 *  
 * @author andrew
 */
class Skinner497 extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/Skinner497Full/";

    protected $combinations=[
        "crescendos"=>[
            "A"=>[1501,1503,1505,1507,1509,1511,1513,1515,1517,1519,
                  1521,1523,1525,1527,1529,1531,1533,1535,1537,1539,
                  1541,1543,1545,1547,1549,1551,1553,1555,1557,1559,1561,1563]
                ]
        ];
    
    protected $crescendo=[914=>1, 916=>2, 913=>4, 918=>4, 911=>6];
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,   9=>self::RANKS_DIRECT,
        1=>self::RANKS_DIFFUSE,  7=>self::RANKS_DIFFUSE,
        4=>self::RANKS_REAR,     8=>self::RANKS_REAR
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>[
                0=>["Group"=>"Simple", "Name"=>"Landscape", "Instance"=>13000, "SetID"=>1036],
                1=>[],
                2=>["Group"=>"Simple", "Name"=>"Portrait", "Instance"=>13000, "SetID"=>1040],
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
                0=>["Name"=>"Mixer", "SetID"=>1035],
               ],
            7=>"DELETE", // Crescendo
            8=>"DELETE", // Crescendo contd
    ];

    protected $patchDivisions=[
            //6=>"DELETE", // Echo
            8=>["DivisionID"=>8, "Name"=>"Blower", "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    protected $patchTremulants=[
        126=>["Type"=>"Switched", "DivisionID"=>5], // Solo
        125=>["Type"=>"Switched", "DivisionID"=>5], // Echo
         38=>["Type"=>"Switched", "DivisionID"=>4], // Swell
        127=>["Type"=>"Synth",    "DivisionID"=>3, "GroupIDs"=>[301,302,303,304]], // Great
        128=>["Type"=>"Switched", "DivisionID"=>2], // Choir
    ];
    
    protected $patchEnclosures=[
        995=>["GroupIDs"=>[601,603,604], "AmpMinimumLevel"=>40], // Echo
        996=>["GroupIDs"=>[501,503,504], "AmpMinimumLevel"=>40], // Solo
        997=>["GroupIDs"=>[401,403,404], "AmpMinimumLevel"=>40], // Swell
        998=>["GroupIDs"=>[201,203,204], "AmpMinimumLevel"=>40], // Choir
        
        901=>["Panels"=>[5=>[800]], "EnclosureID"=>901, "Name"=>"Direct Mixer",
            "GroupIDs"=>[101,201,301,401,501], "AmpMinimumLevel"=>0],
        903=>["Panels"=>[5=>[810]], "EnclosureID"=>903,"Name"=>"Diffuse Mixer",
            "GroupIDs"=>[103,203,303,403,503], "AmpMinimumLevel"=>0],
        904=>["Panels"=>[5=>[820]], "EnclosureID"=>904,"Name"=>"Rear Mixer",
            "GroupIDs"=>[104,204,304,404,504], "AmpMinimumLevel"=>0],
        908=>["Panels"=>[5=>[1595]], "EnclosureID"=>908,"Name"=>"Blower Mixer",
            "GroupIDs"=>[800], "AmpMinimumLevel"=>0],
        909=>["Panels"=>[5=>[1599]], "EnclosureID"=>909,"Name"=>"Tracker Mixer",
            "GroupIDs"=>[900], "AmpMinimumLevel"=>0],
    ];

    protected $patchStops=[
         124=>["StopID"=> 124, "DivisionID"=>1, "Name"=>"Blower",         "ControllingSwitchID"=>124,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>800],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"Pedale Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -112=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"Choir Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -113=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"Great key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -114=>["StopID"=>-114, "DivisionID"=>4, "Name"=>"Swell key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -115=>["StopID"=>-115, "DivisionID"=>5, "Name"=>"Solo key On",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"Pedale Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"Choir Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"Great key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -124=>["StopID"=>-124, "DivisionID"=>4, "Name"=>"Swell key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -125=>["StopID"=>-125, "DivisionID"=>5, "Name"=>"Solo key Off",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[
          8=>["Noise"=>"StopOn",  "GroupID"=>900, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>900, "StopIDs"=>[]],
        950=>["Noise"=>"Ambient", "GroupID"=>800, "StopIDs"=>[124]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-111]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-112]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-113]],
        984=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-114]],
        985=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-115]],
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-121]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-122]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-123]],
        994=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-124]],
        995=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-125]],
        360=>["DivisionID"=>4], // Chimney Flute
        361=>["DivisionID"=>4],
        364=>["DivisionID"=>4],
        367=>["DivisionID"=>4],
        368=>["DivisionID"=>4],
        369=>["DivisionID"=>4],
        440=>["DivisionID"=>4], // Flute Celeste
        441=>["DivisionID"=>4],
        444=>["DivisionID"=>4],
        447=>["DivisionID"=>4],
        448=>["DivisionID"=>4],
        449=>["DivisionID"=>4],
    ];

    public function import(): void {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        parent::import();
        foreach ($this->getStops() as $id=>$stop) {
            for ($rn=1; $rn<10; $rn++) {
                $pc=sprintf("Rank%03dPipeCount", $rn);
                switch ($id) {
                    case 8:     // P. Bourdon 32
                    case 91:    // G. Second Diapason 8
                        break;
                    
                    default:
                        $stop->unset($pc);
                        break;
                }
           }
        }
        
        foreach ($this->crescendo as $instanceid=>$pageid) {
            foreach([0,1,2,3] as $layoutid) {
                $panel=$this->getPanel(($pageid*10)+$layoutid, FALSE);
                if ($panel) {
                    $cr=$panel->Element();
                    $cr->Type="Swell";
                    $this->configureEnclosureImage($cr, ["InstanceID"=>$instanceid], $layoutid);
                }
            }
        }
        
        $this->addPanelImages(5, 200000); // PipeCoupling Level:
        $this->addPanelImages(5, 200010); // PipeDetun Level
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        /* Uncomment to analyse equalisation
        $levels=[];
        foreach($this->hwdata->layers() as $layer) {
            foreach ($layer as $name=>$value) {
                switch ($name) {
                    case "HarmonicShaping_ThirdAndUpperHarmonicsLevelAdjustDecibels":
                    case "HarmonicShaping_ThirdAndUpperHarmonicsAttnAtThisFlowRateDecibls":
                    case "HarmonicShaping_WindModelModDepthAdjustDecibelsAtThirdHarmonic":
                    case "HarmonicShaping_TremulantModDepthAdjustDecibelsAtThirdHarmonic":
                    case "HarmonicShaping_IncrementingContinuousControlID":
                    case "VoicingEQ01_TransitionFrequencyKHertz":
                    case "VoicingEQ01_TransitionWidthAsPercentOfTransitionFrequency":
                    case "VoicingEQ01_HighFrequencyBoostDecibels":
                        if (isset($levels[$name][$value])) {
                            $levels[$name][$value]++;
                        }
                        else {
                            $levels[$name][$value]=1;
                        }
                }
            }
        }
        print_r($levels);
        exit(); //*/
        
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 5:
                    echo $instance["DisplayPageID"], "\t",
                        ($instanceID=$instance["ImageSetInstanceID"]), "\t",
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
        
        $hwdata["Identification_UniqueOrganID"]=2304; 
        return parent::createOrgan($hwdata);
    }
    
    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["DivisionID"]==6) {$hwdata["DivisionID"]=5;}
        return parent::createStop($hwdata);
    }
    
    public function createSwitchNoise(string $type, array $hwdata): void {
        if (isset($hwdata["DivisionID"]) && $hwdata["DivisionID"]==6) {$hwdata["DivisionID"]=5;}
        parent::createSwitchNoise($type, $hwdata);
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
    
    public function configureRelease(array $hwdata, \GOClasses\Pipe $pipe): void {
        //if (!isset($hwdata["ReleaseSelCriteria_LatestKeyReleaseTimeMs"]))
            parent::configureRelease($hwdata, $pipe);
    }

    protected function sampleTuning(array $hwdata) : ?float {
        return NULL;
    }
    
    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        unset($hwdata["ReleaseCrossfadeLengthMs"]); // =30;
        return parent::processSample($hwdata, $isattack);
    }
}

class Skinner497Demo extends Skinner497 {
    
    const SOURCE="OrganDefinitions/San Francisco, Skinner op. 497, Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "San Francisco, Skinner op. 497 (Demo - %s) 1.3";
    
    /**
     * Run the import
     */
    public static function Skinner497(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new Skinner497Demo(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace(", Demo", " ($target)", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getManual(4)->NumberOfLogicalKeys=73;
            $hwi->save(sprintf(self::TARGET, $target));
        }
        else { /*
            self::Skinner497(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Skinner497(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Skinner497(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear"); */
            self::Skinner497(
                    [
                        self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "Surround");
        }
    }
}

class Skinner497Full extends Skinner497 {
    
    const SOURCE="OrganDefinitions/San Francisco, Skinner op. 497.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "San Francisco, Skinner op. 497 (%s) 1.3";
    
    /**
     * Run the import
     */
    public static function Skinner497(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new Skinner497Full(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getManual(4)->NumberOfLogicalKeys=73;
            $hwi->save(sprintf(self::TARGET, $target));
        }
        else {
            self::Skinner497(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Skinner497(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Skinner497(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::Skinner497(
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

Skinner497Full::Skinner497();
Skinner497Demo::Skinner497();