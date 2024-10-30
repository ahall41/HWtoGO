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
 * Import Sonus Paradisi Caen Demo to GrandOrgue
 * The mixer panel could not be completed as the HW ODF references
 * to the corresponding images are missing
 * 
 * @author andrew
 */
class Caen extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/Caen/";
    const SOURCE="OrganDefinitions/Caen St. Etienne, Cavaille-Coll, Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Caen St. Etienne, Cavaille-Coll, Demo (%s) 0.4.organ";
    
    protected string $root=self::ROOT;
    protected array  $rankpositions=[
        0=>self::RANKS_DIRECT,  4=>self::RANKS_REAR,
        1=>self::RANKS_DIRECT,  5=>self::RANKS_REAR,
        2=>self::RANKS_DIRECT,  6=>self::RANKS_REAR
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>"DELETE", // Blower - N/A
            3=>"DELETE", // Mixer - N/A
            4=>[
                0=>["Group"=>"Stops", "Name"=>"Portrait", "Instance"=>1300, "SetID"=>1037],
                1=>["Group"=>"Stops", "Name"=>"Wide",     "xInstance"=>1300, "SetID"=>1038],
               ],
            5=>[
                0=>["Group"=>"Left", "Name"=>"Landscape",  "Instance"=>12000, "SetID"=>1032],
                1=>[],
                2=>["Group"=>"Left", "Name"=>"Portrait",   "Instance"=>12000, "SetID"=>1034],
               ],
            6=>[
                0=>["Group"=>"Right", "Name"=>"Landscape", "Instance"=>12000, "SetID"=>1033],
                1=>[],
                2=>["Group"=>"Right", "Name"=>"Portrait",  "Instance"=>12000, "SetID"=>1035],
               ],
            7=>[
                0=>["Group"=>"Simple", "Name"=>"Landscape", "Instance"=>11000, "SetID"=>1039],
                1=>[],
                2=>["Group"=>"Simple", "Name"=>"Portrait",  "Instance"=>11000, "SetID"=>1040],
               ],
    ];

    private array $div2man=[
            1=>1, 2=>1, // Ped + Anches
            3=>2, 4=>2, // GO + Anches
            5=>3, 6=>3, // Pos + Anches
            7=>4, 8=>4, // Rec + Anches
    ];
    
    protected $patchDivisions=[
            9=>["DivisionID"=>9, "Name"=>"Noises", "Noise"=>TRUE]
    ];

    protected $patchKeyActions=[ 
        3=>"DELETE", // Anches couplers
        4=>"DELETE"
    ];
    
    protected $patchTremulants=[ // Or should they be switched?
        92=>["Type"=>"Synth", "DivisionID"=>4, "GroupIDs"=>[701,702,703,801,802,803]],
    ];

    protected $patchEnclosures=[
        998=>["Panels"=>[1=>[992], 4=>[997], 5=>[995,NULL,995], 7=>[993,NULL,993]], 
            "GroupIDs"=>[701,702,703,801,802,803], "AmpMinimumLevel"=>10], // Swell
    ];

    protected $patchStops=[
       +950=>["StopID"=>+950, "DivisionID"=>1, "Name"=>"Blower",      "ControllingSwitchID"=>950,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>900],
       -981=>["StopID"=>-981, "DivisionID"=>1, "Name"=>"Ped Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -982=>["StopID"=>-982, "DivisionID"=>2, "Name"=>"Ch Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -983=>["StopID"=>-983, "DivisionID"=>3, "Name"=>"Gt key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -984=>["StopID"=>-984, "DivisionID"=>4, "Name"=>"Sw key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -991=>["StopID"=>-991, "DivisionID"=>1, "Name"=>"Ped Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -992=>["StopID"=>-992, "DivisionID"=>2, "Name"=>"Ch Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -993=>["StopID"=>-993, "DivisionID"=>3, "Name"=>"GT key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
       -994=>["StopID"=>-994, "DivisionID"=>4, "Name"=>"SW key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[
          8=>["Noise"=>"StopOn",  "GroupID"=>900, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>900, "StopIDs"=>[]],
        950=>["Noise"=>"Ambient", "GroupID"=>900, "StopIDs"=>[+950]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-981]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-982]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-983]],
        984=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-984]],
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-991]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-992]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-993]],
        994=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-994]],
    ];

    public function configureKeyImage(?\GOClasses\GOObject $object, $keyImageset): void {}
    
    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        $hwdata["DivisionID"]=$this->div2man[$hwdata["DivisionID"]];
        return parent::createStop($hwdata);
    }
    
    public function createSwitchNoise(string $type, array $hwdata): void {
        if (isset($hwdata["DivisionID"]))
            $hwdata["DivisionID"]=$this->div2man[$hwdata["DivisionID"]];
        parent::createSwitchNoise($type, $hwdata);
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
    
    protected function configureAttack(array $hwdata, \GOClasses\Pipe $pipe): void {
        if ($pipe->AttackCount<0)
            parent::configureAttack($hwdata, $pipe);
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

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $hwdata): void {
        if (isset($hwdata["StopID"]) && $hwdata["StopID"]==950) {
            $pe70=$this->getPanel(70)->GUIElement($switch);
            $this->configureImage($pe70, ["SwitchID"=>1050]);
            $pe70->PositionX=1125;
            $pe70->PositionY=643;
            //$pe70->MouseRectWidth=160;
            $pe72=$this->getPanel(72)->GUIElement($switch);
            $this->configureImage($pe72, ["SwitchID"=>1050]);
            $pe72->PositionX=695;
            $pe72->PositionY=1080;
            //$pe72->MouseRectWidth=160;
        }
        else
            parent::configurePanelSwitchImages ($switch, $hwdata);
    }

    /**
     * Run the import
     */
    public static function Caen(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=56;
        if (sizeof($positions)>0) {
            $hwi=new Caen(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("Demo", "Demo $target", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::Caen(
                    [self::RANKS_DIRECT=>"Front"],
                    "Front");
            self::Caen(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
             self::Caen(
                    [self::RANKS_DIRECT=>"Front", self::RANKS_REAR=>"Rear"],
                    "Surround");
        }
    }
}
Caen::Caen();