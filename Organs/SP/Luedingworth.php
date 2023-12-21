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
    
    const COMMENTS= 
              "\n"
            . "1.1 Added Ventils, Toys and Full surround ODFs\n"
            . "\n";
    
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
                0=>["Instance"=>800, "SetID"=>1036],
               ],
            6=>[
                0=>["Instance"=>13000, "SetID"=>1038],
               ],
    ];

    protected $patchDivisions=[
            5=>["DivisionID"=>5, "Name"=>"Toys",    "Noise"=>FALSE],
            8=>["DivisionID"=>8, "Name"=>"Blower",  "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    protected $patchTremulants=[
        36=>["Type"=>"Wave", "GroupIDs"=>[101,103,104,201,203,204,301,303,304,401,403,404]],
    ];

    protected $patchEnclosures=[
        /* 801=>["Name"=>"Pedal",  "EnclosureID"=>801, "X"=>1400, "Y"=>700,
            "GroupIDs"=>[101,102,104]],
        802=>["Name"=>"RP",     "EnclosureID"=>802, "X"=>1400, "Y"=>800,
            "GroupIDs"=>[201,202,204]],
        803=>["Name"=>"OW",     "EnclosureID"=>803, "X"=>1400, "Y"=>900, 
            "GroupIDs"=>[301,302,304]],
        803=>["Name"=>"BW",     "EnclosureID"=>804, "X"=>1400, "Y"=>1000, 
            "GroupIDs"=>[301,302,304]], */
        901=>["Panels"=>[5=>[1600]], "EnclosureID"=>901, "Name"=>"Direct",
            "GroupIDs"=>[101,201,301,401,501], "AmpMinimumLevel"=>1],
        902=>["Panels"=>[5=>[1610]], "EnclosureID"=>902, "Name"=>"Diffuse",
            "GroupIDs"=>[103,203,303,403,503], "AmpMinimumLevel"=>1],
        903=>["Panels"=>[5=>[1620]], "EnclosureID"=>903,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304,404,504], "AmpMinimumLevel"=>1],
        905=>["Panels"=>[5=>[1598]], "EnclosureID"=>907,"Name"=>"Toys",
            "GroupIDs"=>[501,503,504], "AmpMinimumLevel"=>1],
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
        136=>["StopID"=>136, "DivisionID"=>1, "Name"=>"Tremulant Direct",    "ControllingSwitchID"=>36, "Ambient"=>TRUE, "GroupID"=>501],
        236=>["StopID"=>236, "DivisionID"=>1, "Name"=>"Tremulant Diffuse",   "ControllingSwitchID"=>36, "Ambient"=>TRUE, "GroupID"=>503],
        436=>["StopID"=>436, "DivisionID"=>1, "Name"=>"Tremulant Rear",      "ControllingSwitchID"=>36, "Ambient"=>TRUE, "GroupID"=>504],
        137=>["StopID"=>137, "DivisionID"=>1, "Name"=>"Zimbelstern Direct",  "ControllingSwitchID"=>37, "Ambient"=>TRUE, "GroupID"=>501],
        237=>["StopID"=>237, "DivisionID"=>1, "Name"=>"Zimbelstern Diffuse", "ControllingSwitchID"=>37, "Ambient"=>TRUE, "GroupID"=>503],
        437=>["StopID"=>437, "DivisionID"=>1, "Name"=>"Zimbelstern Rear",    "ControllingSwitchID"=>37, "Ambient"=>TRUE, "GroupID"=>504],
        143=>["StopID"=>143, "DivisionID"=>1, "Name"=>"Vogelgesang Direct",  "ControllingSwitchID"=>43, "Ambient"=>TRUE, "GroupID"=>501],
        243=>["StopID"=>243, "DivisionID"=>1, "Name"=>"Vogelgesang Diffuse", "ControllingSwitchID"=>43, "Ambient"=>TRUE, "GroupID"=>503],
        443=>["StopID"=>443, "DivisionID"=>1, "Name"=>"Vogelgesang Rear",    "ControllingSwitchID"=>43, "Ambient"=>TRUE, "GroupID"=>504],
    ];
    
    protected $patchKeyActions=[
          1=>["SourceKeyboardID"=>2, "DestKeyboardID"=>2, "Name"=>"RP Ventil",  "ConditionSwitchID"=>19001],
          7=>["SourceKeyboardID"=>1, "DestKeyboardID"=>1, "Name"=>"Ped Ventil", "ConditionSwitchID"=>19007],
         13=>["SourceKeyboardID"=>3, "DestKeyboardID"=>3, "Name"=>"OW Ventil",  "ConditionSwitchID"=>19013],
         19=>["SourceKeyboardID"=>4, "DestKeyboardID"=>4, "Name"=>"BP Ventil",  "ConditionSwitchID"=>19019  ],
    ];

    protected $patchRanks=[
          5=>"DELETE", //
          6=>"DELETE", // Toys - see ProcessNoise()
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

    protected function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        //error_log(print_r($data,1));
        if (isset($data["ControllingSwitchID"])) {
            $data["StopID"]=$data["ControllingSwitchID"];
        }
        parent::configurePanelSwitchImages($switch, $data);
    }
    
    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2302; 
        return parent::createOrgan($hwdata);
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if ((($rankid=$hwdata["RankID"]) % 10)<5 && $rankid!=950) {
            return parent::createRank($hwdata, $keynoise);
        }
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
        if (sizeof($files)==0) {
            $files=$this->treeWalk(getenv("HOME") . $this->root);
        }
        
        $filename=str_replace("//", "/", $filename);
        if (strpos($filename, "backgrounds")!==FALSE) {
            $filename=str_replace("OrganInstallationPackages/002302/", "", $filename);
        }
        if (isset($files[strtolower($filename)])) {
            $result=$files[strtolower($filename)];
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
            $pe->PositionX=$data["X"];
            $pe->PositionY=$data["Y"];
        }
    }

    public function isNoiseSample(array $hwdata): bool {
        if (in_array($hwdata["RankID"],[5,6,7])) {
            return TRUE;
        }
        return parent::isNoiseSample($hwdata);
    }
    
    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        static $offsets=[960=>100, 965=>200, 967=>400];
        if (in_array(($rankid=$hwdata["RankID"]),[5,6,7])) {
            $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
            $stopid=$hwdata["NormalMIDINoteNumber"]
                    + $offsets[intval($hwdata["SampleID"]/1000)];
            // echo $stopid, "\t", $hwdata["SampleFilename"], "\n";
            $stop=$this->getStop($stopid);
            if ($stop!==NULL) {
                $ambience=$stop->Ambience();
                    $this->configureAttack($hwdata, $ambience);
                return $ambience;
            }
            return NULL;
        }
        return parent::processNoise($hwdata, $isattack);
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
}

class Demo extends Luedingworth {
    const ROOT="/GrandOrgue/Organs/SP/LuedingworthFull/";
    const SOURCE="OrganDefinitions/Luedingworth Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Luedingworth Demo (%s) 1.1.organ";
    
    protected string $root=self::ROOT;
    
    /**
     * Run the import
     */
    public static function Luedingworth(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
        if (sizeof($positions)>0) {
            $hwi=new Demo(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->addCouplerManuals(3, [1,2,3], [1,2,3]);
            $hwi->getOrgan()->ChurchName.= " ($target)";
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
            }
            unset($hwi->getManual(1)->Key027Width);
            
            foreach([100,400,200] as $pid=>$pos) {
                foreach([36=>"trem", 37=>"Zimbelstern",43=>"vogelg"] as $stopid=>$wav) {
                    $stop=$hwi->getStop($pos + $stopid);
                    if ($stop && $stop->Ambience()->AttackCount<0) {
                        $stop->Ambience()->Attack=sprintf("OrganInstallationPackages/002302/pipe/Toys/%s_%01d1.wav",$wav,$pid);
                        // echo $stop, "\n";
                    }
                }
            }
            
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Luedingworth(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Luedingworth(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Luedingworth(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear"); 
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

class Full extends Luedingworth {
    const ROOT="/GrandOrgue/Organs/SP/LuedingworthFull/";
    const SOURCE="OrganDefinitions/Luedingworth Surround.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Luedingworth Full (%s) 1.0.organ";
    
    protected string $root=self::ROOT;
    
    /**
     * Run the import
     */
    public static function Luedingworth(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
        if (sizeof($positions)>0) {
            $hwi=new Full(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->addCouplerManuals(3, [1,2,3], [1,2,3]);
            $hwi->getOrgan()->ChurchName.= " ($target)";
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
            }
            unset($hwi->getManual(1)->Key027Width);
            
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Luedingworth(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::Luedingworth(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::Luedingworth(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear"); 
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

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\SP\ErrorHandler");

Full::Luedingworth();
Demo::Luedingworth();