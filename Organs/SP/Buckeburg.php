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
 * Import Sonus Paradisi Buckeburg, Janke Organ to GrandOrgue
 * 
 * @author andrewZ`
 */
class Buckeburg extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/Buckeburg/";
    const SOURCE="OrganDefinitions/Buckeburg, Janke Organ, Surround Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Buckeburg, Janke Organ, %s Demo 1.3.organ";
    const REVISIONS="\n"
            . "1.1 Remove additional attacks\n"
            . "1.2 Reinstate additional attacks\n"
            . "1.3 Add coupler manuals + correct cross fades\n"
            . "1.4 Remove coupler manuals + cross fades corrected for GO 3.14\n"
            . "\n";
    
    public static bool $singleRelease=FALSE;
    
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
                0=>["Group"=>"Simple", "Name"=>"Landscape", "Instance"=>11000, "SetID"=>1035],
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
                0=>["Name"=>"Mixer", "SetID"=>1036],
               ],
            6=>[
                0=>["Name"=>"Stops", "Instance"=>13000, "SetID"=>1038],
               ],
    ];

    protected $patchDivisions=[
            8=>["DivisionID"=>8, "Name"=>"Blower", "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];
            
    protected $patchTremulants=[ // Needs to be switched until we can load all releases?
        19=>["Type"=>"Wave", "DivisionID"=>2, "GroupIDs"=>[201,202,203]], // HW
        49=>["Type"=>"Wave", "DivisionID"=>4, "GroupIDs"=>[401,402,403]], // BW
        50=>["Type"=>"Wave", "DivisionID"=>3, "GroupIDs"=>[301,302,303]], // OW
    ];

    protected $patchEnclosures=[
        901=>["Panels"=>[5=>[1600]], "EnclosureID"=>901, "Name"=>"Direct",
            "GroupIDs"=>[101,201,301,401], "AmpMinimumLevel"=>0],
        903=>["Panels"=>[5=>[1610]], "EnclosureID"=>903,"Name"=>"Diffuse",
            "GroupIDs"=>[103,203,303,403], "AmpMinimumLevel"=>0],
        904=>["Panels"=>[5=>[1620]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304,404], "AmpMinimumLevel"=>0],
        908=>["Panels"=>[5=>[1595]], "EnclosureID"=>908,"Name"=>"Blower",
            "GroupIDs"=>[800], "AmpMinimumLevel"=>0],
        909=>["Panels"=>[5=>[1599]], "EnclosureID"=>909,"Name"=>"Tracker",
            "GroupIDs"=>[900], "AmpMinimumLevel"=>0],
    ];

    protected $patchStops=[
         124=>["StopID"=> 124, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>124,  "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N", "Ambient"=>TRUE, "GroupID"=>800],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"P Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -112=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"HW Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -113=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"OW key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -114=>["StopID"=>-114, "DivisionID"=>4, "Name"=>"BW key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"P Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"HW Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"OW key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -124=>["StopID"=>-124, "DivisionID"=>4, "Name"=>"BW key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
    ];
    
    protected $patchRanks=[
          8=>["Noise"=>"StopOn",  "GroupID"=>900, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>900, "StopIDs"=>[]],
        950=>["Noise"=>"Ambient", "GroupID"=>800, "StopIDs"=>[124]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-111]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-112]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-113]],
        984=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-114]],
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-121]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-122]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-123]],
        994=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-124]],
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
        $hwdata["Identification_UniqueOrganID"]=2306;
        return parent::createOrgan($hwdata);
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (($hwdata["RankID"] % 10)<5)
            {return parent::createRank($hwdata, $keynoise);}
        else
            {return NULL;}
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
    
    protected function switchedTremulant(array $stopdata) : bool {
        return FALSE; // All stops are wave based
    }
            
    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        /* Single Release */
        if (self::$singleRelease &&
            isset($hwdata["ReleaseSelCriteria_LatestKeyReleaseTimeMs"]) &&
            !empty($hwdata["ReleaseSelCriteria_LatestKeyReleaseTimeMs"])) {
            return NULL;
        }
        $hwdata["IsTremulant"]=0;
        switch ($hwdata["RankID"] % 10) {
            case 9: // Direct
                $hwdata["RankID"]-=9; // -> 0
                $hwdata["IsTremulant"]=1;
                break;
            case 8: // Rear
                $hwdata["RankID"]-=4; // > 4
                $hwdata["IsTremulant"]=1;
                break;
            case 7: // Diffuse
                $hwdata["RankID"]-=6; // -> 1
                $hwdata["IsTremulant"]=1;
                break;
            case 6: // NA
                $hwdata["RankID"]-=4;
                $hwdata["IsTremulant"]=1;
                break;
        }
        return parent::processSample($hwdata, $isattack);
    }
    
    /**
     * Run the import
     */
    public static function Buckeburg(array $positions=[], string $target="", float $balance=0.0) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new Buckeburg(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->balance=$balance;
            $hwi->import();
            // $hwi->addVirtualKeyboards(3, [1,2,3], [1,2,3]);
            $hwi->getOrgan()->ChurchName=str_replace("Surround", "$target", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getManual(4)->NumberOfLogicalKeys=73;
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            self::$singleRelease=FALSE;
            self::Buckeburg(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Buckeburg(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Buckeburg(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::Buckeburg(
                    [
                        self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "Surround");
            self::$singleRelease=TRUE;
            self::Buckeburg(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "SR Diffuse");
        }
    }
}
Buckeburg::Buckeburg();