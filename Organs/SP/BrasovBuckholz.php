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
 * Import Sonus Paradisi BrasovBuckholz, opus 3742 (1995), Bellevue, Washington to GrandOrgue
 * The mixer panel could not be completed as the HW ODF references
 * to the corresponding images are missing
 *  
 * @author andrew
 */
class BrasovBuckholz extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/Brasov_Buckholz/";
    const SOURCE="OrganDefinitions/Brasov - Surround DEMO.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Brasov Buckholz (Demo - %s) 1.0.organ";
    const REVISIONS="";

    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIFFUSE,
        1=>self::RANKS_DIRECT,
        4=>self::RANKS_REAR];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>"DELETE", // Blower
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
                0=>["Group"=>"Single", "Name"=>"Stops", "Instance"=>13000, "SetID"=>1035],
                1=>["Group"=>"Single", "Name"=>"Wide", "Instance"=>13000, "SetID"=>1036],
                2=>[],
               ],
            6=>"DELETE", /* [
                0=>["SetID"=>1040], // Mixer
               ], */
            7=>[
                0=>["Group"=>"Simple", "Name"=>"Landscape", "Instance"=>11000, "SetID"=>1038],
                1=>[],
                2=>["Group"=>"Simple", "Name"=>"Portrait", "Instance"=>11000, "SetID"=>1039],
               ]
    ];

    protected $patchDivisions=[
            8=>["DivisionID"=>8, "Name"=>"Blower", "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    protected $patchStops=[
         124=>["StopID"=> 124, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>800],
        -111=>["StopID"=>-111, "DivisionID"=>1, "Name"=>"P Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -112=>["StopID"=>-112, "DivisionID"=>2, "Name"=>"UW Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -113=>["StopID"=>-113, "DivisionID"=>3, "Name"=>"RW key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -114=>["StopID"=>-114, "DivisionID"=>4, "Name"=>"HW key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -114=>["StopID"=>-115, "DivisionID"=>4, "Name"=>"OW key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"P Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"UW Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"RW key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -124=>["StopID"=>-124, "DivisionID"=>4, "Name"=>"HW key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -125=>["StopID"=>-125, "DivisionID"=>4, "Name"=>"OW key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[
          8=>["Noise"=>"StopOn",  "GroupID"=>900, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>900, "StopIDs"=>[]],
        950=>["Noise"=>"Ambient", "GroupID"=>800, "StopIDs"=>[124]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-111]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-112]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-113]],
        984=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-114]],
        985=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-115]],
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-121]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-122]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-123]],
        994=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-124]],
        995=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-125]],
    ];
    
    protected $patchEnclosures=[
        /*
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
            "GroupIDs"=>[304], "AmpMinimumLevel"=>1],*/
        988=>["Name"=>"UW", 
            "Panels"=>[1=>[985, NULL, 985], 4=>[984, NULL, 984], 5=>[987, 987, NULL], 7=>[986, NULL, 986]], 
            "GroupIDs"=>[201,203,204], "AmpMinimumLevel"=>20],
        998=>["Name"=>"RW",
            "Panels"=>[1=>[995, NULL, 995], 3=>[994, NULL, 994], 5=>[997, 997, NULL], 7=>[996, NULL, 996]], 
            "GroupIDs"=>[301,303,304], "AmpMinimumLevel"=>20], 
    ];

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=1721;
        return parent::createOrgan($hwdata);
    }
    
    /* private function ccLinks(string $type, int $ccid, array &$result) : void {
        static $types=[
            "S"=>"DestControlID",
            "D"=>"SourceControlID"
        ];
        $attribute=$types[$type];
        $links=$this->hwdata->continuousControlLink($ccid);
        if (isset($links[$type])) {
            foreach($links[$type] as $link) {
                if (isset($link[$attribute])) {
                    if (!isset($result[$link[$attribute]])) {
                        $result[$link[$attribute]]=TRUE;
                        $this->ccLinks($type, $link[$attribute], $result);
                    }
                }
            }
        }
    }
    
    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        echo "Enclosure: ", $data["EnclosureID"], "\n";
        $ctrlid=$data["ShutterPositionContinuousControlID"];
        $links=[];
        $this->ccLinks("D", $ctrlid, $links);
        $hasimage=FALSE;
        $links[$ctrlid]=TRUE;
        foreach($links as $linkid=>$dummy) {
            $cc=$this->hwdata->continuousControl($linkid);
            if (isset($cc["ImageSetInstanceID"]) &&
                    !empty($cc["ImageSetInstanceID"])) {
                $instance=$this->hwdata->imageSetInstance($cc["ImageSetInstanceID"]);
                echo "Instance=", $cc["ImageSetInstanceID"], " page=", $instance["DisplayPageID"], "\n";
                $hasimage=TRUE;
            }
        }
        if (!$hasimage) {
            echo "No image[s] for enclosure\n";
        }
    } */

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
        //$hwdata["ReleaseCrossfadeLengthMs"]=100;
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the import
     */
    public static function BrasovBuckholz(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new BrasovBuckholz(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getManual(4)->NumberOfLogicalKeys=73;
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            self::BrasovBuckholz(
                    [
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "6ch");
        }
    }
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\SP\ErrorHandler");
BrasovBuckholz::BrasovBuckholz();