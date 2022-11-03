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
    const ROOT="/GrandOrgue/Organs/Casavant/";
    const SOURCE="OrganDefinitions/Bellevue, Casavant Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Bellevue, Casavant (Demo - %s) 1.0.organ";

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
        25=>["Type"=>"Switched", "DivisionID"=>4], // Rec
        72=>["Type"=>"Switched", "DivisionID"=>3], // Pos
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

    /* protected $patchStops=[
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
    ]; */
    
    /* protected $patchRanks=[
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
    ]; */

    public function xximport() : void {
        $hwd=$this->hwdata;
        foreach($hwd->imageSetElements() as $imagesetelements) {
            foreach($imagesetelements as $imagesetelement) {
                /* if (strpos($imagesetelement["BitmapFilename"], "montre8_on")>0) {
                    print_r($imagesetelement);
                    foreach($hwd->imageSetInstances() as $imagesetinstance) {
                        if ($imagesetinstance["ImageSetID"]==$imagesetelement["ImageSetID"]) {
                            print_r($imagesetinstance);
                        }
                    }
                } */
                // if (strpos($imagesetelement["BitmapFilename"], "000.bmp")>0) {
                if (strpos($imagesetelement["BitmapFilename"], "sliders/001.bmp")!==FALSE) {
                    //print_r($imagesetelement);
                    foreach($hwd->imageSetInstances() as $imagesetinstance) {
                        if ($imagesetinstance["ImageSetID"]==$imagesetelement["ImageSetID"]) {
                            echo $imagesetinstance["ImageSetInstanceID"], " ", 
                                 $imagesetinstance["ImageSetID"], " ",
                                 // $imagesetinstance["AlternateScreenLayout2_ImageSetID"], " ",
                                 $imagesetinstance["DisplayPageID"], " ",
                                 $imagesetelement["BitmapFilename"], " ",
                                 $imagesetinstance["Name"], "\n";
                        }
                    }
                }
            }
        }
        parent::import();
    }

    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["DestDivisionID"]>4) return NULL;
        return parent::createCoupler($hwdata);
    }

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2096; 
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

    /* public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $hwdata): void {
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
    } */
 
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
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            /* self::Casavant(
                    [self::RANKS_DIRECT=>"Front"],
                    "Front");
            self::Casavant(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear"); */
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