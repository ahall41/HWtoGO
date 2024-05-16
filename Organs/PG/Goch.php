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
 * Import Gogh Demo
 */

class Goch extends PGOrgan {

    const ROOT="/GrandOrgue/Organs/PG/Goch/";
    const VERSION="1.2";
    const COMMENTS=
              "Pfarrkirche St. Maria Magdalena in Goch, Germany (%s)\n"
            . "https://piotrgrabowski.pl/goch/\n"
            . "\n"
            . "1.1 wave based tremulant model\n"
            . "    full surround included\n"
            . "    added crescendo control and program\n"
            . "1.2 Cross fades corrected for GO 3.14\n"
            . "\n";

    protected $combinations=[
        "crescendos"=>[
            "A"=>[1000,1002,1004,1005,1007,1008,1010,1011,
                  1013,1015,1016,1018,1019,1021,1022,1024,
                  1026,1027,1029,1030,1032,1033,1035,1036,
                  1038,1040,1041,1043,1044,1046,1047,1050]
                ]
        ];

    protected bool $switchedtremulants=FALSE;

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
                0=>["Group"=>"Simple", "Name"=>"1", "SetID"=>104364],
                1=>["Group"=>"Simple", "Name"=>"2", "SetID"=>104424],
                2=>["Group"=>"Simple", "Name"=>"3", "SetID"=>104484],
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
            1=>["TremulantID"=>1, "ControllingSwitchID"=>56, "Type"=>"Wave", "Name"=>"Recit", "GroupIDs"=>[301,302,303]],
    ];

    public $patchEnclosures=[
            1=>["Panels"=>[10=>11, 40=>121881, 41=>121881, 42=>121881], "GroupIDs"=>[301,302,303],  "AmpMinimumLevel"=>40],
          101=>["EnclosureID"=>101, "Name"=>"Close Ped",    "Panels"=>[60=>500], "GroupIDs"=>[101], "AmpMinimumLevel"=>0],
          102=>["EnclosureID"=>102, "Name"=>"Front Ped",    "Panels"=>[60=>510], "GroupIDs"=>[102], "AmpMinimumLevel"=>0],
          103=>["EnclosureID"=>103, "Name"=>"Rear Ped",     "Panels"=>[60=>520], "GroupIDs"=>[103], "AmpMinimumLevel"=>0],
          201=>["EnclosureID"=>201, "Name"=>"Close GO",     "Panels"=>[60=>501], "GroupIDs"=>[201], "AmpMinimumLevel"=>0],
          202=>["EnclosureID"=>202, "Name"=>"Front GO",     "Panels"=>[60=>511], "GroupIDs"=>[202], "AmpMinimumLevel"=>0],
          203=>["EnclosureID"=>203, "Name"=>"Rear GO",      "Panels"=>[60=>521], "GroupIDs"=>[203], "AmpMinimumLevel"=>0],
          301=>["EnclosureID"=>301, "Name"=>"Close R",      "Panels"=>[60=>502], "GroupIDs"=>[301], "AmpMinimumLevel"=>0],
          302=>["EnclosureID"=>302, "Name"=>"Front R",      "Panels"=>[60=>512], "GroupIDs"=>[302], "AmpMinimumLevel"=>0],
          303=>["EnclosureID"=>303, "Name"=>"Rear R",       "Panels"=>[60=>522], "GroupIDs"=>[303], "AmpMinimumLevel"=>0],
          401=>["EnclosureID"=>401, "Name"=>"Close SL",     "Panels"=>[60=>503], "GroupIDs"=>[401], "AmpMinimumLevel"=>0],
          402=>["EnclosureID"=>402, "Name"=>"Front SL",     "Panels"=>[60=>513], "GroupIDs"=>[402], "AmpMinimumLevel"=>0],
          403=>["EnclosureID"=>403, "Name"=>"Rear SL",      "Panels"=>[60=>523], "GroupIDs"=>[403], "AmpMinimumLevel"=>0],
          609=>["EnclosureID"=>609, "Name"=>"Key Actions",  "Panels"=>[60=>571], "GroupIDs"=>[601,602,603,604], "AmpMinimumLevel"=>0],
          709=>["EnclosureID"=>709, "Name"=>"Stop Actions", "Panels"=>[60=>572], "GroupIDs"=>[701,702,703,704], "AmpMinimumLevel"=>0],
          801=>["EnclosureID"=>801, "Name"=>"Ambient",      "Panels"=>[60=>573], "GroupIDs"=>[801,802,803,804], "AmpMinimumLevel"=>0],
          901=>["EnclosureID"=>901, "Name"=>"Close",        "Panels"=>[60=>540], "GroupIDs"=>[101,201,301,401], "AmpMinimumLevel"=>0],
          902=>["EnclosureID"=>902, "Name"=>"Front",        "Panels"=>[60=>541], "GroupIDs"=>[102,202,302,402], "AmpMinimumLevel"=>0],
          903=>["EnclosureID"=>903, "Name"=>"Rear",         "Panels"=>[60=>542], "GroupIDs"=>[103,203,303,403], "AmpMinimumLevel"=>0],
    ];

    protected $patchStops=[
        8001=>["StopID"=>   8001, "DivisionID"=>1, "Name"=>"Ambience (close)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
      108001=>["StopID"=> 108001, "DivisionID"=>1, "Name"=>"Ambience (front)",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
      208001=>["StopID"=> 208001, "DivisionID"=>1, "Name"=>"Ambience (rear)",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
        8002=>["StopID"=>   8002, "DivisionID"=>1, "Name"=>"Blower (close)",    "ControllingSwitchID"=>141,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
      108002=>["StopID"=> 108002, "DivisionID"=>1, "Name"=>"Blower (front)",    "ControllingSwitchID"=>141,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
      208002=>["StopID"=> 208002, "DivisionID"=>1, "Name"=>"Blower (rear)",     "ControllingSwitchID"=>141,  "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
        8003=>["StopID"=>   8003, "DivisionID"=>1, "Name"=>"Tremulant (close)", "ControllingSwitchID"=>56,   "Ambient"=>TRUE, "GroupID"=>901],
      108003=>["StopID"=> 108003, "DivisionID"=>1, "Name"=>"Tremulant (front)", "ControllingSwitchID"=>56,   "Ambient"=>TRUE, "GroupID"=>902],
      208003=>["StopID"=> 208003, "DivisionID"=>1, "Name"=>"Tremulant (rear)",  "ControllingSwitchID"=>56,   "Ambient"=>TRUE, "GroupID"=>903],
          83=>["StopID"=>   83, "DivisionID"=>1, "Name"=>"P Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          84=>["StopID"=>   84, "DivisionID"=>1, "Name"=>"P Key Off",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          85=>["StopID"=>   85, "DivisionID"=>2, "Name"=>"GO Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          86=>["StopID"=>   86, "DivisionID"=>2, "Name"=>"GO Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          87=>["StopID"=>   87, "DivisionID"=>3, "Name"=>"R Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          88=>["StopID"=>   88, "DivisionID"=>3, "Name"=>"R Key Off",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          89=>["StopID"=>   89, "DivisionID"=>4, "Name"=>"SL Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          90=>["StopID"=>   90, "DivisionID"=>4, "Name"=>"SL Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
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
        1089=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[87]],
        1090=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[88]],

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
            for ($r=1; $r<=$stop->NumberOfRanks; $r++) {
                $rn=sprintf("%03d", $r);
                $stop->unset("Rank${rn}PipeCount");
                $stop->unset("Rank${rn}FirstAccessibleKeyNumber");
                switch ($stopid) {
                    case 3: // P  Flûte 16'
                        $stop->set("Rank{$rn}",$stop->get("Rank{$rn}")-1);
                        break;

                    case 17: // GO  Cornet IV
                        $stop->set("Rank{$rn}FirstAccessibleKeyNumber", 13);
                        break;

                    case 36: // SL  Montre 8'
                        $stop->unset("Rank{$rn}FirstPipeNumber");
                        break;
                }
            }
        }

        foreach ([40=>121889] as $pageid=>$instanceid) {
            foreach([0,1,2] as $layoutid) {
                if (($panel=$this->getPanel($pageid+$layoutid, FALSE))) {
                    $cr=$panel->Element();
                    $cr->Type="Swell";
                    $this->configureEnclosureImage($cr, ["InstanceID"=>$instanceid], $layoutid);
                }
            }
        }
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

    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        $hwdata["ConditionSwitchID"]=$hwdata["ConditionSwitchID"] % 1000;
        if ($hwdata["ConditionSwitchID"]>200) $hwdata["ConditionSwitchID"]-=200;
        unset($hwdata["NumberOfKeys"]);
        return parent::createCoupler($hwdata);
    }

    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Noise {
        $method=$isattack ? "configureAttack" : "configureRelease";
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $type=$this->hwdata->rank($hwdata["RankID"])["Noise"];
        $midi=$hwdata["NormalMIDINoteNumber"];
        if ($type=="Ambient") {
            switch (($pipeid=$hwdata["PipeID"]) % 10000) {
                case 8001: // Ambience
                case 8002: // Blower
                case 8003: // Tremulant?
                    $stop=$this->getStop($hwdata["PipeID"]);
                    if ($stop) {
                        $ambience=$stop->Ambience();
                        $ambience->LoadRelease="Y";
                        $this->$method($hwdata, $ambience);
                    }
                    break;
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
        unset($hwdata["LoadSampleRange_EndPositionValue"]);
        if ($hwdata["PipeLayerNumber"]==2) $hwdata["IsTremulant"]=1;
        return parent::processSample($hwdata, $isattack);
    }
}

class GochDemo extends Goch {

    const ODF="Goch (demo).Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Goch (demo - %s) " . self::VERSION;

    public function patchData(\HWClasses\HWData $hwd): void {
        $index=10000;
        foreach ([2,5,6,11,12,14,16,19,22,23] as $stopid) {
            $nodes=($stopid<10 ? 29 : 61);
            $increment=($stopid==5 ? 12 : 0);
            foreach([0, 1000, 2000] as $baseid) {
                $rankid=$baseid + ($stopid==5 ? 2 : $stopid);
                $this->patchStopRanks[$index++]=[
                    "StopID"=>$stopid,
                    "RankID"=>$rankid,
                    "MIDINoteNumOfFirstMappedDivisionInputNode"=>36,
                    "NumberOfMappedDivisionInputNodes"=>$nodes,
                        "MIDINoteNumIncrementFromDivisionToRank"=>$increment];
            }
        }
        parent::patchData($hwd);
    }

    public static function Goch(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="./OrganInstallationPackages/002518/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new GochDemo(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->save(sprintf(self::TARGET, $target), sprintf(self::COMMENTS, self::ODF));
        }
        else {
            /* self::Goch(
                    [1=>"(close)"],
                    "close");
            self::Goch(
                    [2=>"(front)"],
                    "front");
            self::Goch(
                    [3=>"(rear)"],
                    "rear"); */
            self::Goch(
                    [1=>"(close)", 2=>"(front)", 3=>"(rear)"],
                    "surround");
        }
    }
}

class GochFull extends Goch {

    const ODF="Goch.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Goch (%s) " . self::VERSION;

    public function patchData(\HWClasses\HWData $hwd): void {
        $index=10000;
        for ($stopid=1; $stopid<=42; $stopid++) {
            // if ($stopid>=25 && $stopid!=36) continue;
            $nodes=($stopid<10 ? 29 : 61);
            $increment=($stopid==5 ? 12 : 0);
            foreach([0, 1000, 2000] as $baseid) {
                switch ($stopid) {
                    case  3: // P Flûte 16
                        $rankid=$baseid + 11;
                        $increment=0;
                        break;

                    case  4: // P Violonbasse 16’
                        $rankid=$baseid + 1;
                        $increment=12;
                        break;

                    case  5: // P Soubasse 16
                        $rankid=$baseid + 2;
                        $increment=12;
                        break;

                    case 36: // SL Montre 8
                        $rankid=$baseid + 11;
                        $increment=12;
                        break;

                    default:
                        $rankid=$baseid + $stopid;
                        $increment=0;
                        break;
                }

                $this->patchStopRanks[$index++]=[
                    "StopID"=>$stopid,
                    "RankID"=>$rankid,
                    "MIDINoteNumOfFirstMappedDivisionInputNode"=>36,
                    "NumberOfMappedDivisionInputNodes"=>$nodes,
                    "MIDINoteNumIncrementFromDivisionToRank"=>$increment];
            }
        }
        parent::patchData($hwd);
    }

    public static function Goch(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="./OrganInstallationPackages/002518/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new GochFull(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->save(sprintf(self::TARGET, $target), sprintf(self::COMMENTS, self::ODF));
        }
        else {
            self::Goch(
                    [1=>"(close)"],
                    "close");
            self::Goch(
                    [2=>"(front)"],
                    "front");
            self::Goch(
                    [3=>"(rear)"],
                    "rear");
            self::Goch(
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

GochFull::Goch();
GochDemo::Goch();