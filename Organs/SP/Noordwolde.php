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
 * Import Sonus Paradisi Noordwolde, Janke Organ to GrandOrgue
 * 
 * @todo Blower, Tracker and Stop noises
 * 
 * @author andrewZ`
 */
class Noordwolde extends SPOrganV2 {
    const ROOT="/GrandOrgue/Organs/SP/Noordwolde/";
    const SOURCE="OrganDefinitions/Noordwolde, Huis-Freytag-Lohman, Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Noordwolde, Huis-Freytag-Lohma %s Demo 1.0.organ";
    const REVISIONS="";
    
    const RANKS_SEMI_DRY=1;
    const RANKS_DIFFUSE=2;
    const RANKS_DISTANT=3;
    const RANKS_REAR=4;
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_SEMI_DRY,    9=>self::RANKS_SEMI_DRY,
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

    // We now need sound effects at each listening position
    protected $patchDivisions=[
            6=>["DivisionID"=>6, "Name"=>"Tremulant"],
            7=>["DivisionID"=>7, "Name"=>"Stops"],
            8=>["DivisionID"=>8, "Name"=>"Ambience"],
            9=>["DivisionID"=>9, "Name"=>"Tracker"]
    ];

    protected $patchTremulants=[
        23=>["Type"=>"Switched", "DivisionID"=>3], 
    ];

    protected $patchEnclosures=[
        // NB There is a bug in GO when AmpMinimumLevel is set to 0
        901=>["Panels"=>[5=>[1600]], "EnclosureID"=>901, "Name"=>"Semi-Dry",
            "GroupIDs"=>[101,201,301,601,701,801,901], "AmpMinimumLevel"=>1],
        902=>["Panels"=>[5=>[1610]], "EnclosureID"=>902, "Name"=>"Diffuse",
            "GroupIDs"=>[102,202,302,602,702,802,902], "AmpMinimumLevel"=>1],
        903=>["Panels"=>[5=>[1620]], "EnclosureID"=>903,"Name"=>"Distant",
            "GroupIDs"=>[103,203,303,603,703,803,903], "AmpMinimumLevel"=>1],
        904=>["Panels"=>[5=>[1630]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304,604,704,904,904], "AmpMinimumLevel"=>1],
        906=>["Panels"=>[5=>[1694]], "EnclosureID"=>906,"Name"=>"Tremulant",
            "GroupIDs"=>[601,602,603,604], "AmpMinimumLevel"=>1],
        907=>["Panels"=>[5=>[1690]], "EnclosureID"=>907,"Name"=>"Stops",
            "GroupIDs"=>[701,702,703,704], "AmpMinimumLevel"=>1],
        908=>["Panels"=>[5=>[1691]], "EnclosureID"=>908,"Name"=>"Ambience",
            "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>1],
        909=>["Panels"=>[5=>[1692]], "EnclosureID"=>909,"Name"=>"Tracker",
            "GroupIDs"=>[901,902,903,904], "AmpMinimumLevel"=>1],
    ];

    protected $patchStops=[
        -101=>["StopID"=>-101, "DivisionID"=>1, "Name"=>"Blower (Semi Dry)",    "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>801],
        -102=>["StopID"=>-102, "DivisionID"=>1, "Name"=>"Blower (Diffuse)",     "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>802],
        -103=>["StopID"=>-103, "DivisionID"=>1, "Name"=>"Blower (Distant)",     "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>803],
        -104=>["StopID"=>-104, "DivisionID"=>1, "Name"=>"Blower (Rear)",        "ControllingSwitchID"=>1050,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>804],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"Tremulant (Semi Dry)", "ControllingSwitchID"=>23, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>601],
        -112=>["StopID"=>-112, "DivisionID"=>1, "Name"=>"Tremulant (Diffuse)",  "ControllingSwitchID"=>23, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>602],
        -113=>["StopID"=>-113, "DivisionID"=>1, "Name"=>"Tremulant (Distant)",  "ControllingSwitchID"=>23, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>603],
        -114=>["StopID"=>-114, "DivisionID"=>1, "Name"=>"Tremulant (Rear)",     "ControllingSwitchID"=>23, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>604],

        -121=>["StopID"=>-121, "DivisionID"=>2, "Name"=>"HW Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>3, "Name"=>"OW Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-123, "DivisionID"=>1, "Name"=>"P Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -131=>["StopID"=>-131, "DivisionID"=>2, "Name"=>"HW Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -132=>["StopID"=>-132, "DivisionID"=>3, "Name"=>"OW Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -133=>["StopID"=>-133, "DivisionID"=>1, "Name"=>"P Key Off",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[ 
        998102=>["Noise"=>"KeyOn",  "GroupID"=>901, "StopIDs"=>[-121]], // Front A (semi-dry): Keyboard noise On 1. man.
        998112=>["Noise"=>"KeyOn",  "GroupID"=>902, "StopIDs"=>[-121]], // Front B (diffuse): Keyboard noise On 1. man.
        998122=>["Noise"=>"KeyOn",  "GroupID"=>903, "StopIDs"=>[-121]], // Front C (distant): Keyboard noise On 1. man.
        998142=>["Noise"=>"KeyOn",  "GroupID"=>904, "StopIDs"=>[-121]], // Rear: Keyboard noise On 1. man.
        998152=>["Noise"=>"KeyOff", "GroupID"=>901, "StopIDs"=>[-131]], // Front A (semi-dry): Keyboard noise Off 1. man.
        998162=>["Noise"=>"KeyOff", "GroupID"=>902, "StopIDs"=>[-131]], // Front B (diffuse): Keyboard noise Off 1. man.
        998172=>["Noise"=>"KeyOff", "GroupID"=>903, "StopIDs"=>[-131]], // Front C (distant): Keyboard noise Off 1. man.
        998192=>["Noise"=>"KeyOff", "GroupID"=>904, "StopIDs"=>[-131]], // Rear: Keyboard noise Off 1. man.
        998202=>["Noise"=>"KeyOn",  "GroupID"=>901, "StopIDs"=>[-122]], // Front A (semi-dry): Keyboard noise On 2. man.
        998212=>["Noise"=>"KeyOn",  "GroupID"=>902, "StopIDs"=>[-122]], // Front B (diffuse): Keyboard noise On 2. man.
        998222=>["Noise"=>"KeyOn",  "GroupID"=>903, "StopIDs"=>[-122]], // Front C (distant): Keyboard noise On 2. man.
        998242=>["Noise"=>"KeyOn",  "GroupID"=>904, "StopIDs"=>[-122]], // Rear: Keyboard noise On 2. man.
        998252=>["Noise"=>"KeyOff", "GroupID"=>901, "StopIDs"=>[-132]], // Front A (semi-dry): Keyboard noise Off 2. man.
        998262=>["Noise"=>"KeyOff", "GroupID"=>902, "StopIDs"=>[-132]], // Front B (diffuse): Keyboard noise Off 2. man.
        998272=>["Noise"=>"KeyOff", "GroupID"=>903, "StopIDs"=>[-132]], // Front C (distant): Keyboard noise Off 2. man.
        998292=>["Noise"=>"KeyOff", "GroupID"=>904, "StopIDs"=>[-132]], // Rear: Keyboard noise Off 2. man.
        998602=>["Noise"=>"KeyOn",  "GroupID"=>901, "StopIDs"=>[-123]], // Front A (semi-dry): Keyboard noise On Pedal
        998612=>["Noise"=>"KeyOn",  "GroupID"=>902, "StopIDs"=>[-123]], // Front B (diffuse): Keyboard noise On Pedal
        998622=>["Noise"=>"KeyOn",  "GroupID"=>903, "StopIDs"=>[-123]], // Front C (distant): Keyboard noise On Pedal
        998642=>["Noise"=>"KeyOn",  "GroupID"=>904, "StopIDs"=>[-123]], // Rear: Keyboard noise On Pedal
        998652=>["Noise"=>"KeyOff", "GroupID"=>901, "StopIDs"=>[-133]], // Front A (semi-dry): Keyboard noise Off Pedal
        998662=>["Noise"=>"KeyOff", "GroupID"=>902, "StopIDs"=>[-133]], // Front B (diffuse): Keyboard noise Off Pedal
        998672=>["Noise"=>"KeyOff", "GroupID"=>903, "StopIDs"=>[-133]], // Front C (distant): Keyboard noise Off Pedal
        998692=>["Noise"=>"KeyOff", "GroupID"=>904, "StopIDs"=>[-133]], // Rear: Keyboard noise Off Pedal
        999800=>["Noise"=>"StopOff",  "GroupID"=>701, "StopIDs"=>[]], // Front A (semi-dry): Stop noise Off
        999803=>"DELETE", // Front A (semi-dry): Schel noise Off
        999810=>["Noise"=>"StopOff",  "GroupID"=>702, "StopIDs"=>[]], // Front B (diffuse): Stop noise Off
        999813=>"DELETE", // Front B (diffuse): Schel noise Off
        999820=>["Noise"=>"StopOff",  "GroupID"=>703, "StopIDs"=>[]], // Front C (distant): Stop noise Off
        999823=>"DELETE", // Front C (distant): Schel noise Off
        999840=>["Noise"=>"StopOff",  "GroupID"=>704, "StopIDs"=>[]], // Rear: Stop noise Off
        999843=>"DELETE", // Rear: Schel noise Off
        999900=>["Noise"=>"StopOn",  "GroupID"=>701, "StopIDs"=>[]], // Front A (semi-dry): Stop noise On
        999901=>["Noise"=>"Ambient",  "GroupID"=>801, "StopIDs"=>[-101]], // Front A (semi-dry): Blower noise
        999903=>"DELETE", // Front A (semi-dry): Schel noise On
        999904=>["Noise"=>"Ambient",  "GroupID"=>801, "StopIDs"=>[-111]], // Front A (semi-dry): Tremulant noise
        999910=>["Noise"=>"StopOn",  "GroupID"=>702, "StopIDs"=>[]], // Front B (diffuse): Stop noise On
        999911=>["Noise"=>"Ambient",  "GroupID"=>802, "StopIDs"=>[-102]], // Front B (diffuse): Blower noise
        999913=>"DELETE", // Front B (diffuse): Schel noise On
        999914=>["Noise"=>"Ambient",  "GroupID"=>802, "StopIDs"=>[-112]], // Front B (diffuse): Tremulant noise
        999920=>["Noise"=>"StopOn",  "GroupID"=>703, "StopIDs"=>[]], // Front C (distant): Stop noise On
        999921=>["Noise"=>"Ambient",  "GroupID"=>803, "StopIDs"=>[-103]], // Front C (distant): Blower noise
        999923=>"DELETE", // Front C (distant): Schel noise On
        999924=>["Noise"=>"Ambient",  "GroupID"=>803, "StopIDs"=>[-113]], // Front C (distant): Tremulant noise
        999940=>["Noise"=>"StopOn",  "GroupID"=>704, "StopIDs"=>[]], // Rear: Stop noise On
        999941=>["Noise"=>"Ambient",  "GroupID"=>804, "StopIDs"=>[-104]], // Rear: Blower noise
        999943=>"DELETE", // Rear: Schel noise On
        999944=>["Noise"=>"Ambient",  "GroupID"=>804, "StopIDs"=>[-114]], // Rear: Tremulant noise */
    ];

    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        // The one tremulant switch needs to act across all divisions
        $this->patchTremulants[23]["DivisionID"]=$hwdata["DivisionID"];
        $stop=parent::createStop($hwdata);
        return $stop;
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2308;
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

    protected function sampleTuning(array $hwdata) : ?float {
        return NULL;
    }
    
    protected function sampleMidiKey(array $hwdata) : int {
        return \Import\Configure::sampleMidiKey($hwdata);
    }
    
    /**
     * Run the import
     */
    public static function Noordwolde(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
        if (sizeof($positions)>0) {
            $hwi=new Noordwolde(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("Surround", "$target", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            unset($hwi->getSwitch(23)->GCState);
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            /* self::Noordwolde(
                    [self::RANKS_SEMI_DRY=>"Semi-Dry"],
                    "Semi-Dry");
            self::Noordwolde(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Noordwolde(
                    [self::RANKS_DISTANT=>"Distant"],
                     "Distant");
            self::Noordwolde(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear"); */
            self::Noordwolde(
                    [
                        self::RANKS_SEMI_DRY=>"Semi-Dry", 
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_DISTANT=>"Distant", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "Surround");
        }
    }
}
Noordwolde::Noordwolde();