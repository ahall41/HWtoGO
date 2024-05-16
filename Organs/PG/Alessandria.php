<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\PG;
require_once __DIR__ . "/../../Import/Organ.php";

/**
 * Import Alessandria Demo. This is primarily a test, as Alessandria
 * is already available for GO. 
 */

class Alessandria extends \Import\Organ {

    const ROOT="/GrandOrgue/Organs/PG/Alessandria/";
    const SOURCE=self::ROOT . "OrganDefinitions/Alessandria (demo).Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Alessandria (demo - %s) 0.1.organ";
    const PITCHDATA=self::ROOT . "PitchData.csv";
    
    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    
    public $positions=[];

    public $patchDivisions=[
            5=>"DELETE", // Tuba
            7=>["DivisionID"=>7, "Name"=>"Key Actions"],
            8=>["DivisionID"=>8, "Name"=>"Stop Actions"],
            9=>["DivisionID"=>9, "Name"=>"Blower"]
        ];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1000,  "SwitchID"=>0]
               ],
            2=>[
                0=>["Group"=>"Left Jamb", "Name"=>"1",   "SetID"=>2000], // "SwitchID"=>2000],
                1=>["Group"=>"Left Jamb", "Name"=>"2",   "SetID"=>2200], // "SwitchID"=>2000],
                2=>["Group"=>"Left Jamb", "Name"=>"3",   "SetID"=>2400], // "SwitchID"=>2000]
               ],
            3=>[
                0=>["Group"=>"Right Jamb", "Name"=>"1",  "SetID"=>3000], // "SwitchID"=>3000],
                1=>["Group"=>"Right Jamb", "Name"=>"2",  "SetID"=>3200], // "SwitchID"=>3000],
                2=>["Group"=>"Right Jamb", "Name"=>"3",  "SetID"=>3400], // "SwitchID"=>3000]
               ],
            4=>[
                0=>["Group"=>"Simple Jamb", "Name"=>"1", "SetID"=>75195], // "SwitchID"=>327795],
                1=>["Group"=>"Simple Jamb", "Name"=>"2", "SetID"=>75266], // "SwitchID"=>327795],
                2=>["Group"=>"Simple Jamb", "Name"=>"3", "SetID"=>75337], // "SwitchID"=>327795],
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
            69=>["ControllingSwitchID"=>69, "Type"=>"Switched", "Name"=>"GO Tremulant", "DivisionID"=>2],
            70=>["ControllingSwitchID"=>70, "Type"=>"Switched", "Name"=>"Pos Tremulant", "DivisionID"=>3],
            71=>["ControllingSwitchID"=>71, "Type"=>"Switched", "Name"=>"Rec Tremulant", "DivisionID"=>4],
    ];
    
    public $patchEnclosures=[
            1=>["Panels"=>[10=>11, 40=>84582, 41=>84582, 42=>84582], "GroupIDs"=>[401,402,403]],
          101=>["EnclosureID"=>101, "Name"=>"Close Ped",    "Panels"=>[60=>500], "GroupIDs"=>[101]],
          102=>["EnclosureID"=>102, "Name"=>"Front Ped",    "Panels"=>[60=>510], "GroupIDs"=>[102]],
          103=>["EnclosureID"=>103, "Name"=>"Rear Ped",     "Panels"=>[60=>520], "GroupIDs"=>[103]],
          201=>["EnclosureID"=>201, "Name"=>"Close GO",     "Panels"=>[60=>501], "GroupIDs"=>[201]],
          202=>["EnclosureID"=>202, "Name"=>"Front GO",     "Panels"=>[60=>511], "GroupIDs"=>[202]],
          203=>["EnclosureID"=>203, "Name"=>"Rear GO",      "Panels"=>[60=>521], "GroupIDs"=>[203]],
          301=>["EnclosureID"=>301, "Name"=>"Close Pos",    "Panels"=>[60=>502], "GroupIDs"=>[301]],
          302=>["EnclosureID"=>302, "Name"=>"Front Pos",    "Panels"=>[60=>512], "GroupIDs"=>[302]],
          303=>["EnclosureID"=>303, "Name"=>"Rear Pos",     "Panels"=>[60=>522], "GroupIDs"=>[303]],
          401=>["EnclosureID"=>401, "Name"=>"Close Rec",    "Panels"=>[60=>503], "GroupIDs"=>[401]],
          402=>["EnclosureID"=>402, "Name"=>"Front Rec",    "Panels"=>[60=>513], "GroupIDs"=>[402]],
          403=>["EnclosureID"=>403, "Name"=>"Rear Rec",     "Panels"=>[60=>523], "GroupIDs"=>[403]],
          501=>["EnclosureID"=>501, "Name"=>"Close Tuba",   "Panels"=>[60=>504], "GroupIDs"=>[501]],
          502=>["EnclosureID"=>502, "Name"=>"Front Tuba",   "Panels"=>[60=>514], "GroupIDs"=>[502]],
          503=>["EnclosureID"=>503, "Name"=>"Rear Tuba",    "Panels"=>[60=>524], "GroupIDs"=>[503]],
          609=>["EnclosureID"=>609, "Name"=>"Key Actions",  "Panels"=>[60=>571], "GroupIDs"=>[601,602,603]],
          709=>["EnclosureID"=>709, "Name"=>"Stop Actions", "Panels"=>[60=>572], "GroupIDs"=>[701,702,703]],
          801=>["EnclosureID"=>801, "Name"=>"Blower",       "Panels"=>[60=>573], "GroupIDs"=>[801,802,803]],
          901=>["EnclosureID"=>901, "Name"=>"Close",        "Panels"=>[60=>540], "GroupIDs"=>[101,201,301,401,501]],
          902=>["EnclosureID"=>902, "Name"=>"Front",        "Panels"=>[60=>541], "GroupIDs"=>[102,202,302,402,502]],
          903=>["EnclosureID"=>903, "Name"=>"Rear",         "Panels"=>[60=>542], "GroupIDs"=>[103,203,303,403,503]],
    ];
    
    protected $patchStops=[
           51=>"DELETE",
          80=>["StopID"=>   80, "DivisionID"=>1, "Name"=>"Ambient (close)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
        1080=>["StopID"=> 1080, "DivisionID"=>1, "Name"=>"Ambient (front)",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
        2080=>["StopID"=> 2080, "DivisionID"=>1, "Name"=>"Ambient (rear)",      "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
          81=>["StopID"=>   81, "DivisionID"=>1, "Name"=>"Main Motor (close)",  "ControllingSwitchID"=>101, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
        1081=>["StopID"=> 1081, "DivisionID"=>1, "Name"=>"Main Motor (front)",  "ControllingSwitchID"=>101, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
        2081=>["StopID"=> 2081, "DivisionID"=>1, "Name"=>"Main Motor (rear)",   "ControllingSwitchID"=>101, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
          82=>["StopID"=>   82, "DivisionID"=>1, "Name"=>"Tuba Motor (close)",  "ControllingSwitchID"=>102, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>901],
        1082=>["StopID"=> 1082, "DivisionID"=>1, "Name"=>"Tuba Motor (front)",  "ControllingSwitchID"=>102, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>902],
        2082=>["StopID"=> 2082, "DivisionID"=>1, "Name"=>"Tuba Motor (rear)",   "ControllingSwitchID"=>102, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>903],
          83=>["StopID"=>   83, "DivisionID"=>1, "Name"=>"P Key On",            "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          84=>["StopID"=>   84, "DivisionID"=>1, "Name"=>"P Key Off",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          85=>["StopID"=>   85, "DivisionID"=>2, "Name"=>"GO Key On",           "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          86=>["StopID"=>   86, "DivisionID"=>2, "Name"=>"GO Key Off",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          87=>["StopID"=>   87, "DivisionID"=>3, "Name"=>"POS Key On",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          88=>["StopID"=>   88, "DivisionID"=>3, "Name"=>"POS Key Off",         "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          89=>["StopID"=>   89, "DivisionID"=>4, "Name"=>"REC Key On",          "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
          90=>["StopID"=>   90, "DivisionID"=>4, "Name"=>"REC Key Off",         "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];

    protected $patchRanks=[
          80=>["Noise"=>"Ambient",    "GroupID"=>901, "StopIDs"=>[]],
          83=>["Noise"=>"KeyOn",      "GroupID"=>701, "StopIDs"=>[83]],
          84=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[84]],
          85=>["Noise"=>"KeyOn",      "GroupID"=>701, "StopIDs"=>[85]],
          86=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[86]],
          87=>["Noise"=>"KeyOn",      "GroupID"=>701, "StopIDs"=>[87]],
          88=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[88]],
          89=>["Noise"=>"KeyOn",      "GroupID"=>701, "StopIDs"=>[89]],
          90=>["Noise"=>"KeyOff",     "GroupID"=>701, "StopIDs"=>[90]],
          91=>["Noise"=>"StopOn",     "GroupID"=>801, "StopIDs"=>[]],
          93=>["Noise"=>"StopOff",    "GroupID"=>801, "StopIDs"=>[]],
        
        1080=>["Noise"=>"Ambient",    "GroupID"=>902, "StopIDs"=>[]],
        1083=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[1083]],
        1084=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[1084]],
        1085=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[1085]],
        1086=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[1086]],
        1087=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[1087]],
        1088=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[1088]],
        1089=>["Noise"=>"KeyOn",      "GroupID"=>702, "StopIDs"=>[1089]],
        1090=>["Noise"=>"KeyOff",     "GroupID"=>702, "StopIDs"=>[1090]],
        1091=>["Noise"=>"StopOn",     "GroupID"=>802, "StopIDs"=>[]],
        1093=>["Noise"=>"StopOff",    "GroupID"=>802, "StopIDs"=>[]],

        2080=>["Noise"=>"Ambient",    "GroupID"=>903, "StopIDs"=>[]],
        2083=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[2083]],
        2084=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[2084]],
        2085=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[2085]],
        2086=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[2086]],
        2087=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[2087]],
        2088=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[2088]],
        2089=>["Noise"=>"KeyOn",      "GroupID"=>703, "StopIDs"=>[2089]],
        2090=>["Noise"=>"KeyOff",     "GroupID"=>703, "StopIDs"=>[2090]],
        2091=>["Noise"=>"StopOn",     "GroupID"=>803, "StopIDs"=>[]],
        2093=>["Noise"=>"StopOff",    "GroupID"=>803, "StopIDs"=>[]],

    ];
    
    public function import(): void {
        $this->readLAPitches(self::PITCHDATA);
        parent::import();
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
    
    /*
     * Create Windchest Groups. 1 per position/division
     */
    public function createWindchestGroup(array $divisiondata): ?\GOClasses\WindchestGroup {
        $divid=$divisiondata["DivisionID"];
        $divname=$divisiondata["Name"];
        foreach($this->positions as $posid=>$posname) {
            $divisiondata["Name"]="$divname $posname";
            $divisiondata["GroupID"]=$posid + ($divid*100);
            parent::createWindchestGroup($divisiondata);
        }
        return NULL;
    }

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        if (isset($data["Panels"])) {
            foreach($data["Panels"] as $panelid=>$instanceid) {
                $pe=$this->getPanel($panelid)->GUIElement($enclosure);
                $this->configureEnclosureImage($pe, ["InstanceID"=>$instanceid], $panelid % 10);
            }
        }
    }
    
    /*
     * Compare with AVOrgan ...
     */
    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        static $layouts=
                [0=>"", 1=>"AlternateScreenLayout1_",
                 2=>"AlternateScreenLayout2_", 3=>"AlternateScreenLayout3_"];
        if (empty($data["SwitchID"])) return;

        // All switches associated with this one
        $switchids[$data["SwitchID"]]=$data["SwitchID"];
        $links=$this->hwdata->switchLink($data["SwitchID"]);
        if (isset($links["S"])) {
            foreach($links["S"] as $link) 
                $switchids[$link["DestSwitchID"]]=$link["DestSwitchID"];
        }
        if (isset($links["D"])) {
            foreach($links["D"] as $link) 
                $switchids[$link["SourceSwitchID"]]=$link["SourceSwitchID"];
        }
        
        foreach($switchids as $switchid) {
            $switchdata=$this->hwdata->switch($switchid);
            if (isset($switchdata["Disp_ImageSetInstanceID"]) 
                    && !empty($switchdata["Disp_ImageSetInstanceID"])) {
                $instance=$this->hwdata->imageSetInstance($switchdata["Disp_ImageSetInstanceID"]);
                foreach($layouts as $layout=>$prefix) {
                    if (isset($instance["${prefix}ImageSetID"]) &&
                            !empty($instance["${prefix}ImageSetID"])) {
                        $panelid=$instance["DisplayPageID"]*10+$layout;
                        if ($switch===NULL) 
                            $this->createPanelImage(
                                    $this->getPanel($panelid), 
                                    ["SwitchID"=>$switchid, "ImageIDX"=>2], 
                                    $layout);
                        else {
                            $panelelement=$this->getPanel($panelid)->GUIElement($switch);
                            $this->configureImage(
                                    $panelelement, 
                                    ["SwitchID"=>$switchid], 
                                    $layout);
                        }
                    }
                }
            }
        }
    }

    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["DestDivisionID"]==5)
            return NULL;
        else {
            $hwdata["ConditionSwitchID"]=$hwdata["ConditionSwitchID"] % 1000;
            return parent::createCoupler ($hwdata);
        }
    }

    public function createSwitchNoise(string $type, array $switchdata): void { 
        $switchid=NULL;
        switch ($type) {
            case self::TremulantNoise:
            case self::SwitchNoise:
                $switchid=$switchdata["ControllingSwitchID"];
                break;
            
            case self::CouplerNoise:
                $switchid=$switchdata["ConditionSwitchID"] % 1000;
                break;
        }
        
        if ($switchid!==NULL && ($switch=$this->getSwitch($switchid, FALSE))!==NULL) {
            foreach ($this->positions as $posid=>$position) {
                $name=$switch->Name . " $position";
                if (isset($switchdata["GroupID"]))
                    $groupid=$switchdata["GroupID"];
                else
                    $groupid=800+$posid;
                $windchestgroup=$this->getWindchestGroup($groupid);
                if ($windchestgroup!==NULL 
                        && $this->getSwitchNoise(($switchid*10)+$posid, FALSE)===NULL) {
                    $manual=$this->getManual(
                            isset($switchdata["DivisionID"]) && !empty($switchdata["DivisionID"])
                            ? $switchdata["DivisionID"] : 1);
                    $on=$this->newSwitchNoise(+(($switchid*10)+$posid), "$name $position (on)");
                    $on->WindchestGroup($windchestgroup);
                    $on->Switch($switch);
                    $manual->Stop($on);
                    $off=$this->newSwitchNoise(-(($switchid*10)+$posid), "$name $position (off)");
                    $off->WindchestGroup($windchestgroup);
                    $manual->Stop($off);
                    $off->Function="Not";
                    $off->Switch($switch);
                    unset($off->SwitchCount);
                }
            }
        }
    }  
    
    protected function stopInUse(array $hwdata) : bool {
        if (isset($this->patchStops[$hwdata["StopID"]]["StopID"]))
            return TRUE;
        else
            return parent::stopInUse($hwdata);
    }
    
    public function createStop(array $hwdata) : ?\GOClasses\Sw1tch {
        if (isset($this->patchStops[$hwdata["StopID"]]))
            return parent::createStop($hwdata);
        else {
            $hwdata["ControllingSwitchID"]=$hwdata["StopID"];
            $switch=parent::createStop($hwdata);
            if ($switch===NULL) return NULL;

            $divid=$hwdata["DivisionID"];
            $tremid=NULL;
            foreach($this->patchTremulants as $id=>$data) {
                if ($data["DivisionID"]==$divid) {
                    $tremid=$id;
                    break;
                }
            }

            if ($tremid!==NULL) {
                $stopid=$hwdata["StopID"];
                $this->getStop($stopid)->Switch($this->getSwitch(-$tremid));
                $hwdata["StopID"]=-$stopid;   // tremmed stops
                $hwdata["Name"].= " (tremulant)";
                parent::createStop($hwdata);
                $this->getStop(-$stopid)->Switch($this->getSwitch(+$tremid));
            }
            return $switch;
        }
   }
    
    public function createRank(array $hwdata, bool $keynoise=FALSE): ?\GOClasses\Rank {
        $rankid=$hwdata["RankID"];
        if (!isset($hwdata["StopIDs"])) {
            $hwdata["StopIDs"]=[];
            foreach ($this->hwdata->rankStop($rankid) as $rankstop) {
                $hwdata["StopIDs"][]=$stopid=$rankstop["StopID"];
                $stop=$this->hwdata->stop($stopid, FALSE);
                if ($stop!==NULL) {
                    $division=$stop["DivisionID"];
                    $hwdata["GroupID"]=($division*100) +1 +intval($rankid/1000);
                }
            }            
        }
        if (isset($hwdata["GroupID"])) {
            $rank=parent::createRank($hwdata);
            $stopids=[];
            foreach($hwdata["StopIDs"] as $id=>$stopid) {
                if ($this->getStop(-$stopid)!==NULL) 
                    $stopids[$id]=-$stopid;
            }
            if (sizeof($stopids)>0) {
                $hwdata["StopIDs"]=$stopids;
                $hwdata["RankID"]=-$hwdata["RankID"];
                $hwdata["Name"].=" (tremulant)";
                parent::createRank($hwdata);
            }
            return $rank;
        }
        else
            return NULL;
    }
    
    /*
     * May be OK for other providers 
     */
    protected function isNoiseSample(array $hwdata): bool {
        return isset(($rankdata=$this->hwdata->rank($hwdata["RankID"]))["Noise"])
                && in_array($rankdata["Noise"], ["StopOn","StopOff","Ambient"]);
    }

    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Noise {
        $method=$isattack ? "configureAttack" : "configureRelease";
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $type=$this->hwdata->rank($hwdata["RankID"])["Noise"];
        $midi=$hwdata["NormalMIDINoteNumber"];
        if ($type=="Ambient" && in_array($midi,[36,37,38])) { // Ignore Rank 4 Trem for now
            $stop=$this->getStop(($isattack ? +1 : -1) * ($hwdata["RankID"] + $midi-36));
            if ($stop!==NULL) {
                $ambience=$stop->Ambience();
                $ambience->LoadRelease="Y";
                $this->$method($hwdata, $ambience);
            }
        }
        else {
            unset($hwdata["NormalMIDINoteNumber"]); // Use filename
            unset($hwdata["Pitch_NormalMIDINoteNumber"]);
            $midi=1+$this->sampleMidiKey($hwdata); // Starts at 0
            $stop=$this->getSwitchNoise(($isattack ? +1 : -1)*($midi*10 + 1 + intval($hwdata["RankID"]/1000)));
            if ($stop!==NULL) {
                $stop->Function="And";
                $stop->SwitchCount=1;
                // error_log(($isattack ? "A " : "R ") .  $type .  " " .  $hwdata["RankID"] .  " " .  $hwdata["UniqueID"] .  " " .  $hwdata["PipeID"] .  " " .  $midi .  " " .  $hwdata["SampleFilename"]);
                $noise=$stop->Noise();
                $this->$method($hwdata, $noise);
            }
        }
        return NULL;
    }
    
    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $hwdata["LoopCrossfadeLengthInSrcSampleMs"]=$this->loopCrossfadeLengthInSrcSampleMs;
        unset($hwdata["LoadSampleRange_EndPositionValue"]);
        if ($hwdata["PipeLayerNumber"]==2) {
            $hwdata["RankID"]=-$hwdata["RankID"];
            $hwdata["PipeID"]=-$hwdata["PipeID"];
        }
        return parent::processSample($hwdata, $isattack);
    }
    
    public static function Alessandria(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="./OrganInstallationPackages/002512/Noises/BlankLoop.wav";
        \GOClasses\Ambience::$blankloop="./OrganInstallationPackages/002512/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new Alessandria(self::SOURCE);
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
            self::Alessandria(
                    [1=>"(close)"],
                    "close");
            self::Alessandria( 
                    [2=>"(front)"],
                    "front");
            self::Alessandria(
                    [3=>"(rear)"],
                    "rear");
            self::Alessandria( 
                    [1=>"(close)", 2=>"(front)", 3=>"(rear)"],
                    "surround");
        }
    }   
    
}
Alessandria::Alessandria();