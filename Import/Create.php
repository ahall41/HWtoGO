<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Import;
require_once(__DIR__ . "/Objects.php");

/**
 * Extends objects with create and make methods
 *
 * @author andrew
 */
abstract Class Create extends Objects {

    const TremulantNoise="T";
    const CouplerNoise="C";
    const SwitchNoise="S";
   
    /**
     * Create the Organ. <b>This must be called first</b>
     * @param array $organdata - including ChurchName
     * @return void
     */
    public function createOrgan(array $organdata) : \GOClasses\Organ {
        return $this->newOrgan($organdata["ChurchName"]);
    }

   /**
     * Create a panel
     * @param array $paneldata -data including PanelID, Name and (optional) Group
     * @return \GOClasses\Panel
     */
    public function createPanel(array $paneldata) : ?\GOClasses\Panel {
        $panel=$this->newPanel($paneldata["PanelID"], $paneldata["Name"]);
        if (isset($paneldata["Group"])) $panel->Group=$paneldata["Group"];
        return $panel;
    }

    /**
     * Create a manual. Pedals must be first!
     * 
     * @param array $manualdata including ManualID and Name
     * @return \GOClasses\Manual
     */
    public function createManual(array $manualdata) : ?\GOClasses\Manual {
        return $this->newManual($manualdata["ManualID"], $manualdata["Name"]);
    }

    /**
     * Create a Windchest Group
     * 
     * @param array $groupdata including GroupID and Name
     * @return \GOClasses\Manual
     */
    public function createWindchestGroup(array $groupdata) : ?\GOClasses\WindchestGroup {
        return $this->newWindchestGroup($groupdata["GroupID"], trim($groupdata["Name"]));
    }

    /**
     * Create an Enclosure
     * 
     * @param array $manualdata including EnclosureID, Name and GroupIDs[]
     * @return \GOClasses\Manual
     */
    public function createEnclosure(array $enclosuredata) : ?\GOClasses\Enclosure {
        $enclosure=$this->newEnclosure($enclosuredata["EnclosureID"], $enclosuredata["Name"]);
        foreach($enclosuredata["GroupIDs"] as $groupid) {
            $wcg=$this->getWindchestGroup($groupid);
            if ($wcg) $wcg->Enclosure ($enclosure);
        }
        return $enclosure;
    }

    /**
     * Create a noise stop
     * 
     * @param type $switchid
     * @param type $groupid
     * @param type $name
     */
    public function createNoise(\GOClasses\Sw1tch $switch, array $noisedata) : \GOClasses\SwitchNoise {
        $stop=$this->newSwitchNoise($noisedata["StopID"], $noisedata["Name"]);
        $stop->WindChestgroup($this->getWindchestGroup($noisedata["GroupID"]));
        $this->getManual($noisedata["ManualID"])->Stop($stop);
        $stop->Switch($switch);
        return $stop;
    }
    
    /**
     * Create a Stop and its controlling switch
     * 
     * @param array $stopdata - data including StopID, SwitchID, ManualID and Name
     * @return \GOClasses\Sw1tch
     */
    public function createStop(array $stopdata) : ?\GOClasses\Sw1tch {
        $name=$stopdata["Name"];
        if (isset($stopdata["Ambient"]) && $stopdata["Ambient"]) {
            $wcg=$this->getWindchestGroup($stopdata["GroupID"]);
            if ($wcg===NULL) return NULL;
            $stop=$this->newAmbientNoise($stopdata["StopID"], $name);
            if ($stop instanceof \GOClasses\AmbientNoise)
            $stop->WindchestGroup($wcg);
        }
        else {
            $stop=$this->newStop($stopdata["StopID"], $name);
        }
        
        $this->getManual($stopdata["DivisionID"])->Stop($stop);
        if (isset($stopdata["SwitchID"])
                && !empty($stopdata["SwitchID"])) {
            $switchame=isset($stopdata["SwitchName"]) ? $stopdata["SwitchName"] : "Stop $name";
            $switch=$this->makeSwitch($stopdata["SwitchID"], $switchame);
            $this->getManual($stopdata["DivisionID"])->Switch($switch);
            $stop->Switch($switch);
            return $switch;
        }
        else
            return NULL;
    }

    /**
     * Create a Rank
     * 
     * @param array $rankdata data including RankID, StopIDs[] and Name
     * @return \GOClasses\Rank
     */
    public function createRank(array $rankdata, bool $keynoise=FALSE) : ?\GOClasses\Rank {
        if ((isset($rankdata["KeyNoise"]) && $rankdata["KeyNoise"])) $keynoise=TRUE;
        $group=$this->getWindchestGroup($rankdata["GroupID"]);
        if ($group!==NULL) {
            $rank=$this->newRank($rankdata["RankID"], $rankdata["Name"], $keynoise);
            foreach($rankdata["StopIDs"] as $stopid) {
                $stop=$this->getStop($stopid);
                if ($stop!==NULL) $stop->Rank($rank);
            }
            $rank->WindchestGroup($this->getWindchestGroup($rankdata["GroupID"]));
            return $rank;
        }
        else
            return NULL;
    }
    
    /**
     * Create a coupler (and associated switch)
     * @param array $couplerdata including CouplerID, SwitchID, ManualID and Name
     * @return \GOClasses\Sw1tch
     */
    public function createCoupler(array $couplerdata) : ?\GOClasses\Sw1tch {
        $name=$couplerdata["Name"];
        $coupler=$this->newCoupler($couplerdata["CouplerID"], $name);
        $switch=$this->newSwitch($couplerdata["SwitchID"], "Coupler $name");
        $coupler->Switch($switch);
        $manual=$this->getManual($couplerdata["ManualID"]);
        $manual->Coupler($coupler);
        $manual->Switch($switch);
        return $switch;
    }

    /**
     * Create a "tremulant" and associated switches
     * Where there are separate tremmed stops, we actually
     * create 2 switches (1 for On and 1 for Off)
     * 
     * @param array $tremulantdata including TremulantID, Name, SwitchID and Type
     * for Type != "Switched", we also need GroupID
     * @return \GOClasses\Coupler
     */
    public function createTremulant(array $tremulantdata) : ?\GOClasses\Sw1tch {
        $manual=isset($tremulantdata["DivisionID"]) ? $this->getManual($tremulantdata["DivisionID"]) : FALSE;
        $name=$tremulantdata["Name"];
        if ($tremulantdata["Type"]=="Switched") {
            $on=$this->newSwitch($tremulantdata["SwitchID"], "Tremulant $name (on)");
            if ($manual) $manual->Switch($on);
            $on->GCState=0;
            $on->StoreInDivisional="Y";
            $on->StoreInGeneral="Y";
            $on->Displayed="N";
            $on->DefaultToEngaged="N";

            $off=$this->newSwitch(-$tremulantdata["SwitchID"], "Tremulant $name (off)");
            $off->Displayed="N";
            $off->Function="Not";
            $off->Switch($on);
            return $on;
        }
        else {
            $tremulant=$this->newTremulant(
                    $tremulantdata["TremulantID"], 
                    $tremulantdata["Name"],
                    $tremulantdata["Type"]=="Wave");
            $switch=$this->newSwitch($tremulantdata["SwitchID"], "Tremulant $name");
            if ($manual) $manual->Switch($switch);
            $tremulant->Switch($switch);
            foreach($tremulantdata["GroupIDs"] as $groupid) {
                $wcg=$this->getWindchestGroup($groupid);
                if ($wcg) $wcg->Tremulant($tremulant);
            }
            return $switch;
        }
    }
}