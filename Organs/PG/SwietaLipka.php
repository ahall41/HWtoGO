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
 * Import SwietaLipka Demo
 *
 * @todo: Nachtigall etc.
 */

class SwietaLipka extends PGOrgan {

    const ROOT="/GrandOrgue/Organs/PG/SwietaLipka/";
    const VERSION="1.3";
    const COMMENTS=
              "Święta Lipka, Sanktuarium Nawiedzenia Najświętszej Maryi Panny, Poland (%s)\n"
            . "https://piotrgrabowski.pl/swieta-lipka/\n"
            . "\n"
            . "1.1 Functional couplers; Wave based tremulants\n"
            . "1.2 Added full surround\n"
            . "1.3 Added crescendo program\n"
            . "    Added tremulant samples\n"
            . "    Corrected console swell images\n"
            . "    Added swell superoctave extension\n"
            . "\n";

    protected static bool $synthTremulant=FALSE;
    protected static string $packageID;

    protected $combinations=[
        "crescendos"=>[
            "A"=>[1000,1001,1002,1003,1004,1005,1006,1007,1008,1009,
                  1010,1011,1012,1013,1014,1015,1016,1017,1018,1020,
                  1022,1024,1026,1028,1030,1032,1034,1036,1038,1040,
                  1042,1044]
                ]
        ];

    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    protected int $switchNoiseWCG=700;
    protected bool $switchedtremulants=FALSE;

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
                0=>["Group"=>"Simple", "Name"=>"1", "SetID"=>98717],
                1=>["Group"=>"Simple", "Name"=>"2", "SetID"=>98782],
                2=>["Group"=>"Simple", "Name"=>"3", "SetID"=>98847],
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
            1=>["TremulantID"=>1, "ControllingSwitchID"=>50, "Type"=>"Wave", "Name"=>"P Tremulant",  "GroupIDs"=>[101,102,103,104]],
            2=>["TremulantID"=>2, "ControllingSwitchID"=>51, "Type"=>"Wave", "Name"=>"I Tremulant",  "GroupIDs"=>[201,202,203,204]],
            3=>["TremulantID"=>3, "ControllingSwitchID"=>52, "Type"=>"Wave", "Name"=>"II Tremulant", "GroupIDs"=>[301,302,303,304]],
    ];

    public $patchEnclosures=[     // ImageSetInstance, Name=SimpleJamb ?_C1
            1=>["Panels"=>[10=>11, 40=>113350, 41=>113350, 42=>113350], "GroupIDs"=>[301,302,303,304],],
          101=>["EnclosureID"=>101, "Name"=>"Close Ped",    "Panels"=>[60=>500], "GroupIDs"=>[101], "AmpMinimumLevel"=>1],
          102=>["EnclosureID"=>102, "Name"=>"Front Ped",    "Panels"=>[60=>510], "GroupIDs"=>[102], "AmpMinimumLevel"=>1],
          103=>["EnclosureID"=>103, "Name"=>"Middle Ped",   "Panels"=>[60=>520], "GroupIDs"=>[103], "AmpMinimumLevel"=>1],
          104=>["EnclosureID"=>104, "Name"=>"Rear Ped",     "Panels"=>[60=>530], "GroupIDs"=>[104], "AmpMinimumLevel"=>1],
          201=>["EnclosureID"=>201, "Name"=>"Close I",      "Panels"=>[60=>501], "GroupIDs"=>[201], "AmpMinimumLevel"=>1],
          202=>["EnclosureID"=>202, "Name"=>"Front I",      "Panels"=>[60=>511], "GroupIDs"=>[202], "AmpMinimumLevel"=>1],
          203=>["EnclosureID"=>203, "Name"=>"Middle I",     "Panels"=>[60=>521], "GroupIDs"=>[203], "AmpMinimumLevel"=>1],
          204=>["EnclosureID"=>204, "Name"=>"Rear I",       "Panels"=>[60=>531], "GroupIDs"=>[204], "AmpMinimumLevel"=>1],
          301=>["EnclosureID"=>301, "Name"=>"Close II",     "Panels"=>[60=>502], "GroupIDs"=>[301], "AmpMinimumLevel"=>1],
          302=>["EnclosureID"=>302, "Name"=>"Front II",     "Panels"=>[60=>512], "GroupIDs"=>[302], "AmpMinimumLevel"=>1],
          303=>["EnclosureID"=>303, "Name"=>"Middle II",    "Panels"=>[60=>522], "GroupIDs"=>[303], "AmpMinimumLevel"=>1],
          304=>["EnclosureID"=>304, "Name"=>"Rear II",      "Panels"=>[60=>532], "GroupIDs"=>[304], "AmpMinimumLevel"=>1],
          609=>["EnclosureID"=>609, "Name"=>"Key Actions",  "Panels"=>[60=>571], "GroupIDs"=>[601,602,603,604], "AmpMinimumLevel"=>1],
          709=>["EnclosureID"=>709, "Name"=>"Stop Actions", "Panels"=>[60=>572], "GroupIDs"=>[701,702,703,704], "AmpMinimumLevel"=>1],
          801=>["EnclosureID"=>801, "Name"=>"Blower",       "Panels"=>[60=>573], "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>1],
          901=>["EnclosureID"=>901, "Name"=>"Close",        "Panels"=>[60=>540], "GroupIDs"=>[101,201,301], "AmpMinimumLevel"=>1],
          902=>["EnclosureID"=>902, "Name"=>"Front",        "Panels"=>[60=>541], "GroupIDs"=>[102,202,302], "AmpMinimumLevel"=>1],
          903=>["EnclosureID"=>903, "Name"=>"Middle",       "Panels"=>[60=>542], "GroupIDs"=>[103,203,303], "AmpMinimumLevel"=>1],
          904=>["EnclosureID"=>904, "Name"=>"Rear",         "Panels"=>[60=>543], "GroupIDs"=>[104,204,304], "AmpMinimumLevel"=>1],
    ];

    protected $patchStops=[
          83=>["StopID"=>   83, "DivisionID"=>1, "Name"=>"P Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          83=>["StopID"=>   83, "DivisionID"=>1, "Name"=>"P Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          84=>["StopID"=>   84, "DivisionID"=>1, "Name"=>"P Key Off",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          85=>["StopID"=>   85, "DivisionID"=>2, "Name"=>"I Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          86=>["StopID"=>   86, "DivisionID"=>2, "Name"=>"I Key Off",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          87=>["StopID"=>   87, "DivisionID"=>3, "Name"=>"II Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          88=>["StopID"=>   88, "DivisionID"=>3, "Name"=>"II Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          90=>["StopID"=>   90, "DivisionID"=>1, "Name"=>"Ambient (close)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>801],
        1090=>["StopID"=> 1090, "DivisionID"=>1, "Name"=>"Ambient (front)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>802],
        2090=>["StopID"=> 2090, "DivisionID"=>1, "Name"=>"Ambient (middle)",    "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>803],
        3090=>["StopID"=> 3090, "DivisionID"=>1, "Name"=>"Ambient (rear)",      "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>804],
          91=>["StopID"=>   91, "DivisionID"=>1, "Name"=>"Main Motor (close)",  "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>801],
        1091=>["StopID"=> 1091, "DivisionID"=>1, "Name"=>"Main Motor (front)",  "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>802],
        2091=>["StopID"=> 2091, "DivisionID"=>1, "Name"=>"Main Motor (middle)", "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>803],
        3091=>["StopID"=> 3091, "DivisionID"=>1, "Name"=>"Main Motor (rear)",   "ControllingSwitchID"=>101,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>804],
          92=>["StopID"=>   92, "DivisionID"=>1, "Name"=>"Tremulant (close)",   "ControllingSwitchID"=>NULL, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>801],
        1092=>["StopID"=> 1092, "DivisionID"=>1, "Name"=>"Tremulant (front)",   "ControllingSwitchID"=>NULL, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>802],
        2092=>["StopID"=> 2092, "DivisionID"=>1, "Name"=>"Tremulant (middle)",  "ControllingSwitchID"=>NULL, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>803],
        3092=>["StopID"=> 3092, "DivisionID"=>1, "Name"=>"Tremulant (rear)",    "ControllingSwitchID"=>NULL, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>804],
          93=>["StopID"=>   93, "DivisionID"=>1, "Name"=>"Nachtigall (close)",  "ControllingSwitchID"=>53,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>801],
        1093=>["StopID"=> 1093, "DivisionID"=>1, "Name"=>"Nachtigall (front)",  "ControllingSwitchID"=>53,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>802],
        2093=>["StopID"=> 2093, "DivisionID"=>1, "Name"=>"Nachtigall (middle)", "ControllingSwitchID"=>53,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>803],
        3093=>["StopID"=> 3093, "DivisionID"=>1, "Name"=>"Nachtigall (rear)",   "ControllingSwitchID"=>53,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>804],
          94=>["StopID"=>   94, "DivisionID"=>1, "Name"=>"Verkündigung",        "ControllingSwitchID"=>54,   "Disused"=>TRUE], // No sample ??
          95=>["StopID"=>   95, "DivisionID"=>1, "Name"=>"Glocken (close)",     "ControllingSwitchID"=>55,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>801],
        1095=>["StopID"=> 1095, "DivisionID"=>1, "Name"=>"Glocken (front)",     "ControllingSwitchID"=>55,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>802],
        2095=>["StopID"=> 2095, "DivisionID"=>1, "Name"=>"Glocken (middle)",    "ControllingSwitchID"=>55,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>803],
        3095=>["StopID"=> 3095, "DivisionID"=>1, "Name"=>"Glocken (rear)",      "ControllingSwitchID"=>55,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>804],
          96=>["StopID"=>   96, "DivisionID"=>1, "Name"=>"Zimbelstern (close)", "ControllingSwitchID"=>56,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>801],
        1096=>["StopID"=> 1096, "DivisionID"=>1, "Name"=>"Zimbelstern (front)", "ControllingSwitchID"=>56,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>802],
        2096=>["StopID"=> 2096, "DivisionID"=>1, "Name"=>"Zimbelstern (middle)","ControllingSwitchID"=>56,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>803],
        3096=>["StopID"=> 3096, "DivisionID"=>1, "Name"=>"Zimbelstern (rear)",  "ControllingSwitchID"=>56,   "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>804],
          97=>["StopID"=>   97, "DivisionID"=>1, "Name"=>"Fanfaren",            "ControllingSwitchID"=>57,   "Disused"=>TRUE], // No sample ??
    ];

    protected $patchRanks=[
          80=>["Noise"=>"Ambient",    "GroupID"=>801, "StopIDs"=>[]],
          81=>["Noise"=>"StopOn",     "GroupID"=>701, "StopIDs"=>[]],
          82=>["Noise"=>"StopOff",    "GroupID"=>701, "StopIDs"=>[]],
          83=>["Noise"=>"KeyOn",      "GroupID"=>601, "StopIDs"=>[83]],
          84=>["Noise"=>"KeyOff",     "GroupID"=>601, "StopIDs"=>[84]],
          85=>["Noise"=>"KeyOn",      "GroupID"=>601, "StopIDs"=>[85]],
          86=>["Noise"=>"KeyOff",     "GroupID"=>601, "StopIDs"=>[86]],
          87=>["Noise"=>"KeyOn",      "GroupID"=>601, "StopIDs"=>[87]],
          88=>["Noise"=>"KeyOff",     "GroupID"=>601, "StopIDs"=>[88]],

        1080=>["Noise"=>"Ambient",    "GroupID"=>802, "StopIDs"=>[]],
        1081=>["Noise"=>"StopOn",     "GroupID"=>702, "StopIDs"=>[]],
        1083=>["Noise"=>"StopOff",    "GroupID"=>702, "StopIDs"=>[]],
        1083=>["Noise"=>"KeyOn",      "GroupID"=>602, "StopIDs"=>[83]],
        1084=>["Noise"=>"KeyOff",     "GroupID"=>602, "StopIDs"=>[84]],
        1085=>["Noise"=>"KeyOn",      "GroupID"=>602, "StopIDs"=>[85]],
        1086=>["Noise"=>"KeyOff",     "GroupID"=>602, "StopIDs"=>[86]],
        1087=>["Noise"=>"KeyOn",      "GroupID"=>602, "StopIDs"=>[87]],
        1088=>["Noise"=>"KeyOff",     "GroupID"=>602, "StopIDs"=>[88]],

        2080=>["Noise"=>"Ambient",    "GroupID"=>803, "StopIDs"=>[]],
        2081=>["Noise"=>"StopOn",     "GroupID"=>703, "StopIDs"=>[]],
        2083=>["Noise"=>"StopOff",    "GroupID"=>603, "StopIDs"=>[]],
        2083=>["Noise"=>"KeyOn",      "GroupID"=>603, "StopIDs"=>[83]],
        2084=>["Noise"=>"KeyOff",     "GroupID"=>603, "StopIDs"=>[84]],
        2085=>["Noise"=>"KeyOn",      "GroupID"=>603, "StopIDs"=>[85]],
        2086=>["Noise"=>"KeyOff",     "GroupID"=>603, "StopIDs"=>[86]],
        2087=>["Noise"=>"KeyOn",      "GroupID"=>603, "StopIDs"=>[87]],
        2088=>["Noise"=>"KeyOff",     "GroupID"=>603, "StopIDs"=>[88]],

        3080=>["Noise"=>"Ambient",    "GroupID"=>804, "StopIDs"=>[]],
        3081=>["Noise"=>"StopOn",     "GroupID"=>704, "StopIDs"=>[]],
        3083=>["Noise"=>"StopOff",    "GroupID"=>604, "StopIDs"=>[]],
        3083=>["Noise"=>"KeyOn",      "GroupID"=>604, "StopIDs"=>[83]],
        3084=>["Noise"=>"KeyOff",     "GroupID"=>604, "StopIDs"=>[84]],
        3085=>["Noise"=>"KeyOn",      "GroupID"=>604, "StopIDs"=>[85]],
        3086=>["Noise"=>"KeyOff",     "GroupID"=>604, "StopIDs"=>[86]],
        3087=>["Noise"=>"KeyOn",      "GroupID"=>604, "StopIDs"=>[87]],
        3088=>["Noise"=>"KeyOff",     "GroupID"=>604, "StopIDs"=>[88]],
    ];

    public function import(): void {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 4:
                    echo ($instanceID=$instance["ImageSetInstanceID"]), "\t",
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

        parent::import();

        foreach($this->getStops() as $stopid=>$stop) {
            $stopid=$stopid % 100;
            if ($stopid<12) { // Pedal
                $stop->NumberOfAccessiblePipes=30;
            }
            elseif ($stopid<31) { // Great
                $stop->NumberOfAccessiblePipes=58;
            }
            elseif ($stopid<44) { // Swell
                $stop->NumberOfAccessiblePipes=70;
            }
            elseif ($stopid==92) {// Tremulant effect
                foreach($this->patchTremulants as $tremdata) {
                    $stop->Switch($this->getSwitch($tremdata["ControllingSwitchID"]));
                    $stop->Function="Or";
                }
                unset($stop->GCState);
            }
            
            for ($r=1; $r<=$stop->NumberOfRanks; $r++) {
                $rn=sprintf("%03d", $r);
                $stop->unset("Rank${rn}PipeCount");
                $stop->unset("Rank${rn}FirstAccessibleKeyNumber");
                switch ($stopid) {
                    case 31: // I  Glockenspiel
                        $stop->set("Rank${rn}FirstAccessibleKeyNumber", 13);
                        break;
                }
            }
        }

        foreach ([10=>115572,40=>113361] as $pageid=>$instanceid) {
            foreach([0,1,2] as $layoutid) {
                if (($panel=$this->getPanel($pageid+$layoutid, FALSE))) {
                    $cr=$panel->Element();
                    $cr->Type="Swell";
                    $this->configureEnclosureImage($cr, ["InstanceID"=>$instanceid], $layoutid);
                }
            }
        }

        $swell=$this->getPanel(10)->getElement(1);
        
        for ($n=1; $n<=7; $n++) {
            $swell->set("Bitmap00$n", "OrganInstallationPackages/" . self::$packageID . "/Images/Enclosure/Enclosure$n.png");
        }

        for ($n=1; $n<=5; $n++) {
            foreach([0,1,2] as $layoutid) {
                if (($panel=$this->getPanel(40+$layoutid, FALSE))) {
                    $pe=$panel->Element();
                    $pe->Type="General0$n";
                    $this->configureImage($pe, ["SwitchID"=>353351+$n], $layoutid);
                }
            }
        }

        if (self::$synthTremulant) {
            foreach([1,2,3] as $tremid) {
                $trem=$this->getTremulant($tremid);
                $trem->TremulantType="Synth";
                $trem->Period=250;
                $trem->AmpModDepth=13;
                $trem->StartRate=30;
                $trem->StopRate=30;
            }
        }
        
        foreach ([1=>30,2=>58,3=>70] as $manualid=>$keys) {
            $manual=$this->getManual($manualid);
            $manual->NumberOfLogicalKeys=$keys;
            $manual->NumberOfAccessibleKeys=($keys>58 ? 58 : $keys);
        }
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

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        $data["SwitchID"]=$data["SwitchID"] % 1000;
        parent::configurePanelSwitchImages($switch, $data);
    }

    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Noise {
        /* if (str_contains($hwdata["SampleFilename"], "Noises/")) {
            echo $hwdata["PipeID"], "\t", $hwdata["RankID"], "\t", $hwdata["SampleFilename"], "\n";
        } */
        $method=$isattack ? "configureAttack" : "configureRelease";
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $type=$this->hwdata->rank($hwdata["RankID"])["Noise"];
        $midi=$hwdata["NormalMIDINoteNumber"];
        if ($type=="Ambient") {
            $stop=$this->getStop($id=($isattack ? +1 : -1) * ($hwdata["RankID"] + $midi-26));
            if ($stop!==NULL) {
                $ambience=$stop->Ambience();
                $ambience->LoadRelease="Y";
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
        static $notrem=[1,2,3,4,5,10,11,31];
        if (in_array($hwdata["RankID"]%100, $notrem)) {
            unset($hwdata["IsTremulant"]);
        }
        unset($hwdata["LoadSampleRange_EndPositionValue"]);
        $pipe=\Import\Configure::processSample($hwdata, $isattack);
        if ($pipe) {
            if (($rankid=$hwdata["RankID"] % 100)==31) {
                $pipe->Percussive="Y";
            }
            if ($isattack && $rankid<80) {
                $pipe->MIDIKeyOverride=$or=floor($key=$this->samplePitchMidi($hwdata));
                $pipe->MIDIPitchFraction=100*($key-$or);
            }
        }
        return $pipe;
    }
}

class SwietaLipkaDemo extends SwietaLipka {

    const ODF="Swieta Lipka (demo).Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Swieta Lipka (demo - %s)" . self::VERSION;

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $hwdata["IsTremulant"]=$hwdata["PipeLayerNumber"]==2 ? 1 : 0;
        return parent::processSample($hwdata, $isattack);
    }

    public static function SwietaLipka(array $positions=[], string $target="") {
        self::$packageID="002520";
        \GOClasses\Noise::$blankloop="./OrganInstallationPackages/002520/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new SwietaLipkaDemo(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->save(sprintf(self::TARGET, $target), sprintf(self::COMMENTS, self::ODF));
        }
        else {
            /* self::SwietaLipka(
                    [1=>"(close)"],
                    "close");
            self::SwietaLipka(
                    [2=>"(front)"],
                    "front");
            self::SwietaLipka(
                    [3=>"(middle)"],
                    "middle");
            self::SwietaLipka(
                    [4=>"(rear)"],
                    "rear"); */
            self::$synthTremulant=FALSE;
            self::SwietaLipka(
                    [1=>"(close)", 2=>"(front)", 3=>"(middle)", 4=>"(rear)"],
                    "surround");
            self::$synthTremulant=TRUE;
            self::SwietaLipka(
                    [1=>"(close)", 2=>"(front)", 3=>"(middle)", 4=>"(rear)"],
                    "synth tremulant");
        }
    }
}

class SwietaLipkaFull extends SwietaLipka {

    const ODF="Swieta Lipka.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Swieta Lipka (%s)" . self::VERSION;

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $tremmed=$hwdata["IsTremulant"]=(intval($hwdata["RankID"]/100) % 10)==5 ? 1 : 0;
        if ($tremmed) {
            if (self::$synthTremulant) {return NULL;}
            $hwdata["RankID"]-=500;
        }
        return parent::processSample($hwdata, $isattack);
    }

    public static function SwietaLipka(array $positions=[], string $target="") {
        self::$packageID="002519";
        \GOClasses\Noise::$blankloop="./OrganInstallationPackages/002519/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new SwietaLipkaFull(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->save(sprintf(self::TARGET, $target), sprintf(self::COMMENTS, self::ODF));
        }
        else {
            self::$synthTremulant=FALSE;
            self::SwietaLipka(
                    [1=>"(close)", 2=>"(front)", 3=>"(middle)", 4=>"(rear)"],
                    "surround");
            self::SwietaLipka(
                    [1=>"(close)"],
                    "close");
            self::SwietaLipka(
                    [2=>"(front)"],
                    "front");
            self::SwietaLipka(
                    [3=>"(middle)"],
                    "middle");
            self::SwietaLipka(
                    [4=>"(rear)"],
                    "rear");
            self::$synthTremulant=TRUE;
            self::SwietaLipka(
                    [1=>"(close)", 2=>"(front)", 3=>"(middle)", 4=>"(rear)"],
                    "synth tremulant");
        }
    }
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}

SwietaLipkaFull::SwietaLipka();
SwietaLipkaDemo::SwietaLipka();
