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
 * Import Nitra Demo
 * 
 * @todo: Cuculus, short octave, etc; Keys on alternate console layout
 */

class Nitra extends PGOrgan {

    const ROOT="/GrandOrgue/Organs/Nitra/";
    const ODF="Nitra (demo).Organ_Hauptwerk_xml";
    const COMMENTS=
              "Nitra, Katedrála sv. Emeráma, Slovakia (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/nitra/\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Nitra (demo - %s) 1.0.organ";
    
    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    
    public $positions=[];

    public $patchDivisions=[
            6=>["DivisionID"=>7, "Name"=>"Key Actions"],
            7=>["DivisionID"=>8, "Name"=>"Stop Actions"],
            8=>["DivisionID"=>9, "Name"=>"Ambient"]
        ];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1000]
               ],
            2=>"DELETE", // Alt console - not quite right!
            3=>[
                0=>["Group"=>"Left", "Name"=>"1",   "SetID"=>3000],
                1=>["Group"=>"Left", "Name"=>"2",   "SetID"=>3200],
                2=>["Group"=>"Left", "Name"=>"3",   "SetID"=>3400],
               ],
            4=>[
                0=>["Group"=>"Right", "Name"=>"1",  "SetID"=>4000],
                1=>["Group"=>"Right", "Name"=>"2",  "SetID"=>4200],
                2=>["Group"=>"Right", "Name"=>"3",  "SetID"=>4400],
               ],
            5=>[
                0=>["Group"=>"Simple", "Name"=>"1", "SetID"=>100087],
                1=>["Group"=>"Simple", "Name"=>"2", "SetID"=>100127],
                2=>["Group"=>"Simple", "Name"=>"3", "SetID"=>100167],
               ],
            6=>[
                0=>["SetID"=>6000],
               ],
            7=>[
                0=>["SetID"=>7000]
               ]
    ];

    public $patchTremulants=[
            1=>["ControllingSwitchID"=>34, "Type"=>"Switched", "Name"=>"RP Tremulant", "DivisionID"=>3],
    ];
    
    public $patchEnclosures=[
          101=>["EnclosureID"=>101, "Name"=>"Close Ped",    "Panels"=>[60=>500], "GroupIDs"=>[101], "AmpMinimumLevel"=>0],
          102=>["EnclosureID"=>102, "Name"=>"Front Ped",    "Panels"=>[60=>510], "GroupIDs"=>[102], "AmpMinimumLevel"=>0],
          103=>["EnclosureID"=>103, "Name"=>"Rear Ped",     "Panels"=>[60=>520], "GroupIDs"=>[103], "AmpMinimumLevel"=>0],
          201=>["EnclosureID"=>201, "Name"=>"Close RP",     "Panels"=>[60=>501], "GroupIDs"=>[201], "AmpMinimumLevel"=>0],
          202=>["EnclosureID"=>202, "Name"=>"Front RP",     "Panels"=>[60=>511], "GroupIDs"=>[202], "AmpMinimumLevel"=>0],
          203=>["EnclosureID"=>203, "Name"=>"Rear RP",      "Panels"=>[60=>521], "GroupIDs"=>[203], "AmpMinimumLevel"=>0],
          301=>["EnclosureID"=>301, "Name"=>"Close HW",     "Panels"=>[60=>502], "GroupIDs"=>[301], "AmpMinimumLevel"=>0],
          302=>["EnclosureID"=>302, "Name"=>"Front HW",     "Panels"=>[60=>512], "GroupIDs"=>[302], "AmpMinimumLevel"=>0],
          303=>["EnclosureID"=>303, "Name"=>"Rear HW",      "Panels"=>[60=>522], "GroupIDs"=>[303], "AmpMinimumLevel"=>0],
          609=>["EnclosureID"=>609, "Name"=>"Key Actions",  "Panels"=>[60=>571], "GroupIDs"=>[601,602,603,604], "AmpMinimumLevel"=>0],
          709=>["EnclosureID"=>709, "Name"=>"Stop Actions", "Panels"=>[60=>572], "GroupIDs"=>[701,702,703,704], "AmpMinimumLevel"=>0],
          801=>["EnclosureID"=>801, "Name"=>"Blower",       "Panels"=>[60=>573], "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>0],
          901=>["EnclosureID"=>901, "Name"=>"Close",        "Panels"=>[60=>540], "GroupIDs"=>[101,201,301,401,501], "AmpMinimumLevel"=>0],
          902=>["EnclosureID"=>902, "Name"=>"Front",        "Panels"=>[60=>541], "GroupIDs"=>[102,202,302,402,502], "AmpMinimumLevel"=>0],
          903=>["EnclosureID"=>903, "Name"=>"Rear",         "Panels"=>[60=>542], "GroupIDs"=>[103,203,303,403,503], "AmpMinimumLevel"=>0],
    ];
    
    protected $patchStops=[
          83=>["StopID"=>   83, "DivisionID"=>1, "Name"=>"P Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          84=>["StopID"=>   84, "DivisionID"=>1, "Name"=>"P Key Off",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          85=>["StopID"=>   85, "DivisionID"=>2, "Name"=>"RP Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          86=>["StopID"=>   86, "DivisionID"=>2, "Name"=>"RP Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          87=>["StopID"=>   87, "DivisionID"=>3, "Name"=>"HW Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          88=>["StopID"=>   88, "DivisionID"=>3, "Name"=>"HW Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          90=>["StopID"=>   90, "DivisionID"=>1, "Name"=>"Ambient (close)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>801],
        1090=>["StopID"=> 1090, "DivisionID"=>1, "Name"=>"Ambient (front)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>802],
        2090=>["StopID"=> 2090, "DivisionID"=>1, "Name"=>"Ambient (rear)",      "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>803],
          91=>["StopID"=>   91, "DivisionID"=>1, "Name"=>"Main Motor (close)",  "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>801],
        1091=>["StopID"=> 1091, "DivisionID"=>1, "Name"=>"Main Motor (front)",  "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>802],
        2091=>["StopID"=> 2091, "DivisionID"=>1, "Name"=>"Main Motor (rear)",   "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>803],
          92=>["StopID"=>   92, "DivisionID"=>1, "Name"=>"Vogelsang (close)",   "ControllingSwitchID"=>36,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>801],
        1092=>["StopID"=> 1092, "DivisionID"=>1, "Name"=>"Vogelsang (front)",   "ControllingSwitchID"=>36,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>802],
        2092=>["StopID"=> 2092, "DivisionID"=>1, "Name"=>"Vogelsang (rear)",    "ControllingSwitchID"=>36,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>803],
          93=>["StopID"=>   93, "DivisionID"=>1, "Name"=>"Cymbelstern (close)", "ControllingSwitchID"=>37,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>801],
        1093=>["StopID"=> 1093, "DivisionID"=>1, "Name"=>"Cymbelstern (front)", "ControllingSwitchID"=>37,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>802],
        2093=>["StopID"=> 2093, "DivisionID"=>1, "Name"=>"Cymbelstern (rear)",  "ControllingSwitchID"=>37,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>803],
          94=>["StopID"=>   94, "DivisionID"=>1, "Name"=>"Cuculus (close)",     "ControllingSwitchID"=>35,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>801],
        1094=>["StopID"=> 1094, "DivisionID"=>1, "Name"=>"Cuculus (front)",     "ControllingSwitchID"=>35,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>802],
        2094=>["StopID"=> 2094, "DivisionID"=>1, "Name"=>"Cuculus (rear)",      "ControllingSwitchID"=>35,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>803],
          95=>["StopID"=>   95, "DivisionID"=>1, "Name"=>"Pauke",               "ControllingSwitchID"=>38,   "Engaged"=>"N", "Disused"=>TRUE], // No sample ??
          96=>["StopID"=>   96, "DivisionID"=>1, "Name"=>"Kurtze octav",        "ControllingSwitchID"=>39,   "Engaged"=>"N", "Disused"=>TRUE], // No sample ??
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
        
        1080=>["Noise"=>"Ambient",    "GroupID"=>902, "StopIDs"=>[]],
        1081=>["Noise"=>"StopOn",     "GroupID"=>802, "StopIDs"=>[]],
        1083=>["Noise"=>"StopOff",    "GroupID"=>802, "StopIDs"=>[]],
        1083=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[83]],
        1084=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[84]],
        1085=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[85]],
        1086=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[86]],
        1087=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[87]],
        1088=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[88]],

        2080=>["Noise"=>"Ambient",    "GroupID"=>903, "StopIDs"=>[]],
        2081=>["Noise"=>"StopOn",     "GroupID"=>803, "StopIDs"=>[]],
        2083=>["Noise"=>"StopOff",    "GroupID"=>803, "StopIDs"=>[]],
        2083=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[83]],
        2084=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[84]],
        2085=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[85]],
        2086=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[86]],
        2087=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[87]],
        2088=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[88]],
    ];
    
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
    
    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Noise {
        $method=$isattack ? "configureAttack" : "configureRelease";
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $type=$this->hwdata->rank($hwdata["RankID"])["Noise"];
        $midi=$hwdata["NormalMIDINoteNumber"];
        if ($type=="Ambient") {
            $stop=$this->getStop(($isattack ? +1 : -1) * ($hwdata["RankID"] + $midi-26));
            if ($stop!==NULL) {
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
   
    public static function Nitra(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=\GOClasses\Ambience::$blankloop
                ="./OrganInstallationPackages/002516/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new Nitra(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
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
            }
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::Nitra(
                    [1=>"(close)"],
                    "close");
            self::Nitra( 
                    [2=>"(front)"],
                    "front");
            self::Nitra(
                    [3=>"(rear)"],
                    "rear");
            self::Nitra( 
                    [1=>"(close)", 2=>"(front)", 3=>"(rear)"],
                    "surround");
        }
    }   
    
}
Nitra::Nitra();