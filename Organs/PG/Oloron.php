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
 * Import Oloron Demo
 * 
 * @todo: OG Coupler, Appelles etc
 *        NB. Requires UNPACKED .wav samples
 */

class Oloron extends PGOrgan {

    const ROOT="/GrandOrgue/Organs/Oloron/";
    const ODF="Oloron-Sainte-Marie.Organ_Hauptwerk_xml";
    const COMMENTS=
              "Oloron, Cathédrale Notre-Dame-de-l'Annonciation, France (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/nancy/\n"
            . "\n"
            . "1.1 wave based tremulant model\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Oloron-Sainte-Marie %s 0.2.organ";
    protected bool $switchedtremulants=FALSE;
    
    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    
    public $positions=[];

    public $patchDivisions=[
            6=>["DivisionID"=>6, "Name"=>"Key Actions"],
            7=>["DivisionID"=>7, "Name"=>"Stop Actions"],
            8=>["DivisionID"=>8, "Name"=>"Blower"]
        ];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1000,  "SwitchID"=>0]
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
                0=>["Group"=>"Simple", "Name"=>"1", "SetID"=>17678],
                1=>["Group"=>"Simple", "Name"=>"2", "SetID"=>17712],
                2=>["Group"=>"Simple", "Name"=>"3", "SetID"=>17746],
               ],
            5=>[
                0=>["SetID"=>5000],
               ],
            6=>[
                0=>["SetID"=>6000]
               ]
    ];

    public $patchEnclosures=[
            1=>["Panels"=>[10=>11], "GroupIDs"=>[301,302,303]],
          101=>["EnclosureID"=>101, "Name"=>"Front Ped",    "Panels"=>[50=>500], "GroupIDs"=>[101], "AmpMinimumLevel"=>0],
          102=>["EnclosureID"=>102, "Name"=>"Rear Ped",     "Panels"=>[50=>510], "GroupIDs"=>[102], "AmpMinimumLevel"=>0],
          103=>["EnclosureID"=>103, "Name"=>"Dry Ped",      "Panels"=>[50=>520], "GroupIDs"=>[103], "AmpMinimumLevel"=>0],
          201=>["EnclosureID"=>301, "Name"=>"Front GO",     "Panels"=>[50=>501], "GroupIDs"=>[201], "AmpMinimumLevel"=>0],
          202=>["EnclosureID"=>302, "Name"=>"Rear GO",      "Panels"=>[50=>511], "GroupIDs"=>[202], "AmpMinimumLevel"=>0],
          203=>["EnclosureID"=>303, "Name"=>"Dry GO",       "Panels"=>[50=>521], "GroupIDs"=>[203], "AmpMinimumLevel"=>0],
          301=>["EnclosureID"=>401, "Name"=>"Front Rec",    "Panels"=>[50=>502], "GroupIDs"=>[301], "AmpMinimumLevel"=>0],
          302=>["EnclosureID"=>402, "Name"=>"Rear Rec",     "Panels"=>[50=>512], "GroupIDs"=>[302], "AmpMinimumLevel"=>0],
          303=>["EnclosureID"=>403, "Name"=>"Dry Rec",      "Panels"=>[50=>522], "GroupIDs"=>[303], "AmpMinimumLevel"=>0],
          609=>["EnclosureID"=>609, "Name"=>"Key Actions",  "Panels"=>[50=>571], "GroupIDs"=>[601,602,603], "AmpMinimumLevel"=>0],
          709=>["EnclosureID"=>709, "Name"=>"Stop Actions", "Panels"=>[50=>572], "GroupIDs"=>[701,702,703], "AmpMinimumLevel"=>0],
          801=>["EnclosureID"=>801, "Name"=>"Ambience",     "Panels"=>[50=>573], "GroupIDs"=>[801,802,803], "AmpMinimumLevel"=>0],
          901=>["EnclosureID"=>901, "Name"=>"Front",        "Panels"=>[50=>540], "GroupIDs"=>[101,201,301,601,701,801], "AmpMinimumLevel"=>0],
          902=>["EnclosureID"=>902, "Name"=>"Rear",         "Panels"=>[50=>541], "GroupIDs"=>[102,202,302,602,702,802], "AmpMinimumLevel"=>0],
          903=>["EnclosureID"=>903, "Name"=>"Dry",          "Panels"=>[50=>542], "GroupIDs"=>[103,203,303,603,703,803], "AmpMinimumLevel"=>0],
    ];

    public $patchTremulants=[
            1=>["TremulantID"=>1, "ControllingSwitchID"=>33, "Name"=>"Tremblant", "Type"=>"Wave", "GroupIDs"=>[301,302,303]],
    ];
    
    public $patchKeyActions=[
            0=>["DestDivisionID"=>3],
            1=>["DestDivisionID"=>4],
            4=>"DELETE"
    ];
    
    protected $patchStops=[
          80=>["StopID"=>   80, "DivisionID"=>1, "Name"=>"Ambient (close)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
        1080=>["StopID"=> 1080, "DivisionID"=>1, "Name"=>"Ambient (front)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
        2080=>["StopID"=> 2080, "DivisionID"=>1, "Name"=>"Ambient (middle)",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
          81=>["StopID"=>   81, "DivisionID"=>1, "Name"=>"Main Motor (close)",  "ControllingSwitchID"=>101, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
        1081=>["StopID"=> 1081, "DivisionID"=>1, "Name"=>"Main Motor (front)",  "ControllingSwitchID"=>101, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
        2081=>["StopID"=> 2081, "DivisionID"=>1, "Name"=>"Main Motor (rear)",   "ControllingSwitchID"=>101, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
          83=>["StopID"=>   83, "DivisionID"=>1, "Name"=>"P Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          84=>["StopID"=>   84, "DivisionID"=>1, "Name"=>"P Key Off",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          85=>["StopID"=>   85, "DivisionID"=>2, "Name"=>"Acc Key On",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          86=>["StopID"=>   86, "DivisionID"=>2, "Name"=>"Acc Key Off",         "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          87=>["StopID"=>   87, "DivisionID"=>3, "Name"=>"GO Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          88=>["StopID"=>   88, "DivisionID"=>3, "Name"=>"GO Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          89=>["StopID"=>   89, "DivisionID"=>4, "Name"=>"Rec Key On",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          90=>["StopID"=>   90, "DivisionID"=>4, "Name"=>"Rec Key Off",         "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
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
        1083=>["Noise"=>"StopOff",    "GroupID"=>802, "StopIDs"=>[]],
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
        2083=>["Noise"=>"StopOff",    "GroupID"=>803, "StopIDs"=>[]],
        2083=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[83]],
        2084=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[84]],
        2085=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[85]],
        2086=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[86]],
        2087=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[87]],
        2088=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[88]],
        2089=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[89]],
        2090=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[90]],
    ];
    
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
    
    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["DivisionID"]>1 && $hwdata["StopID"]<80) $hwdata["DivisionID"]++;
        return parent::createStop($hwdata);
    }

    public function createSwitchNoise(string $type, array $switchdata): void {
        if ($type==self::SwitchNoise 
                && $switchdata["DivisionID"]>1 
                && $switchdata["StopID"]<80) $switchdata["DivisionID"]++;
        parent::createSwitchNoise($type, $switchdata);
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        unset($hwdata["LoadSampleRange_EndPositionValue"]);
        if ($hwdata["PipeLayerNumber"]==2) $hwdata["IsTremulant"]=1;
        $pipe=parent::processSample($hwdata, $isattack); 
        if ($pipe && empty($hwdata["Pitch_ExactSamplePitch"])) {
            $hwdata["Pitch_ExactSamplePitch"]=
                $this->readSamplePitch(self::ROOT . $this->sampleFilename($hwdata));
            $pipe->PitchTuning=$this->sampleTuning($hwdata);
            if ($pipe && floatval($pipe->PitchTuning)>1200) {
                echo $pipe, "\n";
                exit();
            }
        }
        return $pipe;
    }
   
    public static function Oloron(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=\GOClasses\Ambience::$blankloop
                ="OrganInstallationPackages/002220/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new Oloron(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" $target";
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getStops() as $stop) {
                for ($i=1; $i<6; $i++) {
                    $stop->unset("Rank00${i}PipeCount");
                    $stop->unset("Rank00${i}FirstAccessibleKeyNumber");
                }
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Oloron(
                    [3=>"(dry)"],
                    "dry");
            self::Oloron(
                    [1=>"(front)"],
                    "front");
            self::Oloron( 
                    [2=>"(rear)"],
                    "rear");
            self::Oloron( 
                    [1=>"(front)", 2=>"(rear)", 3=> "(dry)"],
                    "surround");
        }
    }   
    
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\PG\ErrorHandler");
Oloron::Oloron();