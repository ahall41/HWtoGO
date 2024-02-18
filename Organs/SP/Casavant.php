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
 * @todo: Voce Umana and Flute celeste (pos) and Voix celeste (recit) start at MIDI 48
 *        Full Organ should turn all stops on
 *        Clochettes which is near & which is far?
 * 
 * @author andrew
 */
class Casavant extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/CasavantFull/";
    const VERSION="1.2";
    const REVISIONS=
              "\n"
            . "1.1 Correct voix celeste pitch; remove empty tremulant ranks\n"
            . "1.2 Added full surround, Unison Off couplers\n"
            . "\n";

    protected ?int $releaseCrossfadeLengthMs=NULL;

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

    protected $patchTremulants=[
        25=>["Type"=>"Wave", "GroupIDs"=>[401,404]], // Rec
        72=>["Type"=>"Wave", "GroupIDs"=>[301,304]], // Pos
    ];

    public function import(): void {
        parent::import();
        
        $this->addImages();
        foreach([440,444,448,449,660,664,668,669] as $rankid) { // Celeste
            if (($rank=$this->getRank($rankid))) {
                foreach ($rank->Pipes() as $pipe) {
                    unset($pipe->PitchTuning);
                }
            }
        }
        
        foreach ([570, 574] as $rankid) { // Chimes
            if (($rank=$this->getRank($rankid))) {
                $rank->Percussive="Y";
            }
        }
        
        $this->addVirtualKeyboards(3, [1,2,3],[1,2,3]);
    }
    
    public function createSwitchNoise(string $type, array $hwdata) : void {
        return; // No Op
    }
    
    public function createManuals(array $keyboards): void {
        foreach ([1,2,3,4] as $id) {
            $this->createManual($keyboards[$id]);
        }
    }
    
    public function createCouplers(array $keyactions): void {
        parent::createCouplers($keyactions);
        $this->createCoupler(
                ["SourceKeyboardID"=>2,  // GO
                 "DestDivisionID"=>2,
                 "ConditionSwitchID"=>19053,
                 "ActionEffectCode"=>1,
                 "ConditionSwitchLinkIfEngaged"=>"Y",
                 "Name"=>"Unison Off 2"]);
        $this->createCoupler(
                ["SourceKeyboardID"=>4,  // RE
                 "DestDivisionID"=>4,
                 "ConditionSwitchID"=>19030,
                 "ActionEffectCode"=>1,
                 "ConditionSwitchLinkIfEngaged"=>"Y",
                 "Name"=>"Unison Off 3"]);
        $this->createCoupler(
                ["SourceKeyboardID"=>3, // PO
                 "DestDivisionID"=>3,
                 "ConditionSwitchID"=>19076,
                 "ActionEffectCode"=>1,
                 "ConditionSwitchLinkIfEngaged"=>"Y",
                 "Name"=>"Unison Off 4"]);
    }
    
    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["DestDivisionID"]>4) return NULL;
        return parent::createCoupler($hwdata);
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 5:
                    echo ($instanceID=$instance["ImageSetInstanceID"]), "\t",
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
        $hwdata["Identification_UniqueOrganID"]=2096; 
        return parent::createOrgan($hwdata);
    }
    

    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        
        if (($hwdata["RankID"] % 10)<5) {
            return parent::createRank($hwdata, $keynoise);
        }
        else
            return NULL;
    }
    
    public function addImages() : void {
        $this->addPanelImages(2, 13087); // Full Organ
        //$this->addPanelImages(2, 13088); // Clochettes
        $this->addPanelImages(2, 914); // Crescendo
        $this->addPanelImages(2, 1458); // Matrix 1
        $this->addPanelImages(2, 1459); // Matrix 2
        $this->addPanelImages(2, 1460); // Matrix 3
        
        $this->addPanelImages(3, 918); // Crescendo

        $this->addPanelImages(5, 200000); // Pipe Coupling
        $this->addPanelImages(5, 200010); // Pipe Detume
        
        $this->addPanelImages(10, 11087); // Full Organ
        //$this->addPanelImages(10, 11088); // Clochettes
        $this->addPanelImages(10, 916); // Crescendo
        $this->addPanelImages(10, 1455); // Matrix 1
        $this->addPanelImages(10, 1456); // Matrix 2
        $this->addPanelImages(10, 1457); // Matrix 3
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
        
        switch (($pipeid=$hwdata["PipeID"])) {
            case 96088:
            case 96588:
                $stop=$this->getStop($pipeid%1000);
                if ($stop && $isattack) {
                    $ambience=$stop->Ambience();
                    $this->configureAttack($hwdata, $ambience);
                }
                break;
            
            default:
                return parent::processSample($hwdata, $isattack);
                break;
        }
        return NULL;
    }
}

class CasavantDemo extends Casavant {
    const SOURCE="OrganDefinitions/Bellevue, Casavant Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Bellevue, Casavant (Demo - %s) " . self::VERSION . ".organ";

    protected $patchDivisions=[
            5=>"DELETE", // Chamade
            6=>"DELETE", // Chamade
            9=>["DivisionID"=>9, "Name"=>"Noises", "Noise"=>TRUE]
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
            "GroupIDs"=>[401], "AmpMinimumLevel"=>1],
        944=>["EnclosureID"=>944, "Name"=>"Rec Rear", "Panels"=>[5=>[1544]], 
            "GroupIDs"=>[404], "AmpMinimumLevel"=>1],
        997=>["Name"=>"Rec", "Panels"=>[2=>[985, NULL, 985], 3=>[983, NULL, 983], 10=>[987, NULL, 987]], 
            "GroupIDs"=>[401,404], "AmpMinimumLevel"=>20],
        998=>["Name"=>"Pos", "Panels"=>[2=>[978, NULL, 978], 3=>[976, NULL, 976], 10=>[980, NULL, 980]], 
            "GroupIDs"=>[301,304], "AmpMinimumLevel"=>20], 
    ];

    public function addImages() : void {
        parent::addImages();;
        $this->addPanelImages(2, 13088); // Clochettes;
        $this->addPanelImages(10, 11088); // Clochettes;
    }
    
    /**
     * Run the import
     */
    public static function Casavant(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new CasavantDemo(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
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

class CasavantFull extends Casavant {
    const SOURCE="OrganDefinitions/Bellevue, Casavant Surround.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Bellevue, Casavant (Surround - %s) " . self::VERSION . ".organ";

    protected $patchDivisions=[
        6=>"DELETE",
        7=>["DivisionID"=>7, "Name"=>"Toys"],
        8=>["DivisionID"=>8, "Name"=>"Blower",  "Noise"=>TRUE],
        9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    protected $patchStops=[
          89=>["StopID"=>  89, "DivisionID"=>1, "Name"=>"Blower",      "ControllingSwitchID"=>1050, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N", "Ambient"=>TRUE, "GroupID"=>800],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"Ped Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -112=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"Grt Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -113=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"Rec key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -114=>["StopID"=>-114, "DivisionID"=>4, "Name"=>"Pos key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"Ped Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"Grt Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"Rec key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -124=>["StopID"=>-124, "DivisionID"=>4, "Name"=>"Pos key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
          88=>["StopID"=>  88, "DivisionID"=>1, "Name"=>"Clochette (Front)", "ControllingSwitchID"=>19088, "Ambient"=>TRUE, "GroupID"=>701],
         588=>["StopID"=> 588, "DivisionID"=>1, "Name"=>"Clochette (Rear)",  "ControllingSwitchID"=>19088, "Ambient"=>TRUE, "GroupID"=>704],
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
            "GroupIDs"=>[401], "AmpMinimumLevel"=>1],
        944=>["EnclosureID"=>944, "Name"=>"Rec Rear", "Panels"=>[5=>[1544]], 
            "GroupIDs"=>[404], "AmpMinimumLevel"=>1],
        950=>["EnclosureID"=>950, "Name"=>"Chm Front", "Panels"=>[5=>[1550]], 
            "GroupIDs"=>[501], "AmpMinimumLevel"=>1],
        954=>["EnclosureID"=>954, "Name"=>"Chm Rear", "Panels"=>[5=>[1554]], 
            "GroupIDs"=>[504], "AmpMinimumLevel"=>1],
        970=>["EnclosureID"=>970, "Name"=>"Toys Front", "Panels"=>[5=>[1596]], 
            "GroupIDs"=>[701], "AmpMinimumLevel"=>1],
        974=>["EnclosureID"=>974, "Name"=>"Toys Rear", "Panels"=>[5=>[1597]], 
            "GroupIDs"=>[704], "AmpMinimumLevel"=>1],
        980=>["EnclosureID"=>990, "Name"=>"Blower", "Panels"=>[5=>[1595]], 
            "GroupIDs"=>[900], "AmpMinimumLevel"=>1],
        990=>["EnclosureID"=>990, "Name"=>"Tracker", "Panels"=>[5=>[1599]], 
            "GroupIDs"=>[900], "AmpMinimumLevel"=>1],
        997=>["Name"=>"Rec", "Panels"=>[2=>[985, NULL, 985], 3=>[983, NULL, 983], 10=>[987, NULL, 987]], 
            "GroupIDs"=>[401,404], "AmpMinimumLevel"=>20],
        998=>["Name"=>"Pos", "Panels"=>[2=>[978, NULL, 978], 3=>[976, NULL, 976], 10=>[980, NULL, 980]], 
            "GroupIDs"=>[301,304], "AmpMinimumLevel"=>20], 
    ];

    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
       switch ($hwdata["StopID"]) {
            case 11: // Ped. Clairon en chamade
            case 16: // Ped. Trompette en chamade
                $hwdata["DivisionID"]=1;
                break;
            
            case 45: // GO. Trompette en chamade 8
                $hwdata["DivisionID"]=2;
                break;
            
            case 40: // Rec. Trompette en chamade 8
                $hwdata["DivisionID"]=4;
                break;
            
            case 62: // Pos. Clairon en chamade
            case 67: // Pos. Trompette en chamade 8
            case 71: // Pos. Bombarde en chamade 16
                $hwdata["DivisionID"]=3;
                break;
        }
        return parent::createStop($hwdata);
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        switch (intval($hwdata["RankID"]/10)) {
            case 45: // GO. Trompette en chamade 8
            case 62: // Pos. Clairon en chamade    
            case 71: // Pos. Bombarde en chamade 16
                $hwdata["DivisionID"]=5;
                break;
        }
        return parent::createRank($hwdata, $keynoise);
    }
    
    /**
     * Run the import
     */
    public static function Casavant(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new CasavantFull(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
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

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\SP\ErrorHandler");

CasavantFull::Casavant();
CasavantDemo::Casavant();