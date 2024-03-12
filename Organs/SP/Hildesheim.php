<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\SP;
require_once __DIR__ . "/SPOrganV2.php";

/**
 * Import Sonus Paradisi Hildesheim Cathedral: Echevarria organ (1772)
 * 
 * @author andrew e hall
 */
class Hildesheim extends SPOrganV2 {
    const ROOT="/GrandOrgue/Organs/SP/Hildesheim/";
    const VERSION="1.0";
    const REVISIONS="";
    
    const RANKS_DIRECT=1;
    const RANKS_DIFFUSE=2;
    const RANKS_DISTANT=3;
    const RANKS_REAR=4;
    
    protected ?int $releaseCrossfadeLengthMs=NULL;
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,   9=>self::RANKS_DIRECT,
        1=>self::RANKS_DIFFUSE,  7=>self::RANKS_DIFFUSE,
        4=>self::RANKS_REAR,     8=>self::RANKS_REAR
    ];
    
    public $positions=[];
    public static $singleRelease=FALSE;
    protected $switchGroups=[801, 802, 803, 804];
    
    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>[
                0=>["Group"=>"Simple", "Name"=>"Landscape", "Instance"=>11000, "SetID"=>1036],
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
                0=>["Name"=>"Mixer", "Instance"=>1000, "SetID"=>1035],
               ],
            6=>[
                0=>["Name"=>"Single", "Instance"=>13000, "SetID"=>1038],
               ],
    ];

    protected $patchDivisions=[
            6=>["DivisionID"=>6,  "Name"=>"Blower"],
            /* 7=>["DivisionID"=>7,  "Name"=>"Tracker"],
            8=>["DivisionID"=>8,  "Name"=>"Stop"],
            9=>["DivisionID"=>9,  "Name"=>"Tambores"],
           10=>["DivisionID"=>10, "Name"=>"Paxaros"] */
    ];

    protected $patchTremulants=[
        45=>["Type"=>"Wave",  "DivisionID"=>2, "GroupIDs"=>[201,202,204]],
        71=>["Type"=>"Wave",  "DivisionID"=>5, "GroupIDs"=>[401,402,404]],
        55=>["Type"=>"Wave",  "DivisionID"=>5, "GroupIDs"=>[501,502,504]],
    ];

    protected $patchEnclosures=[
        901=>["Panels"=>[5=>[800]], "EnclosureID"=>901, "Name"=>"Direct",
            "GroupIDs"=>[101,201,301,401,501,601], "AmpMinimumLevel"=>1],
        902=>["Panels"=>[5=>[810]], "EnclosureID"=>903,"Name"=>"Diffuse",
            "GroupIDs"=>[102,202,302,402,502,602], "AmpMinimumLevel"=>1],
        904=>["Panels"=>[5=>[820]], "EnclosureID"=>904,"Name"=>"Rear",
            "GroupIDs"=>[104,204,304,404,504,604], "AmpMinimumLevel"=>1],

        906=>["Panels"=>[5=>[1691]], "EnclosureID"=>906,"Name"=>"Blower",
            "GroupIDs"=>[601,602,604], "AmpMinimumLevel"=>1],
        
        //909=>["Panels"=>[5=>[1693]], "EnclosureID"=>909,"Name"=>"Tambores",
        //    "GroupIDs"=>[901,902,904], "AmpMinimumLevel"=>1],
        //910=>["Panels"=>[5=>[1694]], "EnclosureID"=>910,"Name"=>"Paxaros",
        //    "GroupIDs"=>[1001,1002,1004], "AmpMinimumLevel"=>1],

        998=>["Panels"=>[2=>[988, NULL, 988], 4=>[985, NULL, 985], 6=>[986, 986]], 
            "GroupIDs"=>[501,502,504], "AmpMinimumLevel"=>20], // Echo
   ];

    protected $patchStops=[
        -101=>["StopID"=>-101, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>999, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>601],
        -102=>["StopID"=>-102, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>999, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>602],
        -104=>["StopID"=>-104, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>999, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>604],
        /* -111=>["StopID"=>-110, "DivisionID"=>1, "Name"=>"P Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "GCState"=>1],
        -112=>["StopID"=>-111, "DivisionID"=>2, "Name"=>"1 Key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "GCState"=>1],
        -113=>["StopID"=>-112, "DivisionID"=>3, "Name"=>"2 key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "GCState"=>1],
        -114=>["StopID"=>-113, "DivisionID"=>4, "Name"=>"3 key On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "GCState"=>1],
        -121=>["StopID"=>-121, "DivisionID"=>1, "Name"=>"P Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "GCState"=>1],
        -122=>["StopID"=>-122, "DivisionID"=>2, "Name"=>"1 Key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "GCState"=>1],
        -123=>["StopID"=>-123, "DivisionID"=>3, "Name"=>"2 key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "GCState"=>1],
        -124=>["StopID"=>-124, "DivisionID"=>4, "Name"=>"3 key Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "GCState"=>1],
        
           1=>["StopID"=>1,   "DivisionID"=>1, "Name"=>"Paxaros",  "ControllingSwitchID"=>19001, "Ambient"=>TRUE, "GroupID"=>1001],
          65=>["StopID"=>65,  "DivisionID"=>1, "Name"=>"Tambor D", "ControllingSwitchID"=>19065, "Ambient"=>TRUE, "GroupID"=>901],
          66=>["StopID"=>66,  "DivisionID"=>1, "Name"=>"Tambor A", "ControllingSwitchID"=>19066, "Ambient"=>TRUE, "GroupID"=>901],
         101=>["StopID"=>101, "DivisionID"=>1, "Name"=>"Paxaros",  "ControllingSwitchID"=>19001, "Ambient"=>TRUE, "GroupID"=>1002],
         165=>["StopID"=>165, "DivisionID"=>1, "Name"=>"Tambor D", "ControllingSwitchID"=>19065, "Ambient"=>TRUE, "GroupID"=>902],
         166=>["StopID"=>166, "DivisionID"=>1, "Name"=>"Tambor A", "ControllingSwitchID"=>19066, "Ambient"=>TRUE, "GroupID"=>902],
         201=>["StopID"=>201, "DivisionID"=>1, "Name"=>"Paxaros",  "ControllingSwitchID"=>19001, "Ambient"=>TRUE, "GroupID"=>1004],
         265=>["StopID"=>265, "DivisionID"=>1, "Name"=>"Tambor D", "ControllingSwitchID"=>19065, "Ambient"=>TRUE, "GroupID"=>904],
         266=>["StopID"=>266, "DivisionID"=>1, "Name"=>"Tambor A", "ControllingSwitchID"=>19066, "Ambient"=>TRUE, "GroupID"=>904], */
     ];
    
    protected $patchRanks=[
        999901=>["Noise"=>"Ambient", "GroupID"=>601, "StopIDs"=>[-101]],
        999911=>["Noise"=>"Ambient", "GroupID"=>602, "StopIDs"=>[-102]],
        999941=>["Noise"=>"Ambient", "GroupID"=>604, "StopIDs"=>[-104]],
        
        /* 999903=>["RankID"=>999903, "Noise"=>"Ambient", "GroupID"=>0, "StopIDs"=>[ 65, 66]],
        999913=>["RankID"=>999913, "Noise"=>"Ambient", "GroupID"=>0, "StopIDs"=>[165,166]],
        999943=>["RankID"=>999943, "Noise"=>"Ambient", "GroupID"=>0, "StopIDs"=>[265,266]],
        
        999904=>["RankID"=>999904, "Noise"=>"Ambient", "GroupID"=>0, "StopIDs"=>[  1]],
        999914=>["RankID"=>999914, "Noise"=>"Ambient", "GroupID"=>0, "StopIDs"=>[101]],
        999944=>["RankID"=>999944, "Noise"=>"Ambient", "GroupID"=>0, "StopIDs"=>[201]],

        999800=>["Noise"=>"StopOff", "GroupID"=>801, "StopIDs"=>[]],
        999810=>["Noise"=>"StopOff", "GroupID"=>802, "StopIDs"=>[]],
        999840=>["Noise"=>"StopOff", "GroupID"=>803, "StopIDs"=>[]],
        999900=>["Noise"=>"StopOn",  "GroupID"=>801, "StopIDs"=>[]],
        999910=>["Noise"=>"StopOn",  "GroupID"=>802, "StopIDs"=>[]],
        999940=>["Noise"=>"StopOn",  "GroupID"=>803, "StopIDs"=>[]],

        998602=>["Noise"=>"KeyOn",   "GroupID"=>701, "StopIDs"=>[-111]],
        998612=>["Noise"=>"KeyOn",   "GroupID"=>702, "StopIDs"=>[-111]],
        998642=>["Noise"=>"KeyOn",   "GroupID"=>704, "StopIDs"=>[-111]],
        998102=>["Noise"=>"KeyOn",   "GroupID"=>701, "StopIDs"=>[-112]],
        998112=>["Noise"=>"KeyOn",   "GroupID"=>702, "StopIDs"=>[-112]],
        998142=>["Noise"=>"KeyOn",   "GroupID"=>704, "StopIDs"=>[-112]],
        998202=>["Noise"=>"KeyOn",   "GroupID"=>701, "StopIDs"=>[-113]],
        998212=>["Noise"=>"KeyOn",   "GroupID"=>702, "StopIDs"=>[-113]],
        998242=>["Noise"=>"KeyOn",   "GroupID"=>704, "StopIDs"=>[-113]],
        998302=>["Noise"=>"KeyOn",   "GroupID"=>701, "StopIDs"=>[-114]],
        998312=>["Noise"=>"KeyOn",   "GroupID"=>702, "StopIDs"=>[-114]],
        998342=>["Noise"=>"KeyOn",   "GroupID"=>704, "StopIDs"=>[-114]],

        998652=>["Noise"=>"KeyOff",  "GroupID"=>701, "StopIDs"=>[-121]],
        998662=>["Noise"=>"KeyOff",  "GroupID"=>702, "StopIDs"=>[-121]],
        998692=>["Noise"=>"KeyOff",  "GroupID"=>704, "StopIDs"=>[-121]],
        998152=>["Noise"=>"KeyOff",  "GroupID"=>701, "StopIDs"=>[-122]],
        998162=>["Noise"=>"KeyOff",  "GroupID"=>702, "StopIDs"=>[-122]],
        998192=>["Noise"=>"KeyOff",  "GroupID"=>704, "StopIDs"=>[-122]],
        998252=>["Noise"=>"KeyOff",  "GroupID"=>701, "StopIDs"=>[-123]],
        998262=>["Noise"=>"KeyOff",  "GroupID"=>702, "StopIDs"=>[-123]],
        998292=>["Noise"=>"KeyOff",  "GroupID"=>704, "StopIDs"=>[-123]],
        998352=>["Noise"=>"KeyOff",  "GroupID"=>701, "StopIDs"=>[-124]],
        998362=>["Noise"=>"KeyOff",  "GroupID"=>702, "StopIDs"=>[-124]],
        998392=>["Noise"=>"KeyOff",  "GroupID"=>704, "StopIDs"=>[-124]], */
    ];

    public function import(): void {
        parent::import();
        
        foreach($this->getStops() as $stop) {
            unset($stop->Rank001PipeCount);
            unset($stop->Rank002PipeCount);
            unset($stop->Rank003PipeCount);
            unset($stop->Rank004PipeCount);
            unset($stop->Rank005PipeCount);
            unset($stop->Rank006PipeCount);
        }

        $this->getManual(1)->NumberOfLogicalKeys=
                 $this->getManual(1)->NumberOfAccessibleKeys=32;
        
        $this->addVirtualKeyboards(4, [1,2,3,4],[1,2,3,4]);
    }
    
    public function createOrgan(array $hwdata): \GOClasses\Organ {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 2:
                    echo $instance["DisplayPageID"], "\t",
                         ($instanceID=$instance["ImageSetInstanceID"]), "\t",
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
        $hwdata["Identification_UniqueOrganID"]=2718;
        return parent::createOrgan($hwdata);
    }

    public function configureImage(\GOClasses\GOObject $object, array $data, int $layout=0) : void {
        parent::configureImage($object, $data, $layout);
        $imagedata=$this->getImageData($data, $layout);
        $object->MouseRectWidth=$imagedata["ImageWidthPixels"];
        $object->MouseRectHeight=$imagedata["ImageHeightPixels"];
    }
    
    public function createStops(array $stopsdata) : void {
        parent::createStops($stopsdata);
        
        $switch=$this->getSwitch(999); // Blower
        $panel=$this->getPanel(50);
        $pe=$panel->GUIElement($switch);
        $this->configureImage($pe, ["SwitchID"=>1050], 0);
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
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        if (isset($data["Panels"])) {
            foreach ($data["Panels"] as $panelid=>$instances) {
                foreach ($instances as $layout=>$instanceid) {
                    if ($instanceid!==NULL && $this->hwdata->imageSetInstance($instanceid, TRUE)) {
                        $panel=$this->getPanel(($panelid*10)+$layout, FALSE);
                        if ($panel!==NULL) {
                            $pe=$this->getPanel(($panelid*10)+$layout)->GUIElement($enclosure);
                            $this->configureEnclosureImage($pe, ["InstanceID"=>$instanceid], $layout);
                        }
                    }
                }
            }
        }
    }
    
    protected function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        if (isset($data["StopID"])) $switchid=$data["StopID"];
        elseif (isset($data["CouplerID"])) $switchid=$data["CouplerID"];
        elseif (isset($data["TremulantID"])) $switchid=$data["TremulantID"];
        else return;
        if ($switchid<0) return;
        
        foreach($this->patchDisplayPages as $pageid=>$layouts) {
            if (!is_array($layouts)) continue;
            foreach($layouts as $layoutid=>$layout) {
                if (!isset($layout["Instance"])) continue;
                $id=$switchid+$layout["Instance"];
                if ($id==1050) {continue;}
                $instance=$this->hwdata->imageSetInstance($id, TRUE);
                if ($instance!==NULL 
                        && $this->hwdata->switch($id, TRUE)!==NULL
                        && $instance["DisplayPageID"]==$pageid) {
                    $panel=$this->getPanel(($pageid*10)+$layoutid, FALSE);
                    if ($panel!==NULL) {
                        $pe=$panel->GUIElement($switch);
                        $this->configureImage($pe, ["SwitchID"=>$id], $layoutid);
                    }
                }
            }
        }
    }
    
    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $rankdata=$this->patchRanks[$hwdata["RankID"]];
        
        if ($rankdata["Noise"]=="Ambient") {
            if (in_array($hwdata["PipeID"],[99066, 99166, 99466])) {
                $stopid=$rankdata["StopIDs"][1];
            }
            else {
                $stopid=$rankdata["StopIDs"][0];
            };
            
            $stop=$this->getStop($stopid);
            if ($stop!==NULL) {
                $ambience=$stop->Ambience();
                if ($isattack) {
                    $this->configureAttack($hwdata, $ambience);
                    $ambience->LoadRelease="Y";
                }
                else {
                    $this->configureRelease($hwdata, $ambience);
                    $ambience->LoadRelease="N";
                }
                return $ambience;
            }
        }
        else {
            $stopid=($rankdata["Noise"]=="StopOn" ? +1 : -1) * (100*($hwdata["PipeID"] % 100)+$rankdata["GroupID"]-800);
            $stop=$this->getSwitchNoise($stopid, FALSE);
            if ($stop!==NULL) {
                $noise=$stop->Noise();
                $this->configureAttack($hwdata, $noise);
                return $noise;
            }
        }
        return NULL;
    }
    
    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        if (self::$singleRelease && $hwdata["reltime"]>0) {return NULL;}
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
/*      if (isset($hwdata["AttackSelCriteria_HighestVelocity"])) {
            $pipe->AttackVelocity=$hwdata["AttackSelCriteria_HighestVelocity"];
        }
         if (isset($hwdata["AttackSelCriteria_MinTimeSincePrevPipeCloseMs"])) {
            $pipe->MaxTimeSinceLastReleasey=$hwdata["AttackSelCriteria_MinTimeSincePrevPipeCloseMs"];
        }*/
        return $pipe;
    }
    
}

class HildesheimDemo extends Hildesheim {
    const SOURCE=self::ROOT . "OrganDefinitions/Hildesheim, Beckerath Organ, Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Hildesheim, Beckerath Organ, Demo" . self::VERSION . ".organ";

    /**
     * Run the import
     */
    public static function Hildesheim(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$pedals=32;
        \GOClasses\Manual::$keys=58;
        if (sizeof($positions)>0) {
            $hwi=new HildesheimDemo(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.= " ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(self::TARGET, self::REVISIONS);
        }
        else {
                        self::Hildesheim(
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

HildesheimDemo::Hildesheim();