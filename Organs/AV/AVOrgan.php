<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\AV;
require_once __DIR__ . "/../../Import/Organ.php";

/**
 * Extension of Import\Organ to handle Augustine's Virtual Organs (AVO)
 */
abstract class AVOrgan extends \Import\Organ {
    
    protected ?int $releaseCrossfadeLengthMs=NULL;
    protected int $noiseVersion=2;
    
    protected array $positions;

    /*
     * Create Windchest Groups. 1 per position/division + 1 for all noise effects
     */
    public function createWindchestGroup(array $divisiondata): ?\GOClasses\WindchestGroup {
        if (($divid=$divisiondata["DivisionID"])<7) {
            $divname=$divisiondata["Name"];
            foreach($this->positions as $posid=>$posname) {
                $divisiondata["Name"]="$divname $posname";
                $divisiondata["GroupID"]=$posid + ($divid*100);
                parent::createWindchestGroup($divisiondata);
            }
        }
        else {
            $divisiondata["GroupID"]=$divid*100;
            parent::createWindchestGroup($divisiondata);
        }
        return NULL;
    }

    public function configurePanelSwitchImage(\GOClasses\PanelElement  $panelelement, array $data) : void {
        $this->configureImage($panelelement, ["SwitchID"=>$data["SwitchID"]]);
    }

    /*
     * This might be OK for other providers ...
     */
    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        $switchid=$data["SwitchID"];
        $slinkid=$this->hwdata->switchLink($switchid)["D"][0]["SourceSwitchID"];
        foreach($this->hwdata->switchLink($slinkid)["S"] as $link) {
            $switchdata=$this->hwdata->switch($destid=$link["DestSwitchID"]);
            if (isset($switchdata["Disp_ImageSetInstanceID"])) {
                $instancedata=$this->hwdata->imageSetInstance($switchdata["Disp_ImageSetInstanceID"]);
                $panel=$this->getPanel($instancedata["DisplayPageID"], FALSE);
                if ($panel!==NULL) {
                    $panelelement=$panel->GUIElement($switch);
                    $data["SwitchID"]=$destid;
                    $this->configurePanelSwitchImage($panelelement, $data);
                }
            }
        }
    }

    /*
     * May be OK for other providers
     */
    public function createRank(array $hwdata, bool $keynoise=FALSE): ?\GOClasses\Rank {
        if (isset($hwdata["Noise"]) && $hwdata["Noise"]=="Ambient") {
            return NULL;
        }
        
        $rankid=$hwdata["RankID"];
        if (!isset($hwdata["StopIDs"])) {
            $hwdata["StopIDs"]=[];
            foreach ($this->hwdata->rankStop($rankid) as $rankstop) {
                $hwdata["StopIDs"][]=$stopid=$rankstop["StopID"];
                $division=$this->hwdata->stop($stopid)["DivisionID"];
                $hwdata["GroupID"]=($division*100) +1 +intval($rankid/100);
            }
        }
        $rank=parent::createRank($hwdata);
        return $rank;
    }
    
    /*
     * For AVO, stops in the range 1000-1999 are for noises
     * As we create our own we filter them out
     */
    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        $stopid=$hwdata["StopID"];
        if ($stopid>2000 || $stopid<10)
            return parent::createStop($hwdata);
        else
            return NULL;
    }
    
    /*
     * AVO noises are all in the 1 noise Windchest
     */
    public function createSwitchNoise(string $type, array $switchdata): void {  
        $switchdata["GroupID"]=700;
        parent::createSwitchNoise($type, $switchdata);
    }  

    /*
     * May be OK for other providers 
     */
    protected function isNoiseSample(array $hwdata): bool {
        $rankdata=$this->hwdata->rank($hwdata["RankID"], FALSE);
        return isset($rankdata["Noise"])
                && in_array($rankdata["Noise"], ["StopOn","StopOff","Ambient"]);
    }
    
    public function processNoiseV2(array $hwdata, $isattack): ?\GOClasses\Noise {
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $type=$this->hwdata->rank($rankid=$hwdata["RankID"])["Noise"];
        if ($type=="Ambient") {
            $stopid=$this->hwdata->rank($hwdata["RankID"])["StopIDs"][0];
            if (($stop=$this->getStop($stopid))) {
                if ($isattack)
                    $this->configureAttack($hwdata, $stop->Ambience());
                else
                    $this->configureRelease($hwdata, $stop->Ambience());
            }
        }
        else {
            $midikey=$hwdata["Pitch_NormalMIDINoteNumber"];
            foreach($this->hwdata->read("StopRank") as $sr) {
                if ($sr["RankID"]==$rankid && 
                    $sr["MIDINoteNumIncrementFromDivisionToRank"]==$midikey) {
                    if ($stopdata=$this->hwdata->stop($sr["StopID"], FALSE)) {
                        $switchid=$stopdata["ControllingSwitchID"];
                        $stopoff=strpos($sr["Name"], "Disengaging")!==FALSE;
                        $switchNoise=$this->getSwitchNoise($stopoff ? -$switchid : +$switchid);
                        if ($switchNoise) {
                            $this->configureAttack($hwdata, $switchNoise->Noise());
                        }
                    }
                }
            } 
        }
        return NULL;
    }
   
    /*
     * May be OK for other providers 
     */
    public function processNoiseV1(array $hwdata, $isattack): ?\GOClasses\Noise {
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $type=$this->hwdata->rank($hwdata["RankID"])["Noise"];
        if ($type=="Ambient") {
            $stopid=$this->hwdata->rank($hwdata["RankID"])["StopIDs"][0];
            if (($stop=$this->getStop($stopid))) {
                if ($isattack)
                    $this->configureAttack($hwdata, $stop->Ambience());
                else
                    $this->configureRelease($hwdata, $stop->Ambience());
            }
        }
        else {
            foreach($this->getSwitchNoises() as $id=>$stop) {
                if ($id>0 && $type=="StopOn")
                    $this->configureAttack($hwdata, $stop->Noise());
                elseif ($id<0 && $type=="StopOff")
                    $this->configureAttack($hwdata, $stop->Noise());
            }
        }
        return NULL;
    }
    
    public function processNoise(array $hwdata, $isattack): ?\GOClasses\Pipe {
        if ($this->noiseVersion==1)
            {return $this->processNoiseV1($hwdata, $isattack);}
        else
            {return $this->processNoiseV2($hwdata, $isattack);}
    }
    
    /*
     * May be OK for other providers ...
     */
    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $rankdata=$this->hwdata->rank($hwdata["RankID"], FALSE);
        if ($rankdata==[]) return NULL;
        if (isset($rankdata["Noise"])) {
            switch ($rankdata["Noise"]) {
                case "Ambient":
                    print_r($hwdata);
                case "KeyOn":
                    return parent::processSample($hwdata, TRUE);
                case "KeyOff":
                    return parent::processSample($hwdata, FALSE);
            }
        }

        if ($this->releaseCrossfadeLengthMs===0) {
            $hwdata["ReleaseCrossfadeLengthMs"]=0;
        }
        elseif ($this->releaseCrossfadeLengthMs>0) {
            $hwdata["ReleaseCrossfadeLengthMs"]=$this->releaseCrossfadeLengthMs;
        }
        elseif ($this->releaseCrossfadeLengthMs<0) {
            unset($hwdata["ReleaseCrossfadeLengthMs"]);
        }

        return parent::processSample($hwdata, $isattack);
    }
}