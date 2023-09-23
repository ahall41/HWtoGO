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
 * Import Sonus Paradisi Doesburg, Martinikerk Walcker Organ to GrandOrgue
 * 
 * @author andrew
 */
class Doesburg extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/Doesburg/";
    const SOURCE="OrganDefinitions/Doesburg, St. Martini, Walcker, DEMO.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Doesburg, St. Martini, Walcker, DEMO (%s) 1.1.organ";
    
    private static string $Comments="/nVersion 1.1 Ordered releases\n";
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        1=>self::RANKS_DIRECT,   
        0=>self::RANKS_DIFFUSE,  9=>self::RANKS_DIFFUSE,
        4=>self::RANKS_REAR,     8=>self::RANKS_REAR
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>"DELETE",
            3=>"DELETE", 
            4=>"DELETE", 
            5=>[
                0=>["Name"=>"Stops", "Instance"=>13000, "SetID"=>1035],
               ],
            6=>[
                0=>["Name"=>"Mixer", "SetID"=>1038],
               ],
            7=>"DELETE",
            8=>[
                0=>["Name"=>"Simple", "Instance"=>11000, "SetID"=>1040],
               ],
            9=>"DELETE", 
           10=>"DELETE",
           11=>"DELETE",
           12=>"DELETE",
    ];

    protected $patchDivisions=[
            4=>"DELETE",
            5=>"DELETE",
            8=>["DivisionID"=>8, "Name"=>"Blower", "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    protected $patchTremulants=[
         31=>["Type"=>"Switched", "DivisionID"=>3], // II - but not Dry !!!
        120=>"DELETE", // III
         45=>"DELETE", // IV
    ];

    protected $patchEnclosures=[
        996=>"DELETE", // IV
        997=>"DELETE", // III
        998=>["GroupIDs"=>[301,303,304], "AmpMinimumLevel"=>40], // II
        
        901=>["Panels"=>[6=>[800]], "EnclosureID"=>901, "Name"=>"Direct Mixer",
            "GroupIDs"=>[101,201,301], "AmpMinimumLevel"=>0],
        903=>["Panels"=>[6=>[810]], "EnclosureID"=>903,"Name"=>"Diffuse Mixer",
            "GroupIDs"=>[103,203,303], "AmpMinimumLevel"=>0],
        904=>["Panels"=>[6=>[820]], "EnclosureID"=>903,"Name"=>"Rear Mixer",
            "GroupIDs"=>[104,204,304], "AmpMinimumLevel"=>0],
        908=>["Panels"=>[6=>[1595]], "EnclosureID"=>908,"Name"=>"Blower Mixer",
            "GroupIDs"=>[800], "AmpMinimumLevel"=>0],
        909=>["Panels"=>[6=>[1599]], "EnclosureID"=>909,"Name"=>"Tracker Mixer",
            "GroupIDs"=>[900], "AmpMinimumLevel"=>0],
    ];

    protected $patchStops=[
         124=>["StopID"=> 124, "DivisionID"=>1, "Name"=>"Blower",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>800],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"Pedaal Key On",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -112=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"I Key On",         "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -113=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"II key On",        "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"Pedaal Key Off",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"I Key Off",        "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"II key Off",       "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[
          8=>["Noise"=>"StopOn",  "GroupID"=>900, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>900, "StopIDs"=>[]],
        160=>["GroupID"=>300+self::RANKS_DIFFUSE],
        161=>["GroupID"=>300+self::RANKS_DIRECT],
        164=>["GroupID"=>300+self::RANKS_REAR],
        168=>["GroupID"=>300+self::RANKS_REAR],
        169=>["GroupID"=>300+self::RANKS_DIFFUSE],
        950=>["Noise"=>"Ambient", "GroupID"=>800, "StopIDs"=>[124]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-111]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-112]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-113]],
        984=>"DELETE",
        985=>"DELETE",
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-121]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-122]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-123]],
        994=>"DELETE",
        995=>"DELETE",
    ];

    public function import(): void {
        parent::import();
        foreach ($this->getStops() as $stop) {
            for ($rn=1; $rn<10; $rn++) {
                $r=sprintf("Rank%03dPipeCount", $rn);
                if ($stop->isset($r) && $stop->get($r)>61)
                    $stop->set($r, 61);
            }
        }
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=1307; 
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
                                $panel=$this->getPanel(($instance["DisplayPageID"]*10)+$layoutid, FALSE);
                                $instanceid=$instance["ImageSetInstanceID"];
                                if ($panel!==NULL 
                                        && !($instanceid==968 && $layoutid==1)) {
                                    $pe=$panel->GUIElement($enclosure);
                                    $this->configureEnclosureImage($pe, ["InstanceID"=>$instanceid], $layoutid);
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
    
    protected function samplePitchMidi(array $hwdata) : ?float {
        // The pipes start at 024C but are actually 036C !
        return parent::samplePitchMidi($hwdata)+12;
    }

    protected function configureAttack(array $hwdata, \GOClasses\Pipe $pipe) : void {
        if (strpos($hwdata["SampleFilename"], "_bis")===FALSE 
                && strpos($hwdata["SampleFilename"], "_ter")===FALSE )
            parent::configureAttack($hwdata, $pipe);
    }

    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        unset($hwdata["ReleaseCrossfadeLengthMs"]); 
        //$hwdata["ReleaseCrossfadeLengthMs"]=30;
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the import
     */
    public static function Doesburg(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new Doesburg(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace(" Demo", " ($target)", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::$Comments);
        }
        else {
            self::Doesburg(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Doesburg(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Doesburg(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::Doesburg(
                    [
                        self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "6ch");
        }
    }
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\SP\ErrorHandler");
Doesburg::Doesburg();