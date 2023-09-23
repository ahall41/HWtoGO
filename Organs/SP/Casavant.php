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
 * Import Sonus Paradisi Casavant, opus 3742 (1995), Bellevue, Washington to GrandOrgue
 * The mixer panel could not be completed as the HW ODF references
 * to the corresponding images are missing
 *  
 * @author andrew
 */
class Casavant extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/Casavant/";
    const SOURCE="OrganDefinitions/Bellevue, Casavant Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Bellevue, Casavant (Demo - %s) 1.1.organ";
    const REVISIONS=
              "\n"
            . "1.1 Correct voix celeste pitch; remove empty tremulant ranks\n"
            . "\n";

    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT, 9=>self::RANKS_DIRECT,
        4=>self::RANKS_REAR,   8=>self::RANKS_REAR
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>[
                0=>["Group"=>"Single", "Name"=>"Landscape", "Instance"=>13000, "SetID"=>1036],
                1=>[],
                2=>["Group"=>"Single", "Name"=>"Portrait", "Instance"=>13000, "SetID"=>1043],
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
                0=>["SetID"=>1035]
               ],
            6=>"DELETE", // Pedal matrix
            7=>"DELETE", // Crescendo
            8=>"DELETE", // Crescendo contd
            9=>"DELETE", // Combinations
           10=>[
                0=>["Group"=>"Simple", "Name"=>"Landscape", "Instance"=>11000, "SetID"=>1042],
                1=>[],
                2=>["Group"=>"Simple", "Name"=>"Portrait", "Instance"=>11000, "SetID"=>1040],
               ]
    ];

    protected $patchDivisions=[
            5=>"DELETE", // Chamade
            6=>"DELETE", // Chamade
            9=>["DivisionID"=>9, "Name"=>"Noises", "Noise"=>TRUE]
    ];

    protected $patchTremulants=[
        25=>["Type"=>"Wave", "GroupIDs"=>[401,404]], // Rec
        72=>["Type"=>"Wave", "GroupIDs"=>[301,304]], // Pos
    ];

    protected $patchEnclosures=[
        900=>["EnclosureID"=>900, "Name"=>"Master Front", "Panels"=>[5=>[1500]], 
            "GroupIDs"=>[101,201,301,401], "AmpMinimumLevel"=>1],
        904=>["EnclosureID"=>904, "Name"=>"Master Rear", "Panels"=>[5=>[1504]], 
            "GroupIDs"=>[104,204,304,404], "AmpMinimumLevel"=>1],
        910=>["EnclosureID"=>910, "Name"=>"Ped Front", "Panels"=>[5=>[1510]], 
            "GroupIDs"=>[101], "AmpMinimumLevel"=>1],
        914=>["EnclosureID"=>914, "Name"=>"Ped Rear", "Panels"=>[5=>[1514]], 
            "GroupIDs"=>[104], "AmpMinimumLevel"=>1],
        920=>["EnclosureID"=>920, "Name"=>"Grt Front", "Panels"=>[5=>[1520]], 
            "GroupIDs"=>[201], "AmpMinimumLevel"=>1],
        924=>["EnclosureID"=>924, "Name"=>"Grt Rear", "Panels"=>[5=>[1524]], 
            "GroupIDs"=>[204], "AmpMinimumLevel"=>1],
        930=>["EnclosureID"=>930, "Name"=>"Pos Front", "Panels"=>[5=>[1530]], 
            "GroupIDs"=>[301], "AmpMinimumLevel"=>1],
        934=>["EnclosureID"=>934, "Name"=>"Pos Rear", "Panels"=>[5=>[1534]], 
            "GroupIDs"=>[304], "AmpMinimumLevel"=>1],
        940=>["EnclosureID"=>940, "Name"=>"Rec Front", "Panels"=>[5=>[1540]], 
            "GroupIDs"=>[301], "AmpMinimumLevel"=>1],
        944=>["EnclosureID"=>944, "Name"=>"Rec Rear", "Panels"=>[5=>[1544]], 
            "GroupIDs"=>[304], "AmpMinimumLevel"=>1],
        997=>["Name"=>"Rec", "Panels"=>[2=>[985, NULL, 985], 3=>[983, NULL, 983], 10=>[987, NULL, 987]], 
            "GroupIDs"=>[401,404], "AmpMinimumLevel"=>20],
        998=>["Name"=>"Pos", "Panels"=>[2=>[978, NULL, 978], 3=>[976, NULL, 976], 10=>[980, NULL, 980]], 
            "GroupIDs"=>[301,304], "AmpMinimumLevel"=>20], 
    ];

    public function createSwitchNoise(string $type, array $hwdata) : void {
        return; // No Op
    }
    
    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["DestDivisionID"]>4) return NULL;
        return parent::createCoupler($hwdata);
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2096; 
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
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
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
        }
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the import
     */
    public static function Casavant(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new Casavant(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getManual(4)->NumberOfLogicalKeys=73;
            foreach([440,444,448,449] as $rankid) {
                $rank=$hwi->getRank($rankid);
                if ($rank) {
                    foreach ($rank->Pipes() as $pipe) unset($pipe->PitchTuning);
                }
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            self::Casavant(
                    [
                        self::RANKS_DIRECT=>"Front", 
                    ],
                   "dry");
            self::Casavant(
                    [
                        self::RANKS_REAR=>"Rear"
                    ],
                   "wet");
            self::Casavant(
                    [
                        self::RANKS_DIRECT=>"Front", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "4ch");
        }
    }
}
Casavant::Casavant();