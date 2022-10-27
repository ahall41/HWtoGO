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
 * Import Sonus Paradisi Polná, organ by J. D. Sieber (1708) Demo to GrandOrgue
 * 
 * @author andrewZ`
 */
class Polna extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/Polna/";
    const SOURCE="OrganDefinitions/Polna, Sieber Organ, Surround Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Polna, Sieber Organ, Surround %s Demo 1.0.organ";
    const REVISIONS="";
    
    const RANKS_ORGANIST=1;
    const RANKS_DIFFUSE=2;
    const RANKS_DISTANT=3;
    const RANKS_REAR=4;
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_ORGANIST,    9=>self::RANKS_ORGANIST,
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
                0=>["SetID"=>1036] // Mixer
               ],
            6=>[
                0=>["SetID"=>1038, "Instance"=>13000] // Stops
               ],
    ];

    protected $patchDivisions=[
            8=>["DivisionID"=>8, "Name"=>"Blower", "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    protected $patchEnclosures=[
        901=>["Panels"=>[5=>[1600]], "EnclosureID"=>901, "Name"=>"Organist",
            "GroupIDs"=>[101,201,301], "AmpMinimumLevel"=>0],
        902=>["Panels"=>[5=>[1610]], "EnclosureID"=>902, "Name"=>"Direct",
            "GroupIDs"=>[102,202,302], "AmpMinimumLevel"=>0],
        903=>["Panels"=>[5=>[1620]], "EnclosureID"=>903,"Name"=>"Diffuse",
            "GroupIDs"=>[103,203,303], "AmpMinimumLevel"=>0],
        904=>["Panels"=>[5=>[1630]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304], "AmpMinimumLevel"=>0],
        /* 908=>["Panels"=>[5=>[1691]], "EnclosureID"=>908,"Name"=>"Blower",
            "GroupIDs"=>[800], "AmpMinimumLevel"=>0],
        909=>["Panels"=>[5=>[1692]], "EnclosureID"=>909,"Name"=>"Tracker",
            "GroupIDs"=>[900], "AmpMinimumLevel"=>0], */
    ];

    protected $patchKeyActions=[
        1=>"DELETE",
        2=>["MIDINoteNumOfFirstSourceKey"=>36, "NumberOfKeys"=>30],
        3=>"DELETE",
        4=>"DELETE",
    ];
    
    /*
    protected $patchStops=[
         124=>["StopID"=> 124, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>124,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>800],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"P Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -112=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"HW Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -113=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"OW key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -114=>["StopID"=>-114, "DivisionID"=>4, "Name"=>"BW key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"P Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"HW Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"OW key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -124=>["StopID"=>-124, "DivisionID"=>4, "Name"=>"BW key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ]; */
    
    protected $patchRanks=[
        999800=>"DELETE", // Front A (organist): Stop noise Off
        999810=>"DELETE", // Front B (direct): Stop noise Off
        999820=>"DELETE", // Front C (diffuse): Stop noise Off
        999840=>"DELETE", // Rear: Stop noise Off
        999900=>"DELETE", // Front A (organist): Stop noise On
        999901=>["Noise"=>"",  "GroupID"=>900, "StopIDs"=>[]], // Front A (organist): Blower noise
        999910=>"DELETE", // Front B (direct): Stop noise On
        999911=>["Noise"=>"",  "GroupID"=>900, "StopIDs"=>[]], // Front B (direct): Blower noise
        999920=>"DELETE", // Front C (diffuse): Stop noise On
        999921=>["Noise"=>"",  "GroupID"=>900, "StopIDs"=>[]], // Front C (diffuse): Blower noise
        999940=>"DELETE", // Rear: Stop noise On
        999941=>["Noise"=>"",  "GroupID"=>900, "StopIDs"=>[]], // Rear: Blower noise
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

    public function createRanks(array $ranksdata): void {
        ksort($ranksdata);
        parent::createRanks($ranksdata);
        exit();
    }

    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if ($hwdata["RankID"]>990000) {
            echo $hwdata["RankID"], '=>["Noise"=>"",  "GroupID"=>900, "StopIDs"=>[]], // ', $hwdata["Name"],"\n";
            return NULL;
        }
        else
            return parent::createRank($hwdata, $keynoise);
    }
    
    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        // The one tremulant switch needs to act across all divisions
        $this->patchTremulants[26]["DivisionID"]=$hwdata["DivisionID"];
        return parent::createStop($hwdata);
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2702;
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
    
    protected function sampleTuning(array $hwdata) : ?float {
        return NULL;
    }
    
    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        unset($hwdata["ReleaseCrossfadeLengthMs"]);
        return parent::processSample($hwdata, $isattack);
    }
    
    /**
     * Run the import
     */
    public static function Polna(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=51;
        if (sizeof($positions)>0) {
            $hwi=new Polna(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("Surround", "$target", $hwi->getOrgan()->ChurchName);
            foreach($hwi->getStops() as $stop) {
                foreach (["001","002","003","004"] as $r) {
                    $pc="Rank{$r}PipeCount";
                    unset($stop->$pc);
                }
            }
            $pedals=$hwi->getManual(1);
            $pedals->PositionX=$pedals->Key004Width;
            $pedals->PositionY=$hwi->getPanel(10)->DispScreenSizeVert-272;
            $pedals->Key004Width=0;
            $pedals->Key022Width=0;
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            self::Polna(
                    [self::RANKS_ORGANIST=>"Organist"],
                    "Organist");
            self::Polna(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Polna(
                    [self::RANKS_DISTANT=>"Distant"],
                     "Distant");
            /* self::Polna(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear"); */
            self::Polna(
                    [
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_DISTANT=>"Distant", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "Surround");
        }
    }
}
Polna::Polna();