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
 * Extension of Import\Images to handle Piotr Grabowski's HW models
 * 
 * The main feature is for sampled tremulants. In HW there is a single stop/rank/pkipe
 * but for GO we need separate stops/ranks/pipes for the tremmed and untremmed samples 
 * The tremmed objects will have a negative ID
 */
abstract class PGOrgan extends \Import\Organ {
    
    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $organ=parent::createOrgan($hwdata);
        $organ->RecordingDetails="Recorded by Piotr Grabowski";
        $organ->OrganComments="Sample set was made by Piotr Grabowski (www.piotrgrabowski.pl)";
        return $organ;
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
                        $panel=$this->getPanel($panelid, FALSE);
                        if ($panel!==NULL) {
                            if ($switch===NULL) 
                                $this->createPanelImage(
                                        $panel, 
                                        ["SwitchID"=>$switchid, "ImageIDX"=>2], 
                                        $layout);
                            else {
                                $panelelement=$panel->GUIElement($switch);
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
    }

    public function configureKeyboardKey(\GOClasses\Manual $manual, $switchid, $midikey): void {
        $switch=$this->hwdata->switch($switchid);
        if (!empty($switch["Disp_ImageSetIndexEngaged"])) 
            parent::configureKeyboardKey($manual, $switchid, $midikey);
    }
    
    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        if (empty($hwdata["DestDivisionID"])) $hwdata["DestDivisionID"]=$hwdata["DestKeyboardID"];
        if (empty($hwdata["ConditionSwitchID"])) $hwdata["ConditionSwitchID"]=$hwdata["ConditionSwitchID"] % 1000;
        return parent::createCoupler ($hwdata);
    }

    public function createSwitchNoise(string $type, array $switchdata): void { 
        $switchid=NULL;
        switch ($type) {
            case self::TremulantNoise:
            case self::SwitchNoise:
                $switchid=$switchdata["ControllingSwitchID"] % 1000;
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
        if (isset($this->patchStops[$hwdata["StopID"]]["StopID"])) {
            if (isset($this->patchStops[$hwdata["StopID"]]["Disused"]) 
                    && $this->patchStops[$hwdata["StopID"]]["Disused"])
            return FALSE;
        else
            return TRUE;
        }
        else
            return parent::stopInUse($hwdata);
    }
    
    public function createStop(array $hwdata) : ?\GOClasses\Sw1tch {
        if (isset($this->patchStops[$hwdata["StopID"]])) {
            if (($switchid=$hwdata["ControllingSwitchID"])) {
                $switchdata=$this->hwdata->switch($switchid, TRUE);
                if (isset($switchdata["Name"])) $hwdata["SwitchName"]=$switchdata["Name"];
            }
            return parent::createStop($hwdata);
        }
        else {
            $switchid=$hwdata["ControllingSwitchID"]=$hwdata["StopID"];
            $switchdata=$this->hwdata->switch($switchid);
            if (isset($switchdata["Name"])) $hwdata["SwitchName"]=$switchdata["Name"];
            $switch=parent::createStop($hwdata);
            if ($switch===NULL) return NULL;

            $divid=$hwdata["DivisionID"];
            $tremid=NULL;
            foreach($this->hwdata->tremulants() as $data) {
                if ($data["Type"]=="Switched" && $data["DivisionID"]==$divid) {
                    $tremid=$data["ControllingSwitchID"];
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
        $hwdata["LoopCrossfadeLengthInSrcSampleMs"]=$this->loopCrossfadeLengthInSrcSampleMs;
        if ($hwdata["PipeLayerNumber"]==2) {
            $hwdata["RankID"]=-$hwdata["RankID"];
            $hwdata["PipeID"]=-$hwdata["PipeID"];
        }
        return parent::processSample($hwdata, $isattack);
    }
     
}
