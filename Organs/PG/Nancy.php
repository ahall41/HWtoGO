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
 * Import Nancy Demo
 * 
 * @todo: OG Coupler, Appelles etc
 */

class Nancy extends PGOrgan {

    const ROOT="/GrandOrgue/Organs/PG/Nancy/";
    const ODF="Nancy (demo).Organ_Hauptwerk_xml";
    const COMMENTS=
              "Nancy, Cathédrale Notre-Dame-de-l'Annonciation, France (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/nancy/\n"
            . "\n"
            . "1.1 Functional couplers; Wave based tremulant (on Positif)"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Nancy (demo - %s) 1.1.organ";
    
    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    protected bool $switchedtremulants=FALSE;
    
    public $positions=[];

    public $patchDivisions=[
            7=>["DivisionID"=>7, "Name"=>"Key Actions"],
            8=>["DivisionID"=>8, "Name"=>"Stop Actions"],
            9=>["DivisionID"=>9, "Name"=>"Blower"]
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
                0=>["Group"=>"Simple", "Name"=>"1", "SetID"=>93947],
                1=>["Group"=>"Simple", "Name"=>"2", "SetID"=>94032],
                2=>["Group"=>"Simple", "Name"=>"3", "SetID"=>94117],
               ],
            5=>"DELETE", // Crescendo
            6=>[
                0=>["SetID"=>6000],
               ],
            7=>[
                0=>["SetID"=>7000]
               ]
    ];

    public $patchTremulants=[
            1=>["Type"=>"Synth", "GroupIDs"=>[501,502,503,504]],
            2=>["TremulantID"=>2, "ControllingSwitchID"=>77, "Type"=>"Wave", "Name"=>"Pos Tremulant", "GroupIDs"=>[201,202,203,204]],
    ];
    
    public $patchKeyActions=[
            4=>["ConditionSwitchID"=>20070]
    ];
    
    public $patchEnclosures=[
            1=>["Panels"=>[10=>11, 40=>101680, 41=>101680, 42=>101680], "GroupIDs"=>[501,502,503,504]],
            2=>["Panels"=>[        40=>101679, 41=>101679, 42=>101679], "GroupIDs"=>[201,202,203,204]],
          101=>["EnclosureID"=>101, "Name"=>"Close Ped",    "Panels"=>[60=>500], "GroupIDs"=>[101], "AmpMinimumLevel"=>0],
          102=>["EnclosureID"=>102, "Name"=>"Front Ped",    "Panels"=>[60=>510], "GroupIDs"=>[102], "AmpMinimumLevel"=>0],
          103=>["EnclosureID"=>103, "Name"=>"Middle Ped",   "Panels"=>[60=>520], "GroupIDs"=>[103], "AmpMinimumLevel"=>0],
          104=>["EnclosureID"=>104, "Name"=>"Rear Ped",     "Panels"=>[60=>530], "GroupIDs"=>[104], "AmpMinimumLevel"=>0],
          201=>["EnclosureID"=>201, "Name"=>"Close Pos",    "Panels"=>[60=>501], "GroupIDs"=>[201], "AmpMinimumLevel"=>0],
          202=>["EnclosureID"=>202, "Name"=>"Front Pos",    "Panels"=>[60=>511], "GroupIDs"=>[202], "AmpMinimumLevel"=>0],
          203=>["EnclosureID"=>203, "Name"=>"Middle Pos",   "Panels"=>[60=>521], "GroupIDs"=>[203], "AmpMinimumLevel"=>0],
          204=>["EnclosureID"=>204, "Name"=>"Rear Pos",     "Panels"=>[60=>531], "GroupIDs"=>[204], "AmpMinimumLevel"=>0],
          301=>["EnclosureID"=>301, "Name"=>"Close GO",     "Panels"=>[60=>502], "GroupIDs"=>[301], "AmpMinimumLevel"=>0],
          302=>["EnclosureID"=>302, "Name"=>"Front GO",     "Panels"=>[60=>512], "GroupIDs"=>[302], "AmpMinimumLevel"=>0],
          303=>["EnclosureID"=>303, "Name"=>"Middle GO",    "Panels"=>[60=>522], "GroupIDs"=>[303], "AmpMinimumLevel"=>0],
          304=>["EnclosureID"=>304, "Name"=>"Rear GO",      "Panels"=>[60=>532], "GroupIDs"=>[304], "AmpMinimumLevel"=>0],
          401=>["EnclosureID"=>401, "Name"=>"Close Bombd",  "Panels"=>[60=>503], "GroupIDs"=>[401], "AmpMinimumLevel"=>0],
          402=>["EnclosureID"=>402, "Name"=>"Front Bombd",  "Panels"=>[60=>513], "GroupIDs"=>[402], "AmpMinimumLevel"=>0],
          403=>["EnclosureID"=>403, "Name"=>"Middle Bombd", "Panels"=>[60=>523], "GroupIDs"=>[403], "AmpMinimumLevel"=>0],
          404=>["EnclosureID"=>404, "Name"=>"Rear Bombd",   "Panels"=>[60=>533], "GroupIDs"=>[404], "AmpMinimumLevel"=>0],
          501=>["EnclosureID"=>501, "Name"=>"Close Rec",    "Panels"=>[60=>504], "GroupIDs"=>[501], "AmpMinimumLevel"=>0],
          502=>["EnclosureID"=>502, "Name"=>"Front Rec",    "Panels"=>[60=>514], "GroupIDs"=>[502], "AmpMinimumLevel"=>0],
          503=>["EnclosureID"=>503, "Name"=>"Middle Rec",   "Panels"=>[60=>524], "GroupIDs"=>[503], "AmpMinimumLevel"=>0],
          504=>["EnclosureID"=>504, "Name"=>"Rear Rec",     "Panels"=>[60=>534], "GroupIDs"=>[504], "AmpMinimumLevel"=>0],
          609=>["EnclosureID"=>609, "Name"=>"Key Actions",  "Panels"=>[60=>571], "GroupIDs"=>[701,702,703,704], "AmpMinimumLevel"=>0],
          709=>["EnclosureID"=>709, "Name"=>"Stop Actions", "Panels"=>[60=>572], "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>0],
          801=>["EnclosureID"=>801, "Name"=>"Blower",       "Panels"=>[60=>573], "GroupIDs"=>[901,902,903,904], "AmpMinimumLevel"=>0],
          901=>["EnclosureID"=>901, "Name"=>"Close",        "Panels"=>[60=>540], "GroupIDs"=>[101,201,301,401,501], "AmpMinimumLevel"=>0],
          902=>["EnclosureID"=>902, "Name"=>"Front",        "Panels"=>[60=>541], "GroupIDs"=>[102,202,302,402,502], "AmpMinimumLevel"=>0],
          903=>["EnclosureID"=>903, "Name"=>"Middle",       "Panels"=>[60=>542], "GroupIDs"=>[103,203,303,403,503], "AmpMinimumLevel"=>0],
          904=>["EnclosureID"=>904, "Name"=>"Rear",         "Panels"=>[60=>543], "GroupIDs"=>[104,204,304,404,504], "AmpMinimumLevel"=>0],
    ];
    
    protected $patchStops=[
          80=>["StopID"=>   80, "DivisionID"=>1, "Name"=>"Ambient (close)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
        1080=>["StopID"=> 1080, "DivisionID"=>1, "Name"=>"Ambient (front)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
        2080=>["StopID"=> 2080, "DivisionID"=>1, "Name"=>"Ambient (middle)",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
        3080=>["StopID"=> 3080, "DivisionID"=>1, "Name"=>"Ambient (rear)",      "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>904],
          81=>["StopID"=>   81, "DivisionID"=>1, "Name"=>"Main Motor (close)",  "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
        1081=>["StopID"=> 1081, "DivisionID"=>1, "Name"=>"Main Motor (front)",  "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
        2081=>["StopID"=> 2081, "DivisionID"=>1, "Name"=>"Main Motor (rear)",   "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
        3081=>["StopID"=> 3081, "DivisionID"=>1, "Name"=>"Main Motor (rear)",   "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>904],
          83=>["StopID"=>   83, "DivisionID"=>1, "Name"=>"P Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          84=>["StopID"=>   84, "DivisionID"=>1, "Name"=>"P Key Off",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          85=>["StopID"=>   85, "DivisionID"=>2, "Name"=>"POS Key On",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          86=>["StopID"=>   86, "DivisionID"=>2, "Name"=>"POS Key Off",         "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          87=>["StopID"=>   87, "DivisionID"=>3, "Name"=>"GO Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          88=>["StopID"=>   88, "DivisionID"=>3, "Name"=>"GO Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          89=>["StopID"=>   89, "DivisionID"=>4, "Name"=>"BOM Key On",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          90=>["StopID"=>   90, "DivisionID"=>4, "Name"=>"BOM Key Off",         "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          91=>["StopID"=>   91, "DivisionID"=>5, "Name"=>"REC Key On",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          92=>["StopID"=>   91, "DivisionID"=>5, "Name"=>"REC Key Off",         "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
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
          91=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[91]],
          92=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[92]],
        
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
        1091=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[91]],
        1092=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[92]],

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
        2091=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[91]],
        2092=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[92]],

        3080=>["Noise"=>"Ambient",    "GroupID"=>904, "StopIDs"=>[]],
        3081=>["Noise"=>"StopOn",     "GroupID"=>804, "StopIDs"=>[]],
        3083=>["Noise"=>"StopOff",    "GroupID"=>804, "StopIDs"=>[]],
        3083=>["Noise"=>"KeyOn",      "GroupID"=>704, "StopIDs"=>[83]],
        3084=>["Noise"=>"KeyOff",     "GroupID"=>704, "StopIDs"=>[84]],
        3085=>["Noise"=>"KeyOn",      "GroupID"=>704, "StopIDs"=>[85]],
        3086=>["Noise"=>"KeyOff",     "GroupID"=>704, "StopIDs"=>[86]],
        3087=>["Noise"=>"KeyOn",      "GroupID"=>704, "StopIDs"=>[87]],
        3088=>["Noise"=>"KeyOff",     "GroupID"=>704, "StopIDs"=>[88]],
        3089=>["Noise"=>"KeyOn",      "GroupID"=>704, "StopIDs"=>[89]],
        3090=>["Noise"=>"KeyOff",     "GroupID"=>704, "StopIDs"=>[90]],
        3091=>["Noise"=>"KeyOff",     "GroupID"=>704, "StopIDs"=>[91]],
        3092=>["Noise"=>"KeyOff",     "GroupID"=>704, "StopIDs"=>[92]],
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
    
    public function createManual(array $hwdata): ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]>5) return NULL;
        return parent::createManual($hwdata);
    }
    
    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["SourceKeyboardID"]==6) $hwdata["SourceKeyboardID"]=3;
        if ($hwdata["DestKeyboardID"]==6) $hwdata["DestKeyboardID"]=3;
        return parent::createCoupler($hwdata);
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        $data["SwitchID"]=$data["SwitchID"] % 1000;
        parent::configurePanelSwitchImages($switch, $data);
    }
    
    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        unset($hwdata["LoadSampleRange_EndPositionValue"]);
        if ($hwdata["PipeLayerNumber"]==2) $hwdata["IsTremulant"]=1;
        return parent::processSample($hwdata, $isattack);
    }

    public static function Nancy(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=\GOClasses\Ambience::$blankloop
                ="./OrganInstallationPackages/002514/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new Nancy(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getStops() as $stop) {
                for ($i=1; $i<6; $i++) {
                    $stop->unset("Rank00${i}PipeCount");
                    $stop->unset("Rank00${i}FirstAccessibleKeyNumber");
                }
            }
            $hwi->getSwitch(20070)->DisplayInInvertedState="Y";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Nancy(
                    [1=>"(close)"],
                    "close");
            self::Nancy( 
                    [2=>"(front)"],
                    "front");
            self::Nancy( 
                    [3=>"(middle)"],
                    "middle");
            self::Nancy(
                    [4=>"(rear)"],
                    "rear");
            self::Nancy( 
                    [1=>"(close)", 2=>"(front)", 3=> "(middle)", 4=>"(rear)"],
                    "surround");
        }
    }   
    
}
Nancy::Nancy();