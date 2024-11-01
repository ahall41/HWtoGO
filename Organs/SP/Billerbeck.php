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
 * Import Sonus Paradisi Billerbeck Dom Demo to GrandOrgue
 * The mixer panel could not be completed as the HW ODF references
 * to the corresponding images appear to be missing
 *  
 * @author andrew
 */
class Billerbeck extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/Billerbeck/";
    const SOURCE="OrganDefinitions/Billerbeck, Fleiter Surr.Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Billerbeck, Fleiter Surr (Demo - %s) 0.4.organ";
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_SEMI_DRY, 9=>self::RANKS_SEMI_DRY,
        1=>self::RANKS_DIRECT,   7=>self::RANKS_DIRECT,
        2=>self::RANKS_DIFFUSE,  6=>self::RANKS_DIFFUSE,
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
                2=>["Group"=>"Simple", "Name"=>"Portrait", "Instance"=>13000, "SetID"=>1039],
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
            5=>"DELETE", // Mixer
            6=>"DELETE", // Pedal matrix
            7=>"DELETE", // Crescendo
            8=>"DELETE", // Crescendo contd
    ];

    protected $patchDivisions=[
            9=>["DivisionID"=>9, "Name"=>"Noises", "Noise"=>TRUE]
    ];

    protected $patchTremulants=[
        89=>["Type"=>"Wave",  "DivisionID"=>2, "GroupIDs"=>[201,202,203,204]],
        24=>["Type"=>"Synth", "DivisionID"=>3, "GroupIDs"=>[301,302,303,304]],
        72=>["Type"=>"Wave",  "DivisionID"=>4, "GroupIDs"=>[401,402,403,404]],
    ];

    protected $patchEnclosures=[
        996=>["Panels"=>[2=>[987, NULL, 987]], "GroupIDs"=>[501,502,503,504], "AmpMinimumLevel"=>1], // Chamade
        997=>["Panels"=>[2=>[984, NULL, 984]], "GroupIDs"=>[401,402,403,404], "AmpMinimumLevel"=>1], // Schw
        998=>["Panels"=>[2=>[981, NULL, 981]], "GroupIDs"=>[201,202,203,204], "AmpMinimumLevel"=>1], // Pos
    ];

    protected $patchStops=[
        250=>["StopID"=>250, "DivisionID"=>1, "Name"=>"Blower",        "ControllingSwitchID"=>250,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>900],
        -11=>["StopID"=>-11, "DivisionID"=>1, "Name"=>"Pedal Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -12=>["StopID"=>-12, "DivisionID"=>2, "Name"=>"Pos Key On",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -13=>["StopID"=>-13, "DivisionID"=>3, "Name"=>"GO key On",      "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -14=>["StopID"=>-14, "DivisionID"=>4, "Name"=>"Rec key On",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -15=>["StopID"=>-15, "DivisionID"=>5, "Name"=>"Cham key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -21=>["StopID"=>-21, "DivisionID"=>1, "Name"=>"Pedal Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -22=>["StopID"=>-22, "DivisionID"=>2, "Name"=>"Pos Key Off",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -23=>["StopID"=>-23, "DivisionID"=>3, "Name"=>"GO key Off",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -24=>["StopID"=>-24, "DivisionID"=>4, "Name"=>"Rec key Off",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -25=>["StopID"=>-25, "DivisionID"=>5, "Name"=>"Cham key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[
          8=>["Noise"=>"StopOn",  "GroupID"=>900, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>900, "StopIDs"=>[]],
        950=>["Noise"=>"Ambient", "GroupID"=>900, "StopIDs"=>[250]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-11]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-12]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-13]],
        984=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-14]],
        985=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-15]],
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-21]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-22]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-23]],
        994=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-24]],
        995=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-25]],
    ];

    protected function xxpatchData(\HWClasses\HWData $hwd) : void {
        $stopranks=$hwd->read("StopRank");
        foreach($stopranks as $id=>$stoprank) {
            if (($id % 4)==0 
                    && isset($stopranks[$id+1]["AlternateRankID"])
                    && !empty($stopranks[$id+1]["AlternateRankID"])) {
                $this->patchStopRanks[$id]=["AlternateRankID"=>$stopranks[$id+1]["AlternateRankID"]+2];
            }
        }
        parent::patchData($hwd);
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2283; 
        return parent::createOrgan($hwdata);
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (($hwdata["RankID"] % 10)<5)
            return parent::createRank($hwdata, $keynoise);
        else
            return NULL;
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
        
        $filename=str_replace("\\", "/", $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $hwdata): void {
        if (isset($hwdata["StopID"]) && $hwdata["StopID"]==250) {
            $pe20=$this->getPanel(20)->GUIElement($switch);
            $this->configureImage($pe20, ["SwitchID"=>1050]);
            $pe20->PositionX=2236;
            $pe20->PositionY=107;
            $pe20->MouseRectWidth=160;
            $pe22=$this->getPanel(22)->GUIElement($switch);
            $this->configureImage($pe22,["SwitchID"=>1050]);
            $pe22->PositionX=1361;
            $pe22->PositionY=1797;
            $pe22->MouseRectWidth=160;
        }
        else
            parent::configurePanelSwitchImages ($switch, $hwdata);
    }

    protected function sampleMidiKey(array $hwdata) : int {
        $key=12+$hwdata["PipeID"] % 100;
        return $key;
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
        if ($pipe) unset($pipe->PitchTuning);
        return $pipe;
    }
    
    /**
     * Run the import
     */
    public static function Billerbeck(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=73;
        if (sizeof($positions)>0) {
            $hwi=new Billerbeck(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("8ch", $target, $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getManual(4)->NumberOfLogicalKeys=73;
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::Billerbeck(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Billerbeck(
                    [self::RANKS_SEMI_DRY=>"Semi-dry"],
                    "Semi-Dry");
            self::Billerbeck(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Billerbeck(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
             self::Billerbeck(
                    [
                        self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_SEMI_DRY=>"Semi-dry",
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "8ch");
        }
    }
}
Billerbeck::Billerbeck();