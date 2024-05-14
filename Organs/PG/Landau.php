<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * @todo (for full version):
 *  - Wave tremulant option
 *  - Reed stop delay
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\PG;
require_once __DIR__ . "/PGOrgan.php";

/**
 * Import Gogh Demo
 */

class Landau extends PGOrgan {

    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    
    public $positions=[];

    public $patchDivisions=[
            5=>["DivisionID"=>5, "Name"=>"Echo"],
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
                0=>["Group"=>"Simple", "Name"=>"1", "SetID"=>10529],
                1=>["Group"=>"Simple", "Name"=>"2", "SetID"=>10626],
                2=>["Group"=>"Simple", "Name"=>"3", "SetID"=>10723],
               ],
            5=>"DELETE", // Crescendo
            6=>"DELETE", // Crescendo
            7=>[
                0=>["SetID"=>7000], // Settings
               ],
            8=>[
                0=>["SetID"=>8000], // Info
               ]
    ];

    public $patchTremulants=[
            1=>["ControllingSwitchID"=>701, "Type"=>"Switched", "DivisionID"=>3],
            2=>["ControllingSwitchID"=>702, "Type"=>"Switched", "DivisionID"=>3],
            3=>["ControllingSwitchID"=>703, "Type"=>"Switched", "DivisionID"=>4],
    ];
    
    public $patchEnclosures=[
            1=>["Panels"=>[10=>11, 40=>60925, 41=>60925, 42=>60925], 
                "GroupIDs"=>[301,302,303],  "AmpMinimumLevel"=>20],
            2=>["Panels"=>[10=>12, 40=>60926, 41=>60926, 42=>60926], 
                "GroupIDs"=>[501,502,503],  "AmpMinimumLevel"=>20],
            3=>["Panels"=>[10=>13, 40=>60927, 41=>60927, 42=>60927], 
                "GroupIDs"=>[401,402,403],  "AmpMinimumLevel"=>20],
          101=>["EnclosureID"=>101, "Name"=>"Close Ped",    "Panels"=>[70=>500], "GroupIDs"=>[101], "AmpMinimumLevel"=>1],
          102=>["EnclosureID"=>102, "Name"=>"Front Ped",    "Panels"=>[70=>510], "GroupIDs"=>[102], "AmpMinimumLevel"=>1],
          103=>["EnclosureID"=>103, "Name"=>"Rear Ped",     "Panels"=>[70=>520], "GroupIDs"=>[103], "AmpMinimumLevel"=>1],
          201=>["EnclosureID"=>201, "Name"=>"Close I",      "Panels"=>[70=>501], "GroupIDs"=>[201], "AmpMinimumLevel"=>1],
          202=>["EnclosureID"=>202, "Name"=>"Front I",      "Panels"=>[70=>511], "GroupIDs"=>[202], "AmpMinimumLevel"=>1],
          203=>["EnclosureID"=>203, "Name"=>"Rear I",       "Panels"=>[70=>521], "GroupIDs"=>[203], "AmpMinimumLevel"=>1],
          301=>["EnclosureID"=>301, "Name"=>"Close II",     "Panels"=>[70=>502], "GroupIDs"=>[301,501], "AmpMinimumLevel"=>1],
          302=>["EnclosureID"=>302, "Name"=>"Front II",     "Panels"=>[70=>512], "GroupIDs"=>[302,502], "AmpMinimumLevel"=>1],
          303=>["EnclosureID"=>303, "Name"=>"Rear II",      "Panels"=>[70=>522], "GroupIDs"=>[303,503], "AmpMinimumLevel"=>1],
          401=>["EnclosureID"=>401, "Name"=>"Close III",    "Panels"=>[70=>503], "GroupIDs"=>[401], "AmpMinimumLevel"=>1],
          402=>["EnclosureID"=>402, "Name"=>"Front III",    "Panels"=>[70=>513], "GroupIDs"=>[402], "AmpMinimumLevel"=>1],
          403=>["EnclosureID"=>403, "Name"=>"Rear III",     "Panels"=>[70=>523], "GroupIDs"=>[403], "AmpMinimumLevel"=>1],
          709=>["EnclosureID"=>909, "Name"=>"Key Actions",  "Panels"=>[70=>571], "GroupIDs"=>[701,702,803], "AmpMinimumLevel"=>1],
          809=>["EnclosureID"=>809, "Name"=>"Stop Actions", "Panels"=>[70=>572], "GroupIDs"=>[801,802,803], "AmpMinimumLevel"=>1],
          909=>["EnclosureID"=>909, "Name"=>"Blower",       "Panels"=>[70=>573], "GroupIDs"=>[901,902,903], "AmpMinimumLevel"=>1],
          901=>["EnclosureID"=>901, "Name"=>"Close",        "Panels"=>[70=>540], "GroupIDs"=>[101,201,301,401,501,701,801,901], "AmpMinimumLevel"=>1],
          902=>["EnclosureID"=>902, "Name"=>"Front",        "Panels"=>[70=>541], "GroupIDs"=>[102,202,302,402,502,702,802,902], "AmpMinimumLevel"=>1],
          903=>["EnclosureID"=>903, "Name"=>"Rear",         "Panels"=>[70=>542], "GroupIDs"=>[103,203,303,403,503,703,803,903], "AmpMinimumLevel"=>1],
    ];
    
    protected $patchStops=[
          80=>["StopID"=>   80, "DivisionID"=>1, "Name"=>"Noises (close)",      "ControllingSwitchID"=>99,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
          83=>["StopID"=>   83, "DivisionID"=>1, "Name"=>"P Key On (close)",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          84=>["StopID"=>   84, "DivisionID"=>1, "Name"=>"P Key Off (close)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          85=>["StopID"=>   85, "DivisionID"=>2, "Name"=>"HW Key On (close)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          86=>["StopID"=>   86, "DivisionID"=>2, "Name"=>"HW Key Off (close)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          87=>["StopID"=>   87, "DivisionID"=>3, "Name"=>"SW Key On (close)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          88=>["StopID"=>   88, "DivisionID"=>3, "Name"=>"SW Key Off (close)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          89=>["StopID"=>   89, "DivisionID"=>4, "Name"=>"RP Key On (close)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          90=>["StopID"=>   90, "DivisionID"=>4, "Name"=>"RP Key Off (close)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],

        1080=>["StopID"=> 1080, "DivisionID"=>1, "Name"=>"Noises (front)",      "ControllingSwitchID"=>99,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
        1083=>["StopID"=> 1083, "DivisionID"=>1, "Name"=>"P Key On (front)",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        1084=>["StopID"=> 1084, "DivisionID"=>1, "Name"=>"P Key Off (front)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        1085=>["StopID"=> 1085, "DivisionID"=>2, "Name"=>"HW Key On (front)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        1086=>["StopID"=> 1086, "DivisionID"=>2, "Name"=>"HW Key Off (front)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        1087=>["StopID"=> 1087, "DivisionID"=>3, "Name"=>"SW Key On (front)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        1088=>["StopID"=> 1088, "DivisionID"=>3, "Name"=>"SW Key Off (front)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        1089=>["StopID"=> 1089, "DivisionID"=>4, "Name"=>"RP Key On (front)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        1090=>["StopID"=> 1090, "DivisionID"=>4, "Name"=>"RP Key Off (front)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],

        2080=>["StopID"=> 2080, "DivisionID"=>1, "Name"=>"Noises (rear)",       "ControllingSwitchID"=>99,   "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
        2083=>["StopID"=> 2083, "DivisionID"=>1, "Name"=>"P Key On (rear)",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        2084=>["StopID"=> 2084, "DivisionID"=>1, "Name"=>"P Key Off (rear)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        2085=>["StopID"=> 2085, "DivisionID"=>2, "Name"=>"HW Key On (rear)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        2086=>["StopID"=> 2086, "DivisionID"=>2, "Name"=>"HW Key Off (rear)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        2087=>["StopID"=> 2087, "DivisionID"=>3, "Name"=>"SW Key On (rear)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        2088=>["StopID"=> 2088, "DivisionID"=>3, "Name"=>"SW Key Off (rear)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        2089=>["StopID"=> 2089, "DivisionID"=>4, "Name"=>"RP Key On (rear)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        2090=>["StopID"=> 2090, "DivisionID"=>4, "Name"=>"RP Key Off (rear)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
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
    
    public function createOrgan(array $hwdata): \GOClasses\Organ {
        /*Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            //if ($instance["DisplayPageID"]!=4) continue;
            $instanceid=intval($instance["ImageSetInstanceID"]);
            
            echo $instanceid, " ", 
                 $instance["ImageSetInstanceID"], " ",
                 $instance["Name"], "\n";
            foreach ($this->hwdata->switches() as $switch) {
                if (isset($switch["Disp_ImageSetInstanceID"]) && 
                        intval($switch["Disp_ImageSetInstanceID"])==$instanceid) {
                    echo "\t", $switch["SwitchID"], " - ",
                         $switch["Name"], "\n";
                }
            }
        } 
        exit(); /*/
        return parent::createOrgan($hwdata);
    }
    
    public function patchData(\HWClasses\HWData $hwd): void {
        // Fill in missing StopRank table
        $index=10000;
        
        foreach ($hwd->ranks() as $rankid=>$rank) {
            $stopid=$rankid%1000;
            if ($stopid>=80) {continue;} // Exclude Noise and Tremmed ranks
            
            $nodes=($stopid<17) ? 32 : 61;
            
            $this->patchStopRanks[$index++]=[
                "StopID"=>$stopid, 
                "RankID"=>$rankid,
                "MIDINoteNumOfFirstMappedDivisionInputNode"=>36,
                "NumberOfMappedDivisionInputNodes"=>$nodes];
        }

        parent::patchData($hwd);
    }
    
    public function configureKeyboardKey(\GOClasses\Manual $manual, $switchid, $midikey): void {
        $switch=$this->hwdata->switch($switchid);
        if (!empty($switch["Disp_ImageSetIndexEngaged"])) {
            parent::configureKeyboardKey($manual, $switchid, $midikey);
        }
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
    
    public function createTremulant(array $hwdata): ?\GOClasses\Sw1tch {
        return parent::createTremulant($hwdata);
    }
    
    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        if (($stopid=$hwdata["StopID"])>=80) {
            $pos=1+intval($stopid/1000);
            if (!(array_key_exists($pos, $this->positions))) {
                return NULL;
            }
        }
        return parent::createStop($hwdata);
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if ($hwdata["RankID"] % 100==80) {return Null;}
        return parent::createRank($hwdata, $keynoise);
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
            $midi=$this->sampleMidiKey($hwdata);
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
        unset($hwdata["LoadSampleRange_EndPositionValue"]);
        $rankid=$hwdata["RankID"];
        if ((intval($rankid/100) % 10)==5) $hwdata["RankID"]=-($rankid-500);
        $pipe=parent::processSample($hwdata, $isattack);
        /* if ($pipe && $isattack) {
            $pipe->MIDIKeyOverride=$or=floor($key=$this->samplePitchMidi($hwdata));
            $pipe->MIDIPitchFraction=100*($key-$or);
        } */
        return $pipe;
    }
    
    protected static function Landau(Landau $hwi, array $positions, string $target) {
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        $hwi->positions=$positions;
        $hwi->import();
        
        foreach([1,2,3,4] as $mid) {
            $manual=$hwi->getManual($mid);
            $manual->NumberOfLogicalKeys=$manual->NumberOfAccessibleKeys=$mid==0 ? 32 : 61;
            if ($mid>2) {
                $manual->NumberOfLogicalKeys+=12;
            }
        }
        
        foreach ($hwi->getStops() as $stopid=>$stop) {
            // echo $stopid, "\n", $stop, "\n";
            switch ($id=abs($stopid)) {
                case 49:
                case 50:
                    $stop->Switch002+=2; // Echo tremulant
            }
            
            if ($id>=36 && $id<=72) {
                $stop->NumberOfAccessiblePipes+=12;
                $ranks=$stop->Ranks();
                foreach($ranks as $n=>$rank) {
                    $stop->set("Rank{$n}PipeCount", sizeof($rank->Pipes()));
                }
                //echo $stop; exit();
            }
        }
        
        foreach ($hwi->getSwitchNoises() as $stop) {
            $stop->Name=str_replace(
                    ["(close) (close)", "(front) (front)", "(rear) (rear)"],
                    ["(close)", "(front)", "(rear)"],
                    $stop->Name);
        }
        
        foreach ($hwi->getRanks() as $rankid=>$rank) {
            switch (abs($rankid % 100)) {
                case 49:
                case 50:
                    $wcg=$hwi->getWindchestGroup(501 + intval(abs($rankid)/1000));
                    $rank->WindchestGroup=$wcg->instance();
                    //echo $rank, "\n";
            }
        }
    }
}

class LandauDemo extends Landau {

    const ROOT="/GrandOrgue/Organs/PG/Landau/";
    const ODF="Landau (demo).Organ_Hauptwerk_xml";
    const COMMENTS=
              "Stadtpfarrkirche St. Maria in Landau in der Pfalz, Germany (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/landau-st-maria/\n"
            . "\n";
    
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Landau (demo - %s) 1.1.organ";
    
    public static function LandauDemo(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=
                "OrganInstallationPackages/002529/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new LandauDemo(self::SOURCE);
            self::Landau($hwi, $positions, $target);
            $hwi->saveODF(sprintf(self::TARGET, $target));
            $hwi->getOrgan()->ChurchName.=sprintf(" (%s)", $target);
            echo $hwi->getOrgan()->ChurchName, "\n";
        }
        else {
            self::LandauDemo(
                    [1=>"(close)"],
                    "close");
            self::LandauDemo( 
                    [2=>"(front)"],
                    "front");
            self::LandauDemo(
                    [3=>"(rear)"],
                    "rear");
            self::LandauDemo( 
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
LandauDemo::LandauDemo();