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
 * Import Sonus Paradisi Burton Berlin Demo to GrandOrgue
 * @todo: Mixer panel
 * 
 * @author andrew
 */
class BurtonBerlinFull extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/BurtonBerlinFull/";
    const SOURCE="OrganDefinitions/Burton-Berlin Hill Surround.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Burton-Berlin Hill %s.1.2.organ";
    const COMMENTS="/n"
            . "1.1 Corrected tremulants and Diffuse expression\n"
            . "1.2 Added virtul keyboards for 2 keyboard operation\n"
            . "    Corrected surround channels\n"
            . "\n";
    
    protected string $root=self::ROOT;
    protected array  $rankpositions=[
        0=>self::RANKS_DIFFUSE,  9=>self::RANKS_DIFFUSE,
        1=>self::RANKS_DIRECT,   7=>self::RANKS_DIRECT,
        4=>self::RANKS_REAR,     8=>self::RANKS_REAR
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>[
                0=>["Name"=>"Simple", "Instance"=>11000, "SetID"=>1036],
               ],
            3=>[
                0=>["Group"=>"Landscape", "Instance"=>12000, "SetID"=>1031],
                1=>[],
                2=>["Group"=>"Portrait", "Instance"=>12000, "SetID"=>1033],
               ],
            4=>[
                0=>["Group"=>"Landscape", "Instance"=>12000, "SetID"=>1032],
                1=>[],
                2=>["Group"=>"Portrait", "Instance"=>12000, "SetID"=>1034],
               ],
            5=>"DELETE", // Mixer
            6=>"DELETE", // Pedal matrix
            7=>"DELETE", // Crescendo
            8=>"DELETE", // Crescendo contd
            9=>"DELETE", // Combinations
           10=>[
                0=>["Name"=>"Stops", "Instance"=>13000, "SetID"=>1041],
               ],
    ];

    protected $patchDivisions=[
            8=>["DivisionID"=>8, "Name"=>"Blower", "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Noises", "Noise"=>TRUE]
    ];

    protected $patchTremulants=[
         1=>["Type"=>"Wave", "DivisionID"=>4, "GroupIDs"=>[401,403,404]],
        45=>["Type"=>"Wave", "DivisionID"=>2, "GroupIDs"=>[201,203,204]],
    ];

    protected $patchEnclosures=[
        997=>["Panels"=>[2=>[987], 4=>[986,NULL,986], 10=>[984]], 
            "GroupIDs"=>[201,203,204], "AmpMinimumLevel"=>20], // Choir
        998=>["Panels"=>[2=>[981], 4=>[980,NULL,980], 10=>[978]], 
            "GroupIDs"=>[401,403,404], "AmpMinimumLevel"=>20], // Swell
    ];

    protected $patchStops=[
       +250=>["StopID"=>+250, "DivisionID"=>1, "Name"=>"Blower",      "ControllingSwitchID"=>250,  "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N", "Ambient"=>TRUE, "GroupID"=>800],
       -101=>["StopID"=>-101, "DivisionID"=>1, "Name"=>"Ped Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
       -102=>["StopID"=>-102, "DivisionID"=>2, "Name"=>"Ch Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
       -103=>["StopID"=>-103, "DivisionID"=>3, "Name"=>"Gt key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
       -104=>["StopID"=>-104, "DivisionID"=>4, "Name"=>"Sw key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
       -201=>["StopID"=>-201, "DivisionID"=>1, "Name"=>"Ped Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
       -202=>["StopID"=>-202, "DivisionID"=>2, "Name"=>"Ch Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
       -203=>["StopID"=>-203, "DivisionID"=>3, "Name"=>"GT key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
       -204=>["StopID"=>-204, "DivisionID"=>4, "Name"=>"SW key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
    ];
    
    protected $patchRanks=[
          8=>["Noise"=>"StopOn",  "GroupID"=>900, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>900, "StopIDs"=>[]],
        950=>["Noise"=>"Ambient", "GroupID"=>900, "StopIDs"=>[+250]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-101]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-102]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-103]],
        984=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-104]],
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-201]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-202]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-203]],
        994=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-204]],
    ];

    protected $patchKeyActions=[
        98=>["Name"=>"CH Uni Off", "SourceKeyboardID"=>2, "DestKeyboardID"=>2, "ConditionSwitchID"=>19075],
        99=>["Name"=>"Sw Uni Off", "SourceKeyboardID"=>4, "DestKeyboardID"=>4, "ConditionSwitchID"=>19074]
    ];
    
    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2261; 
        return parent::createOrgan($hwdata);
    }

    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        if (($switchid=$hwdata["ControllingSwitchID"])) {
            $switchdata=$this->hwdata->switch($switchid, TRUE);
            if (isset($switchdata["Name"])) $hwdata["SwitchName"]=$switchdata["Name"];
        }
        if (!isset($this->patchStops[$hwdata["StopID"]]))
            $hwdata["ControllingSwitchID"]=$hwdata["StopID"];
        $switch= \Import\Configure::createStop($hwdata);
        return $switch;
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
        
        $filename=str_replace("//", "/", $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $hwdata): void {    
        if (isset($hwdata["StopID"]) && $hwdata["StopID"]==250) {
            $pe20=$this->getPanel(20)->GUIElement($switch);
            $this->configureImage($pe20, ["SwitchID"=>1050]);
            $on=$pe20->ImageOn;
            $pe20->ImageOn=$pe20->ImageOff;
            $pe20->ImageOff=$on;
            $pe20->PositionX=2250;
            $pe20->PositionY=40;
            $pe20->MouseRectWidth=160;
        }
        else
            parent::configurePanelSwitchImages ($switch, $hwdata);
    }

    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        //$hwdata["ReleaseCrossfadeLengthMs"]=100;
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
        }
        return parent::processSample($hwdata, $isattack);
    }
    
    /**
     * Run the import
     */
    public static function BurtonBerlinFull(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=56;
        if (sizeof($positions)>0) {
            $hwi=new BurtonBerlinFull(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->addVirtualKeyboards(3, [1,2,3], [1,2,3]);
            $hwi->getOrgan()->ChurchName=str_replace("Surround", "$target", $hwi->getOrgan()->ChurchName);
            $hwi->getSwitch(250)->DisplayInInvertedState="Y";
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
            }
            foreach ([70,71,74,120,121,124] as $rankid) {
                $rank=$hwi->getRank($rankid);
                if ($rank) {
                    foreach($rank->Pipes() as $pipe) {
                        unset($pipe->IsTremulant);
                        unset($pipe->Release001IsTremulant);
                        unset($pipe->Release002IsTremulant);
                        unset($pipe->Release003IsTremulant);
                    }
                }
            }
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::BurtonBerlinFull(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::BurtonBerlinFull(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::BurtonBerlinFull(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
             self::BurtonBerlinFull(
                    [self::RANKS_DIRECT=>"Direct", self::RANKS_DIFFUSE=>"Diffuse", self::RANKS_REAR=>"Rear"],
                     "Surround");
        }
    }
}
BurtonBerlinFull::BurtonBerlinFull();