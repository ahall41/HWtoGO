<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\SP;
require_once __DIR__ . "/SPOrgan.php";

/**
 * Version 2 extension of SPOrgan to handle 
 * 
 * In this extension, there are separate sound effects in each listening 
 * perspective.
 * 
 */
abstract class SPOrganV2 extends SPOrgan {

    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if ($hwdata["RankID"]>990000 && !isset($hwdata["StopIDs"])) 
            return NULL;
        else
            return parent::createRank($hwdata, $keynoise);
    }
    
    public function createSwitchNoise(string $type, array $hwdata) : void {
        $switchid=NULL;
        switch ($type) {
            case self::TremulantNoise:
                return;
            
            case self::CouplerNoise:
                $switchid=$hwdata["ConditionSwitchID"] % 1000;
                break;
                
            case self::SwitchNoise:
                if (isset($this->patchStops[$hwdata["StopID"]]))
                    return;
                else 
                    $switchid=$hwdata["StopID"];
                break;
        }
        
        if ($switchid!==NULL && ($switch=$this->getSwitch($switchid, FALSE))!==NULL) {
            $switchid*=100;
            foreach ([701,702,703,704] as $groupid) {
                $switchid++;
                if ($windchestgroup=$this->getWindchestGroup($groupid)) {
                    $name=$switch->Name . " (" . $windchestgroup->Name . ")";
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
        }
    }  

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        static $layouts=[0=>"", 1=>"AlternateScreenLayout1_",
            2=>"AlternateScreenLayout2_", 3=>"AlternateScreenLayout3_"];

        if (isset($data["ShutterPositionContinuousControlID"]) 
                && !empty($data["ShutterPositionContinuousControlID"])) {
            $hwd=$this->hwdata;
            $slink=$hwd->continuousControlLink($data["ShutterPositionContinuousControlID"])["D"][0];
            $dlinks=$hwd->continuousControlLink($slink["SourceControlID"]);
            foreach($dlinks["S"] as $dlink) {
                $control=$hwd->continuousControl($dlink["DestControlID"]);
                if (isset($control["ImageSetInstanceID"])) {
                    $instance=$hwd->imageSetInstance($control["ImageSetInstanceID"]);
                    if ($instance!==NULL
                            && isset($this->patchDisplayPages[$instance["DisplayPageID"]])) {
                        foreach($layouts as $layoutid=>$layout) {
                            if (isset($instance["${layout}ImageSetID"])
                                && !empty($instance["${layout}ImageSetID"])) {
                                $panel=$this->getPanel(($instance["DisplayPageID"]*10)+$layoutid);
                                if ($panel!==NULL) {
                                    $pe=$panel->GUIElement($enclosure);
                                    $this->configureEnclosureImage($pe, ["InstanceID"=>$instance["ImageSetInstanceID"]], $layoutid);
                                }
                            }
                        }
                    }
                }
            }
        }
        else
            parent::configurePanelEnclosureImages($enclosure, $data);
    }

    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $rankdata=$this->patchRanks[$hwdata["RankID"]];
        
        if ($rankdata["Noise"]=="Ambient") {
            $stop=$this->getStop($rankdata["StopIDs"][0]);
            if ($stop!==NULL) {
                $ambience=$stop->Ambience();
                if ($isattack) {
                    $this->configureAttack($hwdata, $ambience);
                    $ambience->LoadRelease="Y";
                }
                else {
                    $this->configureRelease($hwdata, $ambience);
                    $ambience->LoadRelease="N";
                }
                return $ambience;
            }
        }
        else {
            $stop=$this->getSwitchNoise(
                    ($rankdata["Noise"]=="StopOn" ? +1 : -1) * (100*($hwdata["PipeID"] % 100)+$rankdata["GroupID"]-700), FALSE);
            if ($stop!==NULL) {
                $noise=$stop->Noise();
                $this->configureAttack($hwdata, $noise);
                return $noise;
            }
        }
        return NULL;
    }
}