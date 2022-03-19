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
 * Create bespoke organ based on Sonus Paradisi sample sets
 * 
 * @author andrew
 */
abstract class SPExtended extends \Import\Organ {
    const RANKS_DIRECT=1;
    const RANKS_SEMI_DRY=2;
    const RANKS_DIFFUSE=3;
    const RANKS_REAR=4;
    
    protected array $positions=[];
    protected array $rankpositions=[];
    protected array $divisions=[];
    protected array $enclosures=[];
    protected array $manuals=[];
    protected array $tremulants=[];
    protected array $couplers=[];
    protected array $stops=[];
    protected string $rootdir;

    public function build() : void {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        $hwd=$this->hwdata;
        $this->buildOrgan();
        $this->buildWindchestGroups();
        $this->buildEnclosures();
        $this->buildManuals();
        $this->buildTremulants();
        $this->buildCouplers();
        $this->buildStops();
        $this->buildRanks();
        $this->processSamples($hwd->attacks(), TRUE);
        $this->processSamples($hwd->releases(), FALSE);
    }

    protected function buildOrgan() : void {
        throw new \Exception("Method buildOrgan() must be implemented");
    }

    protected function buildWindchestGroups() {
        foreach($this->divisions as $divid=>$division) {
            foreach ($this->positions as $posid=>$position) {
                $this->createWindchestGroup([
                    "GroupID"=>$divid*100+$posid,
                    "Name"=>"$division $position"]);
            }
        }
    }

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        $panel=$this->getPanel(0);
        $panel->GUIElement($enclosure);
    }
    
    protected function buildEnclosures() {
        foreach ($this->enclosures as $divid=>$name) {
            $data=["EnclosureID"=>$divid, "Name"=>$name, "GroupIDs"=>[]];
            foreach ($this->positions as $posid=>$position)
                $data["GroupIDs"][]=$divid*100+$posid;
            $this->createEnclosure($data);
        }
        
        // Mixer
        foreach($this->positions as $posid=>$position) {
            $data=[
                "EnclosureID"=>100+$posid, 
                "Name"=>$position,
                "AmpMinimumLevel"=>1,
                "GroupIDs"=>[]];
            foreach($this->divisions as $divid=>$division)
                $data["GroupIDs"][]=$divid*100+$posid;
            $this->createEnclosure($data);
        }
    }
    protected function buildManuals() : void {
        foreach($this->manuals as $id=>$name)
            $this->createManual([
                "KeyboardID"=>$id,
                "Name"=>$name]);
    }

    protected function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $switchdata): void {
        $panel=$this->getPanel(0);
        $pe=$panel->GUIElement($switch);
        $pe->DispDrawstopRow=$switchdata["Position"][0];
        $pe->DispDrawstopCol=$switchdata["Position"][1];
        $pe->DispLabelText  =isset($switchdata["Label"])  ? $switchdata["Label"]  : $switchdata["Name"];
        $pe->DispLabelColour=isset($switchdata["Colour"]) ? $switchdata["Colour"] : "Black";
    }

    protected function buildTremulants() : void {
        foreach($this->tremulants as $id=>$tremdata) {
            $tremdata["TremulantID"]=$id;
            $tremdata["Colour"]="Dark Blue";
            $tremdata["Label"]="Tremulant";
            $this->createTremulant($tremdata);
        }
    }

    protected function buildCouplers() : void {
        foreach($this->couplers as $id=>$cplrdata) {
            $cplrdata["Colour"]="Dark Green";
            $cplrdata["SwitchID"]=$cplrdata["CouplerID"]=$id;
            $this->createCoupler($cplrdata);
        }
    }

    
    protected function stopInUse(array $hwdata): bool {
        return TRUE;
    }

    protected function buildStops() : void {
        foreach($this->stops as $stopid=>$stopdata) {
            $stopdata["StopID"]=$stopdata["SwitchID"]=$stopid;
            $manualid=$stopdata["DivisionID"]=$stopdata["ManualID"];
            $switch=$this->createStop($stopdata);
            if (isset($stopdata["TremulantID"])) {
                $tswitchid=$this->tremulants[$stopdata["TremulantID"]]["SwitchID"];
                $on=$this->getSwitch($tswitchid);
                $off=$this->getSwitch(-$tswitchid);

                $this->getStop($stopid)->switch($off);
                $stop=$this->newStop(-$stopid, $this->stops[$stopid]["Name"] . " (tremulant)");
                $this->getManual($manualid)->Stop($stop);
                $stop->switch($switch);
                $stop->switch($on);
            }
        }
    }

    protected function buildRank(int $stopid, array $rankdata) : ?\GOClasses\Rank {
        $rankid=$rankdata["RankID"];
        $divid=$this->stops["$stopid"]["DivisionID"];
        $posid=$this->rankpositions[$rankid % 10];
        $wcg=$this->getWindchestGroup(($divid*100) + $posid);
        if ($wcg===NULL) return NULL;
        $rank=$this->newRank($rankid, $rankdata["Name"]);
        $rank->WindchestGroup($wcg);
        foreach ($this->stops as $id=>$stopdata) {
            if ($stopid==$id || (isset($stopdata["Rank"]) && $stopdata["Rank"]==$stopid)) {
                if (($rankid % 10)<5)
                    $stop=$this->getStop($id);
                else
                    $stop=$this->getStop(-$id);
                if ($stop!==NULL)  $stop->Rank($rank);
                if (isset($stopdata["FirstPipe"])) {
                    $ranknum=$stop->int2str($stop->NumberOfRanks);
                    if (($firstpipe=$stopdata["FirstPipe"])>0)
                        $stop->set("Rank${ranknum}FirstPipeNumber", $firstpipe);
                    else
                        $stop->set("Rank${ranknum}FirstAccessibleKeyNumber", -$firstpipe);
                }
            }
        }
        return $rank;
    }
    
    protected function buildRanks() : void {
        $ranks=$this->hwdata->ranks();
        asort($ranks);
        foreach($ranks as $rankdata) {
            $stopid=intval($rankid=($rankdata["RankID"]/10));
            if (isset($this->stops[$stopid]))
                $rank=$this->buildRank($stopid, $rankdata);
        }
        
    }

    private function treeWalk($root, $dir="", &$results=[]) {
        $files=scandir("$root$dir");
        foreach ($files as $key => $value) {
            if (!is_dir("$root$dir/$value")) {
                $results[strtolower("$dir/$value")] = "$dir/$value";
            } else if ($value != "." && $value != "..") {
                $this->treeWalk($root, ltrim("$dir/$value", "/"), $results);
            }
        }
        return $results;
    }
    
    protected function correctFileName(string $filename): string {
        static $files=[];
        if (sizeof($files)==0)
            $files=$this->treeWalk(getenv("HOME") . $this->rootdir);
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    protected function sampleMidiKey(array $hwdata) : int {
        $key=$hwdata["PipeID"] % 100;
        if (intval($hwdata["RankID"]/10)==54) $key+=12;
        return $key;
    }
   
    protected function configureAttack(array $hwdata, \GOClasses\Pipe $pipe): void {
        if ($pipe->AttackCount<0)
            parent::configureAttack($hwdata, $pipe);
    }

    public function processSample(array $hwdata, bool $isattack) : ?\GOClasses\Pipe {
        if (isset($hwdata["LoopCrossfadeLengthInSrcSampleMs"]) 
                && $hwdata["LoopCrossfadeLengthInSrcSampleMs"]>120)
                $hwdata["LoopCrossfadeLengthInSrcSampleMs"]=120;
        return parent::processSample($hwdata, $isattack);
    }
}