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
 * @todo: Appelles
 */

class Nancy extends PGOrgan {

    const ROOT="/GrandOrgue/Organs/PG/NancyFull/";
    const COMMENTS=
              "Nancy, Cathédrale Notre-Dame-de-l'Annonciation, France (%s)\n"
            . "https://piotrgrabowski.pl/nancy/\n"
            . "\n"
            . "1.1 Functional couplers; Wave based tremulant (on Positif)\n"
            . "1.2 Added full surround\n"
            . "    Added crescendo program\n"
            . "\n";
    
    const VERSION="1.2";

    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    protected bool $switchedtremulants=FALSE;

    public $positions=[];
    
    private $appelles=[ // switchid => [data]
        80=>["stops"=>[8,9,10,12,14],   "instances"=>[10=>80, 40=>344906], "Name"=>"Anches 32"],
        81=>["stops"=>[11,13,15],       "instances"=>[10=>81, 40=>344907], "Name"=>"Anches Pédale"],
        82=>["stops"=>[26,27,28,29],    "instances"=>[10=>82, 40=>344908], "Name"=>"Anches Positif"],
        83=>["stops"=>[61,62,63,64,65], "instances"=>[10=>83, 40=>344909], "Name"=>"Anches Récit expressif"],
    ];
    
    protected $combinations=[
        "crescendos"=>[
            "A"=>[1000,1002,1004,1006,1008,1010,1012,1014,1016,1018,
                  1020,1022,1024,1026,1012,1030,1032,1034,1036,1038,
                  1040,1042,1045,1048,1051,1054,1057,1060,1063,1066,
                  1069,1072]
                ]
        ];

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
                1=>["Group"=>"Left", "Name"=>"2",   "SetID"=>2200, "Display_ConsoleScreenWidthPixels"=>1920, "Display_ConsoleScreenHeightPixels"=>1440],
                2=>["Group"=>"Left", "Name"=>"3",   "SetID"=>2400, "Display_ConsoleScreenWidthPixels"=>1440, "Display_ConsoleScreenHeightPixels"=>2560],
               ],
            3=>[
                0=>["Group"=>"Right", "Name"=>"1",  "SetID"=>3000],
                1=>["Group"=>"Right", "Name"=>"2",  "SetID"=>3200, "Display_ConsoleScreenWidthPixels"=>1920, "Display_ConsoleScreenHeightPixels"=>1440],
                2=>["Group"=>"Right", "Name"=>"3",  "SetID"=>3400, "Display_ConsoleScreenWidthPixels"=>1440, "Display_ConsoleScreenHeightPixels"=>2560],
               ],
            4=>[
                0=>["Group"=>"Simple", "Name"=>"1", "SetID"=>93947],
                1=>["Group"=>"Simple", "Name"=>"2", "SetID"=>94032, "Display_ConsoleScreenWidthPixels"=>1920, "Display_ConsoleScreenHeightPixels"=>1440],
                2=>["Group"=>"Simple", "Name"=>"3", "SetID"=>94117, "Display_ConsoleScreenWidthPixels"=>1440, "Display_ConsoleScreenHeightPixels"=>2560],
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
          101=>["EnclosureID"=>101, "Name"=>"Close Ped",    "Panels"=>[60=>500], "GroupIDs"=>[101], "AmpMinimumLevel"=>1],
          102=>["EnclosureID"=>102, "Name"=>"Front Ped",    "Panels"=>[60=>510], "GroupIDs"=>[102], "AmpMinimumLevel"=>1],
          103=>["EnclosureID"=>103, "Name"=>"Middle Ped",   "Panels"=>[60=>520], "GroupIDs"=>[103], "AmpMinimumLevel"=>1],
          104=>["EnclosureID"=>104, "Name"=>"Rear Ped",     "Panels"=>[60=>530], "GroupIDs"=>[104], "AmpMinimumLevel"=>1],
          201=>["EnclosureID"=>201, "Name"=>"Close Pos",    "Panels"=>[60=>501], "GroupIDs"=>[201], "AmpMinimumLevel"=>1],
          202=>["EnclosureID"=>202, "Name"=>"Front Pos",    "Panels"=>[60=>511], "GroupIDs"=>[202], "AmpMinimumLevel"=>1],
          203=>["EnclosureID"=>203, "Name"=>"Middle Pos",   "Panels"=>[60=>521], "GroupIDs"=>[203], "AmpMinimumLevel"=>1],
          204=>["EnclosureID"=>204, "Name"=>"Rear Pos",     "Panels"=>[60=>531], "GroupIDs"=>[204], "AmpMinimumLevel"=>1],
          301=>["EnclosureID"=>301, "Name"=>"Close GO",     "Panels"=>[60=>502], "GroupIDs"=>[301], "AmpMinimumLevel"=>1],
          302=>["EnclosureID"=>302, "Name"=>"Front GO",     "Panels"=>[60=>512], "GroupIDs"=>[302], "AmpMinimumLevel"=>1],
          303=>["EnclosureID"=>303, "Name"=>"Middle GO",    "Panels"=>[60=>522], "GroupIDs"=>[303], "AmpMinimumLevel"=>1],
          304=>["EnclosureID"=>304, "Name"=>"Rear GO",      "Panels"=>[60=>532], "GroupIDs"=>[304], "AmpMinimumLevel"=>1],
          401=>["EnclosureID"=>401, "Name"=>"Close Bombd",  "Panels"=>[60=>503], "GroupIDs"=>[401], "AmpMinimumLevel"=>1],
          402=>["EnclosureID"=>402, "Name"=>"Front Bombd",  "Panels"=>[60=>513], "GroupIDs"=>[402], "AmpMinimumLevel"=>1],
          403=>["EnclosureID"=>403, "Name"=>"Middle Bombd", "Panels"=>[60=>523], "GroupIDs"=>[403], "AmpMinimumLevel"=>1],
          404=>["EnclosureID"=>404, "Name"=>"Rear Bombd",   "Panels"=>[60=>533], "GroupIDs"=>[404], "AmpMinimumLevel"=>1],
          501=>["EnclosureID"=>501, "Name"=>"Close Rec",    "Panels"=>[60=>504], "GroupIDs"=>[501], "AmpMinimumLevel"=>1],
          502=>["EnclosureID"=>502, "Name"=>"Front Rec",    "Panels"=>[60=>514], "GroupIDs"=>[502], "AmpMinimumLevel"=>1],
          503=>["EnclosureID"=>503, "Name"=>"Middle Rec",   "Panels"=>[60=>524], "GroupIDs"=>[503], "AmpMinimumLevel"=>1],
          504=>["EnclosureID"=>504, "Name"=>"Rear Rec",     "Panels"=>[60=>534], "GroupIDs"=>[504], "AmpMinimumLevel"=>1],
          609=>["EnclosureID"=>609, "Name"=>"Key Actions",  "Panels"=>[60=>571], "GroupIDs"=>[701,702,703,704], "AmpMinimumLevel"=>1],
          709=>["EnclosureID"=>709, "Name"=>"Stop Actions", "Panels"=>[60=>572], "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>1],
          801=>["EnclosureID"=>801, "Name"=>"Blower",       "Panels"=>[60=>573], "GroupIDs"=>[901,902,903,904], "AmpMinimumLevel"=>1],
          901=>["EnclosureID"=>901, "Name"=>"Close",        "Panels"=>[60=>540], "GroupIDs"=>[101,201,301,401,501], "AmpMinimumLevel"=>1],
          902=>["EnclosureID"=>902, "Name"=>"Front",        "Panels"=>[60=>541], "GroupIDs"=>[102,202,302,402,502], "AmpMinimumLevel"=>1],
          903=>["EnclosureID"=>903, "Name"=>"Middle",       "Panels"=>[60=>542], "GroupIDs"=>[103,203,303,403,503], "AmpMinimumLevel"=>1],
          904=>["EnclosureID"=>904, "Name"=>"Rear",         "Panels"=>[60=>543], "GroupIDs"=>[104,204,304,404,504], "AmpMinimumLevel"=>1],
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

    public function import(): void {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                //case 1:
                //case 4:
                case 6:
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
            for ($i=1; $i<6; $i++) {
                $stop->unset("Rank00${i}PipeCount");
                $stop->unset("Rank00${i}FirstAccessibleKeyNumber");
            }
            switch ($stopid) {
                case 24: // PO  Cornet 5
                case 40: // GO Septième 1 1/7
                case 44: // B  Cornet 5
                case 59: // R  Cornet 5
                    $stop->FirstAccessiblePipeLogicalKeyNumber=25;
                    break;
            }
        }
        
        $this->getSwitch(20070)->DisplayInInvertedState="Y";
        
        foreach ($this->getManuals() as $manualid=>$manual) {
            $manual->NumberOfLogicalKeys=$manual->NumberOfAccessibleKeys=$manualid==1 ? 32 : 61;
        }

        foreach ([40=>101681] as $pageid=>$instanceid) {
            foreach([0,1,2] as $layoutid) {
                if (($panel=$this->getPanel($pageid+$layoutid, FALSE))) {
                    $cr=$panel->Element();
                    $cr->Type="Swell";
                    $this->configureEnclosureImage($cr, ["InstanceID"=>$instanceid], $layoutid);
                }
            }
        }
        
        foreach ($this->appelles as $switchid=>$data) {
            $switch=$this->newSwitch($switchid, $data["Name"]);
            foreach ($data["instances"] as $pageid=>$instanceid) {
                foreach([0,1,2] as $layoutid) {
                    if (($panel=$this->getPanel($pageid+$layoutid, FALSE))) {
                        $pe=$panel->GUIElement($switch);
                        $this->configureImage($pe, ["SwitchID"=>$instanceid], $layoutid);
                    }
                }
            }
            
            foreach ($data["stops"] as $stopid) {
                if (($stop=$this->getStop($stopid))) {
                    $stop->Switch($switch);
                }
            }
        }
        
        /* // Read tuning from Front rank
        static $pitchtuning=[];
        if (sizeof($pitchtuning)==0) {
            foreach($this->getStops() as $stopid=>$stop) { // Direct
                if (($rank=$this->getRank(1000+$stopid))) {
                    foreach($rank->Pipes() as $key=>$pipe) {
                        $pitchtuning[$stopid][$key]=$pipe->PitchTuning;
                    }
                }
            }
        }
  
        // Apply to Front perspective
        foreach($this->getStops() as $stopid=>$stop) { // Direct
            if (($rank=$this->getRank($stopid))) {
                foreach($rank->Pipes() as $key=>$pipe) {
                    $pipe->PitchTuning=$pitchtuning[$stopid][$key];
                }
            }
        } */

        foreach($this->getStops() as $stopid=>$stop) {
            if ($stopid>80) {continue;}
            foreach([0,1000,2000,3000] as $rbase) {
                if (($rank=$this->getRank($stopid+$rbase))) {
                    //echo $stopid+$rbase, "\t", $rank->Name, "\n";
                    foreach($rank->Pipes() as $key=>$pipe) {
                        $skey=$this->sampleMidiKey(["SampleFilename"=>$pipe->Attack]);
                        if ($skey==$key) {continue;}
                        $pt=$rank->Pipe($skey)->PitchTuning;
                        //if ($key==90) echo $key, "\t", $skey, "\t", $pipe->PitchTuning, "\t";
                        $pipe->PitchTuning=(empty($pt) ? 0 : $pt) + (100*($key-$skey));
                        //if ($key==90) echo $pipe->PitchTuning, "\t", $pt, "\t", $pipe->Attack, "\n";
                    }
                }
            }
        }

        /* foreach($this->getStops() as $stopid=>$stop) {
            $close=$this->getRank($stopid);
            $front=$this->getRank(1000+$stopid);
            $middle=$this->getRank(2000+$stopid);
            $rear=$this->getRank(3000+$stopid);
            for($key=86; $key<=99; $key++) {
                if ($close && ($pipe=$close->Pipe($key))) {
                    echo "Close\t$key\t", $pipe->PitchTuning, "\t", $pipe->Attack, "\n";
                }
                if ($front && ($pipe=$front->Pipe($key))) {
                    echo "Front\t$key\t", $pipe->PitchTuning, "\t", $pipe->Attack, "\n";
                }
                if ($middle && ($pipe=$middle->Pipe($key))) {
                    echo "Middle\t$key\t", $pipe->PitchTuning, "\t", $pipe->Attack, "\n";
                }
                if ($rear && ($pipe=$rear->Pipe($key))) {
                    echo "Rear\t$key\t", $pipe->PitchTuning, "\t", $pipe->Attack, "\n";
                }
            }
        } */
        
        /* foreach($this->getStops() as $stopid=>$stop) {
            //
            $pipes=[];
            foreach([0,1000,2000,3000] as $rbase) {
                if (($rank=$this->getRank($stopid+$rbase)) && sizeof($rank->Pipes())>0) {
                    $pipes[]=$rank->Pipes();
                }
            }
            
            if (sizeof($pipes)>0) {
                //foreach ($pipes as $p) {
                //    echo sizeof($p), "\t";
                //}
                //echo $stop->Name, "\n";
                
                foreach ($pipes as $ps) {
                    foreach ($ps as $k=>$p) {
                        if ($p->PitchTuning != $pipes[0][$k]->PitchTuning) {
                            echo $k, ":\t", $p->PitchTuning, " (", $p->Attack, ") != ",
                                 $pipes[0][$k]->PitchTuning, " (", $pipes[0][$k]->Attack, ")\n";
                        }
                    }
                }
            }
        } */
        
        foreach ($this->getStops() as $stopid=>$stop) {
            if ($stopid>80) continue;
            $direct=$this->getRank($stopid)->Pipes();
            $front=$this->getRank(1000+$stopid)->Pipes();
            $middle=$this->getRank(2000+$stopid)->Pipes();
            $rear=$this->getRank(3000+$stopid)->Pipes();
            foreach ($direct as $key=>$pipe) {
                $d=$direct[$key]->PitchTuning;
                $f=$front[$key]->PitchTuning;
                $m=$middle[$key]->PitchTuning;
                $r=$rear[$key]->PitchTuning;
                
                if (!(($d==$f) && ($d==$m) && ($d==$r))) {
                    echo "$stopid\t$key\t$d\t$f\t$m\t$r\t", $stop->Name, "\n";
                }
            }
            
        }
        
        exit();
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

    public function createManual(array $hwdata): ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]>5) return NULL;
        return parent::createManual($hwdata);
    }

    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["SourceKeyboardID"]==6) $hwdata["SourceKeyboardID"]=3;
        if ($hwdata["DestKeyboardID"]==6) $hwdata["DestKeyboardID"]=3;
        return parent::createCoupler($hwdata);
    }
    
    protected function configurePanelSwitchImage
            (\GOClasses\Panel $panel, 
            \GOClasses\Sw1tch $switch, 
            int $switchid, int $layout) : ? \GOClasses\PanelElement {
        if ($switchid==142) {return NULL;}
        return parent::configurePanelSwitchImage($panel, $switch, $switchid, $layout);
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        $data["SwitchID"]=$data["SwitchID"] % 1000;
        parent::configurePanelSwitchImages($switch, $data);
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        static $trem=[15,17,18,19,20,21,22,23,24,25,26,27,28,29];
        unset($hwdata["LoadSampleRange_EndPositionValue"]);
        if (!isset($hwdata["PipeLayerNumber"])) {$hwdata["PipeLayerNumber"]=1;}
        if (!isset($hwdata["NormalMIDINoteNumber"])) $hwdata["NormalMIDINoteNumber"]=60;
        if (in_array($hwdata["RankID"] % 100, $trem)) {
            $hwdata["IsTremulant"]=$hwdata["PipeLayerNumber"]==2 ? 1 : 0;
        }
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe && $isattack && (($hwdata["RankID"] % 100)<80)) {
            $pipe->MIDIKeyOverride=$or=floor($key=$this->samplePitchMidi($hwdata));
            $pipe->MIDIPitchFraction=100*($key-$or);
            //echo $this->readSamplePitch(self::ROOT . $pipe->Attack), "\t", $hwdata["Pitch_ExactSamplePitch"], "\t",
            //        $pipe->Attack, "\n";
        }
        return $pipe;
    }
}

class NancyDemo extends Nancy {

    const ODF="Nancy (demo).Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Nancy (demo - %s) " . self::VERSION;

    public static function Nancy(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="./OrganInstallationPackages/002514/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new NancyDemo(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->save(sprintf(self::TARGET, $target), sprintf(self::COMMENTS, self::ODF));
        }
        else { /*
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
                    "rear"); */
            self::Nancy(
                    [1=>"(close)", 2=>"(front)", 3=> "(middle)", 4=>"(rear)"],
                    "surround");
        }
    }
}

class NancyFull extends Nancy {

    const ODF="Nancy.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Nancy (%s)" . self::VERSION;

    public static function Nancy(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="./OrganInstallationPackages/002513/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new NancyFull(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName .= " ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->save(sprintf(self::TARGET, $target), sprintf(self::COMMENTS, self::ODF));
        }
        else {
            self::Nancy(
                    [1=>"(close)", 2=>"(front)", 3=> "(middle)", 4=>"(rear)"],
                    "surround");
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
        }
    }
}

NancyFull::Nancy();
NancyDemo::Nancy();