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
 * Import Sonus Paradisi Luedingworth Demo to GrandOrgue
 *  
 * @author andrew
 */
class Luedingworth extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/Luedingworth/";
    const SOURCE="OrganDefinitions/Luedingworth Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Luedingworth Demo (%s) 1.0.organ";
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,  9=>self::RANKS_DIRECT,
        1=>self::RANKS_DIFFUSE, 7=>self::RANKS_DIFFUSE,
        2=>self::RANKS_REAR,    6=>self::RANKS_REAR,
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>[
                0=>["Group"=>"Landscape", "Instance"=>11000, "SetID"=>1035],
                1=>[],
                2=>["Group"=>"Portrait", "Instance"=>11000, "SetID"=>1037],
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
            5=>[
                0=>["Instance"=>800, "SetID"=>1036],
               ],
            6=>[
                0=>["Instance"=>13000, "SetID"=>1038],
               ],
    ];

    protected $patchDivisions=[
            8=>["DivisionID"=>8, "Name"=>"Blower", "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    protected $patchTremulants=[
        36=>["Type"=>"Wave", "GroupIDs"=>[101,102,104,201,202,204,301,302,304,401,402,404]],
    ];

    protected $patchEnclosures=[
        /* 801=>["Name"=>"Pedal",  "EnclosureID"=>801, "X"=>1400, "Y"=>700,
            "GroupIDs"=>[101,102,104]],
        802=>["Name"=>"RPtif",     "EnclosureID"=>802, "X"=>1400, "Y"=>800,
            "GroupIDs"=>[201,202,204]],
        803=>["Name"=>"OW",        "EnclosureID"=>803, "X"=>1400, "Y"=>900, 
            "GroupIDs"=>[301,302,304]], */
        901=>["Panels"=>[5=>[1600]], "EnclosureID"=>901, "Name"=>"Direct",
            "GroupIDs"=>[101,201,301,401], "AmpMinimumLevel"=>1],
        902=>["Panels"=>[5=>[1610]], "EnclosureID"=>902, "Name"=>"Diffuse",
            "GroupIDs"=>[102,202,302,402], "AmpMinimumLevel"=>1],
        903=>["Panels"=>[5=>[1620]], "EnclosureID"=>903,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304], "AmpMinimumLevel"=>1],
        908=>["Panels"=>[5=>[1595]], "EnclosureID"=>908,"Name"=>"Blower",
            "GroupIDs"=>[800], "AmpMinimumLevel"=>1],
        909=>["Panels"=>[5=>[1599]], "EnclosureID"=>909,"Name"=>"Tracker",
            "GroupIDs"=>[900], "AmpMinimumLevel"=>1]
    ];

    protected $patchStops=[
        250=>["StopID"=>250, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>250,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>800],
        -11=>["StopID"=>-11, "DivisionID"=>1, "Name"=>"PE Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -12=>["StopID"=>-12, "DivisionID"=>2, "Name"=>"PO Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -13=>["StopID"=>-13, "DivisionID"=>3, "Name"=>"HW key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -14=>["StopID"=>-14, "DivisionID"=>4, "Name"=>"BW key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -21=>["StopID"=>-21, "DivisionID"=>1, "Name"=>"PE Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -22=>["StopID"=>-22, "DivisionID"=>2, "Name"=>"PO Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -23=>["StopID"=>-23, "DivisionID"=>3, "Name"=>"GO key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -24=>["StopID"=>-24, "DivisionID"=>4, "Name"=>"BW key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[
          5=>"DELETE", //
          6=>"DELETE", // Toys
          7=>"DELETE", //
          8=>["Noise"=>"StopOn",  "GroupID"=>900, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>900, "StopIDs"=>[]],
        950=>["Noise"=>"Ambient", "GroupID"=>800, "StopIDs"=>[250]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-11]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-12]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-13]],
        984=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-14]],
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-21]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-22]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-23]],
        994=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-24]],
    ];

    protected function patchData(\HWClasses\HWData $hwd) : void {
        $stopranks=$hwd->read("StopRank");
        parent::patchData($hwd);
        return;
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if ($instance["DisplayPageID"]==5) {
                echo $instance["ImageSetInstanceID"], " ",
                     $instance["DisplayPageID"], " ",
                     $instance["Name"], "\n";
            }
        } 
        exit();
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2302; 
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
        $filename=str_replace("//", "/", $filename);
        if (isset($files[strtolower($filename)])) {
            $result=$files[strtolower($filename)];
            if (strpos($filename, "backgrounds"))
                $result=str_replace("OrganInstallationPackages/002302/", "", $result);
            return $result;
        }
        else
            throw new \Exception ("File $filename does not exist!");
    }
    
    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        if (isset($data["Panels"]))
            parent::configurePanelEnclosureImages($enclosure, $data);
        else {
            $panel=$this->getPanel(50); // Mixer
            $pe=$panel->GUIElement($enclosure);
            print_r($data);
            $pe->PositionX=$data["X"];
            $pe->PositionY=$data["Y"];
            echo $pe, "\n";
        }
    }

    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        // unset($hwdata["ReleaseCrossfadeLengthMs"]);
        $hwdata["IsTremulant"]=0;
        switch ($hwdata["RankID"] % 10) {
            case 9:
                $hwdata["RankID"]-=9;
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
        return parent::processSample($hwdata, $isattack);
    }
    
    /**
     * Run the import
     */
    public static function Luedingworth(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=73;
        if (sizeof($positions)>0) {
            $hwi=new Luedingworth(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.= " ($target)";
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
            }
            unset($hwi->getManual(1)->Key027Width);
            
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            /* self::Luedingworth(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Luedingworth(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Luedingworth(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear"); */
             self::Luedingworth(
                    [
                        self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "Surround");
        }
    }
}
Luedingworth::Luedingworth();