<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\PG;
require_once __DIR__ . "/PGOrgan.php";

/**
 * Import Erfurt Predigerkirche Demo
 */

class ErfurtPredigerkirche extends PGOrgan {

    const ROOT="/GrandOrgue/Organs/PG/Erfurt Predigerkirche/";
    const ODF="Erfurt Predigerkirche (demo).Organ_Hauptwerk_xml";
    const COMMENTS=
              "Predigerkirche in Erfurt, Germany (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/goch/\n"
            . "\n"
            . "1.1 Cross fades corrected for GO 3.14\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Erfurt Predigerkirche (demo - %s) 1.1.organ";
    
    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    
    public $positions=[];

    public $patchDivisions=[
            7=>["DivisionID"=>7, "Name"=>"Key Actions"],
            8=>["DivisionID"=>8, "Name"=>"Stop Actions"],
            9=>["DivisionID"=>9, "Name"=>"Ambient"]
        ];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1000]
               ],
            2=>[
                0=>["Group"=>"Left", "Name"=>"1",   "SetID"=>2000],
                1=>["Group"=>"Left", "Name"=>"2",   "SetID"=>2200],
                2=>["Group"=>"Left", "Name"=>"3",   "SetID"=>2400],
               ],
            3=>[
                0=>["Group"=>"Right", "Name"=>"1",  "SetID"=>3000],
                1=>["Group"=>"Right", "Name"=>"2",  "SetID"=>3200],
                2=>["Group"=>"Right", "Name"=>"3",  "SetID"=>3400],
               ],
            4=>[
                0=>["Group"=>"Simple", "Name"=>"1", "SetID"=>9476],
                1=>["Group"=>"Simple", "Name"=>"2", "SetID"=>9543],
                2=>["Group"=>"Simple", "Name"=>"3", "SetID"=>9610],
               ],
            5=>"DELETE", // Crescendo
            6=>[
                0=>["SetID"=>6000], // Settings
               ],
            7=>[
                0=>["SetID"=>7000], // Info
               ]
    ];

    public $patchTremulants=[
            1=>["ControllingSwitchID"=>63, "Type"=>"Switched", "DivisionID"=>3],
            2=>["ControllingSwitchID"=>64, "Type"=>"Switched", "DivisionID"=>4],
    ];
    
    public $patchEnclosures=[
            1=>["Panels"=>[10=>11, 40=>11936, 41=>11936, 42=>11936], "GroupIDs"=>[301,302,303],  "AmpMinimumLevel"=>20],
          101=>["EnclosureID"=>101, "Name"=>"Close Ped",    "Panels"=>[60=>500], "GroupIDs"=>[101], "AmpMinimumLevel"=>1],
          102=>["EnclosureID"=>102, "Name"=>"Front Ped",    "Panels"=>[60=>510], "GroupIDs"=>[102], "AmpMinimumLevel"=>1],
          103=>["EnclosureID"=>103, "Name"=>"Rear Ped",     "Panels"=>[60=>520], "GroupIDs"=>[103], "AmpMinimumLevel"=>1],
          201=>["EnclosureID"=>201, "Name"=>"Close GO",     "Panels"=>[60=>501], "GroupIDs"=>[201], "AmpMinimumLevel"=>1],
          202=>["EnclosureID"=>202, "Name"=>"Front GO",     "Panels"=>[60=>511], "GroupIDs"=>[202], "AmpMinimumLevel"=>1],
          203=>["EnclosureID"=>203, "Name"=>"Rear GO",      "Panels"=>[60=>521], "GroupIDs"=>[203], "AmpMinimumLevel"=>1],
          301=>["EnclosureID"=>301, "Name"=>"Close R",      "Panels"=>[60=>502], "GroupIDs"=>[301], "AmpMinimumLevel"=>1],
          302=>["EnclosureID"=>302, "Name"=>"Front R",      "Panels"=>[60=>512], "GroupIDs"=>[302], "AmpMinimumLevel"=>1],
          303=>["EnclosureID"=>303, "Name"=>"Rear R",       "Panels"=>[60=>522], "GroupIDs"=>[303], "AmpMinimumLevel"=>1],
          401=>["EnclosureID"=>401, "Name"=>"Close SL",     "Panels"=>[60=>503], "GroupIDs"=>[401], "AmpMinimumLevel"=>1],
          402=>["EnclosureID"=>402, "Name"=>"Front SL",     "Panels"=>[60=>513], "GroupIDs"=>[402], "AmpMinimumLevel"=>1],
          403=>["EnclosureID"=>403, "Name"=>"Rear SL",      "Panels"=>[60=>523], "GroupIDs"=>[403], "AmpMinimumLevel"=>1],
          709=>["EnclosureID"=>909, "Name"=>"Key Actions",  "Panels"=>[60=>571], "GroupIDs"=>[701,702,803], "AmpMinimumLevel"=>1],
          809=>["EnclosureID"=>809, "Name"=>"Stop Actions", "Panels"=>[60=>572], "GroupIDs"=>[801,802,803], "AmpMinimumLevel"=>1],
          909=>["EnclosureID"=>909, "Name"=>"Blower",       "Panels"=>[60=>573], "GroupIDs"=>[901,902,903], "AmpMinimumLevel"=>1],
          901=>["EnclosureID"=>901, "Name"=>"Close",        "Panels"=>[60=>540], "GroupIDs"=>[101,201,301,401], "AmpMinimumLevel"=>1],
          902=>["EnclosureID"=>902, "Name"=>"Front",        "Panels"=>[60=>541], "GroupIDs"=>[102,202,302,402], "AmpMinimumLevel"=>1],
          903=>["EnclosureID"=>903, "Name"=>"Rear",         "Panels"=>[60=>542], "GroupIDs"=>[103,203,303,403], "AmpMinimumLevel"=>1],
    ];
    
    protected $patchStops=[
          80=>["StopID"=>   80, "DivisionID"=>1, "Name"=>"Noises (close)",      "ControllingSwitchID"=>99,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
        1080=>["StopID"=> 1080, "DivisionID"=>1, "Name"=>"Noises (front)",      "ControllingSwitchID"=>99,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
        2080=>["StopID"=> 2080, "DivisionID"=>1, "Name"=>"Noises (rear)",       "ControllingSwitchID"=>99,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
          83=>["StopID"=>   83, "DivisionID"=>1, "Name"=>"P Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          84=>["StopID"=>   84, "DivisionID"=>1, "Name"=>"P Key Off",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          85=>["StopID"=>   85, "DivisionID"=>2, "Name"=>"HW Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          86=>["StopID"=>   86, "DivisionID"=>2, "Name"=>"HW Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          87=>["StopID"=>   87, "DivisionID"=>3, "Name"=>"SW Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          88=>["StopID"=>   88, "DivisionID"=>3, "Name"=>"SW Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          89=>["StopID"=>   89, "DivisionID"=>4, "Name"=>"RP Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          90=>["StopID"=>   90, "DivisionID"=>4, "Name"=>"RP Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];

    protected $patchRanks=[
          80=>["Noise"=>"Ambient",    "GroupID"=>901, "StopIDs"=>[]],
          81=>["Noise"=>"StopOn",     "GroupID"=>801, "StopIDs"=>[]],
          82=>["Noise"=>"StopOff",    "GroupID"=>801, "StopIDs"=>[]],
          83=>["Noise"=>"KeyOn",      "GroupID"=>701, "StopIDs"=>[83]],
          84=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[84]],
          85=>["Noise"=>"KeyOn",      "GroupID"=>701, "StopIDs"=>[85]],
          86=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[86]],
          87=>["Noise"=>"KeyOn",      "GroupID"=>701, "StopIDs"=>[87]],
          88=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[88]],
          89=>["Noise"=>"KeyOn",      "GroupID"=>701, "StopIDs"=>[89]],
          90=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[90]],
        
        1080=>["Noise"=>"Ambient",    "GroupID"=>902, "StopIDs"=>[]],
        1081=>["Noise"=>"StopOn",     "GroupID"=>802, "StopIDs"=>[]],
        1082=>["Noise"=>"StopOff",    "GroupID"=>802, "StopIDs"=>[]],
        1083=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[83]],
        1084=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[84]],
        1085=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[85]],
        1086=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[86]],
        1087=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[87]],
        1088=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[88]],
        1089=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[89]],
        1090=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[90]],

        2080=>["Noise"=>"Ambient",    "GroupID"=>903, "StopIDs"=>[]],
        2081=>["Noise"=>"StopOn",     "GroupID"=>803, "StopIDs"=>[]],
        2082=>["Noise"=>"StopOff",    "GroupID"=>803, "StopIDs"=>[]],
        2083=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[83]],
        2084=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[84]],
        2085=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[85]],
        2086=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[86]],
        2087=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[87]],
        2088=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[88]],
        2089=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[89]],
        2090=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[90]],
    ];
    
    public function patchData(\HWClasses\HWData $hwd): void {
        $index=10000;
        foreach ($hwd->stops() as $stopid=>$stop) {
            $nodes=($stopid<10 ? 29 : 61);
            $increment=($stopid==5 ? 12 : 0);
            foreach([0, 1000, 2000] as $baseid) {
                $rankid=$baseid + $stopid;
                $this->patchStopRanks[$index++]=[
                    "StopID"=>$stopid, 
                    "RankID"=>$rankid,
                    "MIDINoteNumOfFirstMappedDivisionInputNode"=>36,
                    "NumberOfMappedDivisionInputNodes"=>$nodes,
                        "MIDINoteNumIncrementFromDivisionToRank"=>$increment];
            }
        } 
        parent::patchData($hwd);
        /* $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            switch ($instance["DisplayPageID"]) {
                case 6:
                    echo ($instanceID=$instance["ImageSetInstanceID"]), " ",
                         $instance["Name"], "\n";
                    foreach ($this->hwdata->switches() as $switch) {
                        if ($switch["Disp_ImageSetInstanceID"]==$instance)
                            echo $switch["SwitchID"], " ",
                                 $switch["Name"], "\n";
                    }
            }
        } 
        exit(); */
    }
    
    public function configureKeyboardKey(\GOClasses\Manual $manual, $switchid, $midikey): void {
        $switch=$this->hwdata->switch($switchid);
        if (!empty($switch["Disp_ImageSetIndexEngaged"])) 
            parent::configureKeyboardKey($manual, $switchid, $midikey);
    }

    public function createPanel($hwdata): ?\GOClasses\Panel {
        $pageid=$hwdata["PageID"];
        $hwdata["AlternateConsoleScreenLayout0_Include"]="Y";
        foreach([0,1,2,3] as $l) {
            if (isset($hwdata["AlternateConsoleScreenLayout{$l}_Include"])
                    && $hwdata["AlternateConsoleScreenLayout{$l}_Include"]=="Y") {
                $paneldata=array_merge($hwdata, $hwdata[$l]);
                $paneldata["PanelID"]=(10*$pageid)+$l;
                $panel=parent::createPanel($paneldata);
                $this->configurePanelImage($panel, $paneldata);
            }
        }
        return NULL;
    }
    
    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        $hwdata["ConditionSwitchID"]=$hwdata["ConditionSwitchID"] % 1000;
        if ($hwdata["ConditionSwitchID"]>200) $hwdata["ConditionSwitchID"]-=200;
        return parent::createCoupler($hwdata);
    }

    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Noise {
        $method=$isattack ? "configureAttack" : "configureRelease";
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $type=$this->hwdata->rank($hwdata["RankID"])["Noise"];
        if ($type=="Ambient") {
            $stop=$this->getStop($hwdata["RankID"]);
            $midi=$hwdata["NormalMIDINoteNumber"];
            if ($isattack && $stop!==NULL && $midi==37) { // Blower!
                $ambience=$stop->Ambience();
                $ambience->LoadRelease="Y";
                unset($hwdata["LoopCrossfadeLengthInSrcSampleMs"]);
                $this->$method($hwdata, $ambience);
            }
        }
        else {
            unset($hwdata["NormalMIDINoteNumber"]); // Use filename
            unset($hwdata["Pitch_NormalMIDINoteNumber"]);
            $midi=$hwdata["PipeID"] % 100;
            $stop=$this->getSwitchNoise(($isattack ? +1 : -1)*($midi*10 + 1 + intval($hwdata["RankID"]/1000)));
            if ($stop!==NULL) {
                $stop->Function="And";
                $stop->SwitchCount=1;
                $noise=$stop->Noise();
                $this->$method($hwdata, $noise);
            }
        }
        return NULL;
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $pipemidi=$this->pipePitchMidi($hwdata);
        unset($hwdata["LoadSampleRange_EndPositionValue"]);
        return parent::processSample($hwdata, $isattack);
    }
   
    public static function ErfurtPredigerkirche(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="OrganInstallationPackages/002524/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new ErfurtPredigerkirche(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
                unset($stop->Rank004PipeCount);
                unset($stop->Rank005PipeCount);
                unset($stop->Rank006PipeCount);
                unset($stop->Rank001FirstAccessibleKeyNumber);
                unset($stop->Rank002FirstAccessibleKeyNumber);
                unset($stop->Rank003FirstAccessibleKeyNumber);
                unset($stop->Rank004FirstAccessibleKeyNumber);
                unset($stop->Rank005FirstAccessibleKeyNumber);
                unset($stop->Rank006FirstAccessibleKeyNumber);
                unset($stop->Rank001FirstPipeNumber);
                unset($stop->Rank002FirstPipeNumber);
                unset($stop->Rank003FirstPipeNumber);
                unset($stop->Rank004FirstPipeNumber);
                unset($stop->Rank005FirstPipeNumber);
                unset($stop->Rank006FirstPipeNumber);
            }
            /* foreach([80, 1080, 2080] as $stopid)
                echo $hwi->getStop($stopid); */
            $hwi->saveODF(sprintf(self::TARGET, $target));
            echo $hwi->getOrgan()->ChurchName, "\n";
        }
        else {
            self::ErfurtPredigerkirche(
                    [1=>"(close)"],
                    "close");
            self::ErfurtPredigerkirche( 
                    [2=>"(front)"],
                    "front");
            self::ErfurtPredigerkirche(
                    [3=>"(rear)"],
                    "rear");
            self::ErfurtPredigerkirche( 
                    [1=>"(close)", 2=>"(front)", 3=>"(rear)"],
                    "surround");
        }
    }   
    
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\PG\ErrorHandler");
ErfurtPredigerkirche::ErfurtPredigerkirche();