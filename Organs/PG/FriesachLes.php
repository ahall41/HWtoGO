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
 * Import Friesach Extended by Les Deutch
 * Project abandoned (UI issues) - see FriesachExt.php. 
 */

class FriesachLes extends \Import\Organ {

    const ROOT="/GrandOrgue/Organs/PG/FriesachLes/";
    const SOURCE=self::ROOT . "OrganDefinitions/Friesach_Ext_V2.0.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Friesach_Ext_V2.0.organ";
    
    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    
    public $positions=[];

    /* public $patchDivisions=[
            5=>"DELETE", // Tuba
            7=>["DivisionID"=>7, "Name"=>"Key Actions"],
            8=>["DivisionID"=>8, "Name"=>"Stop Actions"],
            9=>["DivisionID"=>9, "Name"=>"Blower"]
        ]; */

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1]
               ],
            2=>[
                0=>["SetID"=>2],
               ],
            3=>[
                0=>["SetID"=>3],
               ]
    ];

    public $patchTremulants=[
            1720=>["ControllingSwitchID"=>1720, "Type"=>"Synth", "Name"=>"Sw Tremulant", "GroupIDs"=>[301]],
            1730=>["ControllingSwitchID"=>1730, "Type"=>"Synth", "Name"=>"Ch Tremulant", "GroupIDs"=>[401]],
    ];
    
    public $patchEnclosures=[
       220=>["GroupIDs"=>[301]],
       230=>["GroupIDs"=>[401]],
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

    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        $kbdid=$hwdata["KeyboardID"];
        if ($kbdid<10)
            return parent::createManual($hwdata);
        else
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
        return;
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
        if (empty($hwdata["ConditionSwitchID"]))
            return NULL;
        else {
            error_log(print_r($hwdata, TRUE));
            $hwdata["SwitchID"]=$hwdata["ConditionSwitchID"]-10320+1000;
            return parent::createCoupler ($hwdata);
        }
    }

    public function createSwitchNoise(string $type, array $switchdata): void { 
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
        return parent::processSample($hwdata, $isattack);
    }
    
    public static function FriesachLes(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="./OrganInstallationPackages/002512/Noises/BlankLoop.wav";
        \GOClasses\Ambience::$blankloop="./OrganInstallationPackages/002512/Noises/BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new FriesachLes(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "demo $target", $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
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
            $posaune16=$hwi->getRank(11);
            $posaune16->removePipe(38);
            $pipe38=$posaune16->Pipe(38, $posaune16->Pipe(40));
            $pipe38->PitchTuning-=200;

            $pedal=$hwi->getManual(1);
            $pedal->PositionX=715;
            $pedal->PositionY=1400;
            
            $pos=$hwi->getManual(2);
            $pos->PositionX=780;
            $pos->PositionY=806;

            $hw=$hwi->getManual(3);
            $hw->PositionX=780;
            $hw->PositionY=666;

            $sw=$hwi->getManual(4);
            $sw->PositionX=780;
            $sw->PositionY=525;

            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::FriesachLes(
                    [1=>""],"");
        }
    }   
    
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\PG\ErrorHandler");

FriesachLes::FriesachLes();