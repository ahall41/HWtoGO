<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\SP;
require_once __DIR__ . "/../../Import/Organ.php";

/**
 * Extension of Import\Images to handle Sonus Paradisi organs
 * 
 * The main feature is for sampled tremulants. Sonus Paradis have separate tremmed
 * ranks, but only one stop. However StopRank does make use of AlternateRankID 
 * (element p in the condensed model).
 * 
 */
abstract class SPOrgan extends \Import\Organ {
    
    protected string $root="";
    protected array  $rankpositions=[];
    
    const RANKS_DIRECT=1;
    const RANKS_SEMI_DRY=2;
    const RANKS_DIFFUSE=3;
    const RANKS_REAR=4;
    
    public function createPanel(array $hwdata) : ?\GOClasses\Panel {
        $pageid=$hwdata["PageID"];
        $hwdata["AlternateConsoleScreenLayout0_Include"]="Y";
        foreach([0,1,2,3] as $l) {
            if (isset($hwdata["AlternateConsoleScreenLayout{$l}_Include"])
                    && $hwdata["AlternateConsoleScreenLayout{$l}_Include"]=="Y"
                    && isset($hwdata[$l])) {
                $paneldata=array_merge($hwdata, $hwdata[$l]);
                $paneldata["PanelID"]=(10*$pageid)+$l;
                $panel=parent::createPanel($paneldata);
                $this->configurePanelImage($panel, $paneldata);
            }
        }
        return NULL;
    }
    
    public function configureKeyImage(?\GOClasses\GOObject $object, $keyImageset): void {
        $object=$this->getManual($keyImageset["KeyImageSetID"]+1);
        parent::configureKeyImage($object, $keyImageset);
    }

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        if (isset($data["Panels"])) {
            foreach ($data["Panels"] as $panelid=>$instances) {
                foreach ($instances as $layout=>$instanceid) {
                    if ($instanceid!==NULL) {
                        $panel=$this->getPanel(($panelid*10)+$layout, FALSE);
                        if ($panel!==NULL) {
                            $pe=$this->getPanel(($panelid*10)+$layout)->GUIElement($enclosure);
                            $this->configureEnclosureImage($pe, ["InstanceID"=>$instanceid], $layout);
                        }
                    }
                }
            }
        }
    }
    
    protected function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        if (isset($data["StopID"])) $switchid=$data["StopID"];
        elseif (isset($data["CouplerID"])) $switchid=$data["CouplerID"];
        elseif (isset($data["TremulantID"])) $switchid=$data["TremulantID"];
        else return;
        if ($switchid<0) return;
        
        foreach($this->patchDisplayPages as $pageid=>$layouts) {
            if (!is_array($layouts)) continue;
            foreach($layouts as $layoutid=>$layout) {
                if (!isset($layout["Instance"])) continue;
                $id=$switchid+$layout["Instance"];
                $instance=$this->hwdata->imageSetInstance($id, TRUE);
                if ($instance!==NULL 
                        && $this->hwdata->switch($id, TRUE)!==NULL
                        && $instance["DisplayPageID"]==$pageid) {
                    $panel=$this->getPanel(($pageid*10)+$layoutid, FALSE);
                    if ($panel!==NULL) {
                        $pe=$panel->GUIElement($switch);
                        $this->configureImage($pe, ["SwitchID"=>$id], $layoutid);
                    }
                }
            }
        }
    }

   
    protected function stopIsTremmed(array $stopdata) : bool {
        return sizeof($this->hwdata->altStopRank($stopdata["StopID"]))>0;
    }

    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        if (!isset($this->patchStops[$hwdata["StopID"]]))
            $hwdata["ControllingSwitchID"]=$hwdata["StopID"];
        $switch=parent::createStop($hwdata);
        
        if (sizeof($this->hwdata->altStopRank($hwdata["StopID"]))>0) {// Tremmed ranks
            foreach($this->patchTremulants as $tremulantid=>$tremulantdata) {
                if (isset($tremulantdata["Type"]) && $tremulantdata["Type"]=="Switched" 
                        &&  $tremulantdata["DivisionID"]==$hwdata["DivisionID"]) {
                    $stop=$this->getStop($hwdata["StopID"]);
                    $stop->Switch($this->getSwitch(-$tremulantid));
                    $stop=$this->newStop(-$hwdata["StopID"], $hwdata["Name"] . " (tremulant)");
                    $this->getManual($hwdata["DivisionID"])->Stop($stop);
                    $stop->Switch($this->getSwitch($hwdata["StopID"]));
                    $stop->Switch($this->getSwitch($tremulantid));
                }
            }
        }
        return $switch;
    }
    
    public function configureKeyboardKeys($keyboardKeys): void {
        foreach($keyboardKeys as $index=>$keyboardKey) {
            if (!isset($keyboardKey["NormalMIDINoteNumber"]))
                $keyboardKeys[$index]["NormalMIDINoteNumber"]=$keyboardKey["SwitchID"] % 100;
        }
        parent::configureKeyboardKeys($keyboardKeys);
    }    
    
    
    protected function stopInUse(array $hwdata): bool {
        return TRUE;
    }

    public function createRank(array $hwdata, bool $keynoise=FALSE): ?\GOClasses\Rank {
        $rankid=$hwdata["RankID"];
        if (!isset($hwdata["StopIDs"])) {
            $stopids=[];
            foreach($this->hwdata->rankStop($rankid) as $rankstop)
                $stopids[]=$rankstop["StopID"];
            foreach($this->hwdata->altRankStop($rankid) as $rankstop)
                $stopids[]=-$rankstop["StopID"];
            if (sizeof($stopids)==0)
                throw new \Exception("Missing RankStop for rank $rankid: " . $hwdata["Name"]);
            $hwdata["StopIDs"]=$stopids;
            if (isset($hwdata["DivisionID"]))
                $divid=$hwdata["DivisionID"];
            else
                $divid=$this->hwdata->stop(abs($stopids[0]))["DivisionID"];
            $posid=$this->rankpositions[$rankid % 10];
            if (!isset($hwdata["GroupID"]))
                $hwdata["GroupID"]=($divid*100) + $posid;
        }
        $rank=parent::createRank($hwdata);
        return $rank;
    }

    public function createSwitchNoise(string $type, array $hwdata) : void {
        $switchid=NULL;
        switch ($type) {
            case self::TremulantNoise:
                $switchid=$hwdata["TremulantID"] ;
                break;
            
            case self::CouplerNoise:
                $switchid=$hwdata["ConditionSwitchID"] % 1000;
                break;
                
            case self::SwitchNoise:
                if (isset($this->patchStops[$hwdata["StopID"]]))
                    $switchid=$hwdata["ControllingSwitchID"];
                else 
                    $switchid=$hwdata["StopID"];
                if ($switchid==250) return;
                break;
        }
        
        if ($switchid!==NULL && ($switch=$this->getSwitch($switchid, FALSE))!==NULL) {
            $name=$switch->Name;
            $windchestgroup=$this->getWindchestGroup(900);
            $manual=$this->getManual(
                    isset($hwdata["DivisionID"]) && !empty($hwdata["DivisionID"])
                    ? $hwdata["DivisionID"] : 1);
            $on=$this->newSwitchNoise($switchid, "$name (on)");
            $on->WindchestGroup($windchestgroup);
            $on->Switch($switch);
            $manual->Stop($on);
            $off=$this->newSwitchNoise(-$switchid, "$name (off)");
            $off->WindchestGroup($windchestgroup);
            $manual->Stop($off);
            $off->Function="Not";
            $off->Switch($switch);
            unset($off->SwitchCount);
        }
    }  

    protected function isNoiseSample(array $hwdata): bool {
        $rankdata=$this->hwdata->rank($hwdata["RankID"], FALSE);
        return isset($rankdata["Noise"])
                && in_array($rankdata["Noise"], ["StopOn","StopOff","Ambient"]);
    }

    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $rankdata=$this->patchRanks[$hwdata["RankID"]];
        
        if ($rankdata["Noise"]=="Ambient") {
            $stop=$this->getStop($rankdata["StopIDs"][0]);
            if ($stop!==NULL) {
                $ambience=$stop->Ambience();
                $this->configureAttack($hwdata, $ambience);
                return $ambience;
            }
        }
        else {
            $stop=$this->getSwitchNoise( /** sic !!! @todo !!! */
                    ($rankdata["Noise"]=="StopOn" ? -1 : +1) * ($hwdata["PipeID"] % 1000), FALSE);
            if ($stop!==NULL) {
                $noise=$stop->Noise();
                $this->configureAttack($hwdata, $noise);
                return $noise;
            }
        }
        return NULL;
    }
    
    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        $hwdata["ConditionSwitchID"]=$hwdata["ConditionSwitchID"] % 1000;
        return parent::createCoupler($hwdata);
    }

    
    public function createTremulant(array $hwdata): ?\GOClasses\Sw1tch {
        $hwdata["SwitchID"]=$hwdata["TremulantID"];
        return parent::createTremulant($hwdata);
    }
    
    // The attribute "Noise" is now misleading, as SP have started recording
    // sound effects at each listening position
    public function createWindchestGroup(array $divisiondata): ?\GOClasses\WindchestGroup {
        $divid=$divisiondata["DivisionID"];
        if (isset($divisiondata["Noise"]) && $divisiondata["Noise"]) {
            $divisiondata["GroupID"]=$divid*100;
            parent::createWindchestGroup($divisiondata);
        }
        else {
            $divname=$divisiondata["Name"];
            foreach($this->positions as $posid=>$posname) {
                $divisiondata["Name"]="$divname $posname";
                $divisiondata["GroupID"]=$posid + ($divid*100);
                parent::createWindchestGroup($divisiondata);
            }
        }
        return NULL;
    }

    
    public function getImageData(array $data, int $layout=0) : array {
        $imagedata=parent::getImageData($data, $layout);
        if ((!isset($imagedata["ImageWidthPixels"]) || empty($imagedata["ImageWidthPixels"]))
                || (!isset($imagedata["ImageWidthPixels"]) || empty($imagedata["ImageWidthPixels"]))) {
            $filename=reset($imagedata["Images"]);
            $sizes=getimagesize(getenv("HOME") . $this->root . $filename);
            if (is_array($sizes)) {
                $imagedata["ImageWidthPixels"]=$sizes[0];
                $imagedata["ImageHeightPixels"]=$sizes[1];
            }
        }
       return $imagedata;
    }

    protected function sampleMidiKey(array $hwdata) : int {
        $key=$hwdata["PipeID"] % 100;
        if (intval($hwdata["RankID"]/10)==54) $key+=12;
        return $key;
    }

    public function processSample(array $hwdata, bool $isattack) : ?\GOClasses\Pipe {
        $rankid=$hwdata["RankID"];
        
        if (isset($this->patchRanks[$rankid]["Noise"])) {
            $rankdata=$this->patchRanks[$rankid];
            $isattack=$rankdata["Noise"]=="KeyOn";
            if (isset($rankdata["Attack"])) $hwdata["RankID"]=$rankdata["Attack"];
        }

        if (isset($hwdata["LoopCrossfadeLengthInSrcSampleMs"]) 
                && $hwdata["LoopCrossfadeLengthInSrcSampleMs"]>120)
                $hwdata["LoopCrossfadeLengthInSrcSampleMs"]=120;

        return parent::processSample($hwdata, $isattack);
    }
}