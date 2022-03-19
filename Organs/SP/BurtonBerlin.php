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
 * The mixer panel could not be completed as the HW ODF references
 * to the corresponding images are missing
 * 
 * @author andrew
 */
class BurtonBerlin extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/Burton-Berlin/";
    const SOURCE="OrganDefinitions/Burton-Berlin Hill Surround Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Burton-Berlin Hill %s Demo.0.1.organ";
    
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
            9=>"DELETE", // Combinations
           10=>[
                0=>["Name"=>"Stops", "Instance"=>13000, "SetID"=>1041],
                // 1=>["Group"=>"Stops", "Name"=>"Wide",   "Instance"=>13000, "SetID"=>1042],
               ],
    ];

    protected $patchDivisions=[
            9=>["DivisionID"=>9, "Name"=>"Noises", "Noise"=>TRUE]
    ];

    protected $patchTremulants=[
         1=>["Type"=>"Synth",    "DivisionID"=>4, "GroupIDs"=>[401,402,403]],
        45=>["Type"=>"Switched", "DivisionID"=>2],
    ];

    protected $patchEnclosures=[
        997=>["Panels"=>[2=>[987], 4=>[986,NULL,986], 10=>[984]], 
            "GroupIDs"=>[201,202,203], "AmpMinimumLevel"=>10], // Choir
        998=>["Panels"=>[2=>[981], 4=>[980,NULL,980], 10=>[978]], 
            "GroupIDs"=>[401,402,403], "AmpMinimumLevel"=>10], // Swell
    ];

    protected $patchStops=[
       +250=>["StopID"=>+250, "DivisionID"=>1, "Name"=>"Blower",      "ControllingSwitchID"=>250,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>900],
       -101=>["StopID"=>-101, "DivisionID"=>1, "Name"=>"Ped Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -102=>["StopID"=>-102, "DivisionID"=>2, "Name"=>"Ch Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -103=>["StopID"=>-103, "DivisionID"=>3, "Name"=>"Gt key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -104=>["StopID"=>-104, "DivisionID"=>4, "Name"=>"Sw key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -201=>["StopID"=>-201, "DivisionID"=>1, "Name"=>"Ped Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -202=>["StopID"=>-202, "DivisionID"=>2, "Name"=>"Ch Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -203=>["StopID"=>-203, "DivisionID"=>3, "Name"=>"GT key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -204=>["StopID"=>-204, "DivisionID"=>4, "Name"=>"SW key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
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

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2261; 
        return parent::createOrgan($hwdata);
    }
    
    protected function correctFileName(string $filename): string {
        $root=getenv("HOME") . self::ROOT;
        $filename=str_replace(
                ["\\", "jamb.png", "jambw.png", "choir", "/go/", "ortf", "_hw_", "clarinet/", 
                    "_tierce_tr/", "viol8_tr", "_flute4", "_aeoline8_tr", "_oboe_tr", "_gedackt_tr",
                    "_viol8", "_aeoline8/", "_larigot/", "_larigot_tr", "_oboe/", "_gedackt/",
                    "nazard_tr", "tierce/", "piccolo/", "nazard/", "_piccolo_tr", "_clarinet_tr",
                    "-ab_", "-rea_", "stopsw.png"],
                ["/" , "Jamb.png", "JambW.png", "Choir", "/GO/", "ORTF", "_HW_", "Clarinet/", 
                    "_Tierce_tr/", "Viol8_tr", "_Flute4", "_Aeoline8_tr", "_Oboe_tr", "_Gedackt_tr",
                    "_Viol8", "_Aeoline8/", "_Larigot/", "_Larigot_tr", "_Oboe/", "_Gedackt/",
                    "Nazard_tr", "Tierce/", "Piccolo/", "Nazard/", "_Piccolo_tr", "_Clarinet_tr",
                    "-AB_", "-Rea_", "stopsW.png"],
                $filename
        );
        if (file_exists("$root/$filename")) 
            return $filename;

        foreach([".bmp", ".BMP", ".jpg"] as $sfx) {
            $newfile=substr($filename, 0, -strlen($sfx)) . $sfx;
            if (file_exists("$root/$newfile")) 
                return $newfile;        
        }
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

    /**
     * Run the import
     */
    public static function BurtonBerlin(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=56;
        if (sizeof($positions)>0) {
            $hwi=new BurtonBerlin(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("Surr.", "$target ", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::BurtonBerlin(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::BurtonBerlin(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::BurtonBerlin(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
             self::BurtonBerlin(
                    [self::RANKS_DIRECT=>"Direct", self::RANKS_SEMI_DRY=>"Diffuse", self::RANKS_DIFFUSE=>"Rear"],
                     "Surround");
        }
    }
}
BurtonBerlin::BurtonBerlin();