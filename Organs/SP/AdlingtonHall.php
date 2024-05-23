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
 * Import Sonus Paradisi AdlingtonHall, Janke Organ to GrandOrgue
 * 
 * @author andrewZ`
 */
class AdlingtonHall extends SPOrganV2 {
    const ROOT="/GrandOrgue/Organs/SP/Adlington_Hall/";
    const SOURCE="OrganDefinitions/Adlington Hall, Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Adlington Hall %s Demo 1.1.organ";
    const REVISIONS="\n"
            . "1.1 Cross fades corrected for GO 3.14\n"
            . "\n";
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,
        1=>self::RANKS_DIFFUSE,
        2=>self::RANKS_REAR,
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ 
            // Set is for background - look for backgrounds/*.bmp
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>[
                0=>["Group"=>"Simple", "Instance"=>11000, "Name"=>"Landscape", "SetID"=>1035],
                1=>[],
                2=>["Group"=>"Simple", "Instance"=>11000, "Name"=>"Portrait", "SetID"=>1037],
               ],
            3=>[
                0=>["Group"=>"Left", "Instance"=>12000, "Name"=>"Landscape", "SetID"=>1031],
                1=>[],
                2=>["Group"=>"Left", "Instance"=>12000, "Name"=>"Portrait", "SetID"=>1033],
               ],
            4=>[
                0=>["Group"=>"Right", "Instance"=>12000, "Name"=>"Landscape", "SetID"=>1032],
                1=>[],
                2=>["Group"=>"Right", "Instance"=>12000, "Name"=>"Portrait", "SetID"=>1034],
               ],
            5=>[
                0=>["Name"=>"Mixer", "SetID"=>1036],
               ],
            6=>[
                0=>["Name"=>"Stops", "Instance"=>13000, "SetID"=>1038],
               ],
    ];

    protected $patchDivisions=[
        3=>["DivisionID"=>3, "Name"=>"Great"],
        7=>["DivisionID"=>7, "Name"=>"Stops"],
        8=>["DivisionID"=>8, "Name"=>"Ambience"],
        9=>["DivisionID"=>9, "Name"=>"Tracker"]
    ];

    protected $patchEnclosures=[
        901=>["Panels"=>[5=>[1600]], "EnclosureID"=>901, "Name"=>"Direct",
            "GroupIDs"=>[101,201,301,701,801,901], "AmpMinimumLevel"=>1],
        903=>["Panels"=>[5=>[1610]], "EnclosureID"=>904,"Name"=>"Diffuse",
            "GroupIDs"=>[102,202,302,703,803,903], "AmpMinimumLevel"=>1],
        904=>["Panels"=>[5=>[1620]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[103,203,303,704,804,904], "AmpMinimumLevel"=>1],
        907=>["Panels"=>[5=>[1690]], "EnclosureID"=>907,"Name"=>"Stops",
            "GroupIDs"=>[701,703,704], "AmpMinimumLevel"=>1],
        908=>["Panels"=>[5=>[1691]], "EnclosureID"=>908,"Name"=>"Ambience",
            "GroupIDs"=>[801,803,804], "AmpMinimumLevel"=>1],
        909=>["Panels"=>[5=>[1692]], "EnclosureID"=>909,"Name"=>"Tracker",
            "GroupIDs"=>[901,903,904], "AmpMinimumLevel"=>1],
    ];

    protected $patchStops=[
        -101=>["StopID"=>-101, "DivisionID"=>1, "Name"=>"Blower (Direct)",    "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>801],
        -102=>["StopID"=>-102, "DivisionID"=>1, "Name"=>"Blower (Diffuse)",   "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>803],
        -103=>["StopID"=>-103, "DivisionID"=>1, "Name"=>"Blower (Rear)",      "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>804],

        -121=>["StopID"=>-121, "DivisionID"=>2, "Name"=>"GT Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>3, "Name"=>"CH Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -131=>["StopID"=>-131, "DivisionID"=>2, "Name"=>"GT Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -132=>["StopID"=>-132, "DivisionID"=>3, "Name"=>"CH Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[
        999800=>["Noise"=>"StopOn",  "GroupID"=>701, "StopIDs"=>[]], // Front A (direct): Stop noise Off
        999810=>["Noise"=>"StopOn",  "GroupID"=>703, "StopIDs"=>[]], // Front C (distant): Stop noise Off
        999820=>["Noise"=>"StopOn",  "GroupID"=>704, "StopIDs"=>[]], // Rear: Stop noise Off
        999900=>["Noise"=>"StopOff", "GroupID"=>701, "StopIDs"=>[]], // Front A (direct): Stop noise On
        999910=>["Noise"=>"StopOff", "GroupID"=>703, "StopIDs"=>[]], // Front C (distant): Stop noise On
        999920=>["Noise"=>"StopOff", "GroupID"=>704, "StopIDs"=>[]], // Rear: Stop noise On

        999901=>"DELETE", // Direct blower noise
        999911=>"DELETE", // Front blower noise
        999921=>"DELETE", // Rear blower noise
        
        998102=>["Noise"=>"KeyOn",  "GroupID"=>901, "StopIDs"=>[-121]], // (direct): Keyboard noise On 1. man.
        998112=>["Noise"=>"KeyOn",  "GroupID"=>903, "StopIDs"=>[-121]], // (diffuse): Keyboard noise On 1. man.
        998122=>["Noise"=>"KeyOn",  "GroupID"=>904, "StopIDs"=>[-121]], // (rear): Keyboard noise On 1. man.
        998152=>["Noise"=>"KeyOff", "GroupID"=>901, "StopIDs"=>[-131]], // (direct): Keyboard noise Off 1. man.
        998162=>["Noise"=>"KeyOff", "GroupID"=>903, "StopIDs"=>[-131]], // (diffuse): Keyboard noise Off 1. man.
        998172=>["Noise"=>"KeyOff", "GroupID"=>904, "StopIDs"=>[-131]], // (rear): Keyboard noise Off 1. man.

        998202=>["Noise"=>"KeyOn",  "GroupID"=>901, "StopIDs"=>[-122]], // (direct): Keyboard noise On 2. man.
        998212=>["Noise"=>"KeyOn",  "GroupID"=>903, "StopIDs"=>[-122]], // (diffuse): Keyboard noise On 2. man.
        998222=>["Noise"=>"KeyOn",  "GroupID"=>904, "StopIDs"=>[-122]], // (rear): Keyboard noise On 2. man.
        998252=>["Noise"=>"KeyOff", "GroupID"=>901, "StopIDs"=>[-132]], // (direct): Keyboard noise Off 2. man.
        998262=>["Noise"=>"KeyOff", "GroupID"=>903, "StopIDs"=>[-132]], // (diffuse): Keyboard noise Off 2. man.
        998272=>["Noise"=>"KeyOff", "GroupID"=>904, "StopIDs"=>[-132]], // (rear): Keyboard noise Off 2. man.

        999901=>["Noise"=>"Ambient", "GroupID"=>801, "StopIDs"=>[-101]], // (direct): Blower noise
        999911=>["Noise"=>"Ambient", "GroupID"=>803, "StopIDs"=>[-102]], // (diffuse): Blower noise
        999921=>["Noise"=>"Ambient", "GroupID"=>804, "StopIDs"=>[-103]], // (rear): Blower noise
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
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 6:
                    echo ($instanceID=$instance["ImageSetInstanceID"]), " ",
                         $instance["Name"], "\n";
                    foreach ($this->hwdata->switches() as $switch) {
                        if (isset($switch["Disp_ImageSetInstanceID"]) && 
                                $switch["Disp_ImageSetInstanceID"]==$instance)
                            echo $switch["SwitchID"], " ",
                                 $switch["Name"], "\n";
                    }
            }
        } 
        exit(); */
        $hwdata["Identification_UniqueOrganID"]=2707;
        return parent::createOrgan($hwdata);
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        switch ($hwdata["RankID"]) { // DELETE not working !!!
            case 999800: // Direct stop noise off
            case 999810: // Front stop noise off
            case 999820: // Rear stop noise off
            case 999900: // Direct stop noise on
            case 999910: // Front stop noise on
            case 999920: // Rear stop noise on
            case 999901: // Direct blower noise
            case 999911: // Front blower noise
            case 999921: // Rear blower noise
                return NULL;
        }
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

    protected function sampleTuning(array $hwdata) : ?float {
        return NULL;
    }
    
    /**
     * Run the import
     */
    public static function AdlingtonHall(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=53;
        \GOClasses\Manual::$pedals=26;
        if (sizeof($positions)>0) {
            $hwi=new AdlingtonHall(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("Surround", "Hall ($target)", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getStops() as $id=>$stop) {
                foreach(["001","002","003"] as $rank) {
                    $stop->unset("Rank${rank}FirstAccessibleKeyNumber");
                }
                if ($id==10) { // 2' flute treble
                    for ($n=1; $n<=$stop->NumberOfRanks; $n++) {
                        $kn=sprintf("Rank%03dFirstAccessibleKeyNumber", $n);
                        $stop->set($kn, 26);
                    }
                }
            }
            /* Use master couplers?
            $coupler=new \GOClasses\Coupler("GT/P");
            $coupler->DestinationManual="002";
            $coupler->FirstMIDINoteNumber=35;
            $coupler->NumberOfKeys=26;
            $coupler->DefaultToEngaged="Y";
            $coupler->GCState=1; 
            $hwi->getManual(1)->Coupler($coupler); */
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            /* self::AdlingtonHall(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::AdlingtonHall(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::AdlingtonHall(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear"); */
            self::AdlingtonHall(
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
AdlingtonHall::AdlingtonHall();