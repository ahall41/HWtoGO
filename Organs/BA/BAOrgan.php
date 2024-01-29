<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\BA;
require_once __DIR__ . "/../../Import/Organ.php";

/**
 * Extension of Import\Organ to handle Ivan Barritt (BA - barrittaudio.co.uk/) Organs
 */
abstract class BAOrgan extends \Import\Organ {
    
    protected ?int $releaseCrossfadeLengthMs=0;
    protected string $root="";
    
    protected array $positions=[];

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

    /*
     * May be OK for other providers 
     */
    protected function isNoiseSample(array $hwdata): bool {
        $rankdata=$this->hwdata->rank($hwdata["RankID"], FALSE);
        return isset($rankdata["Noise"])
                && in_array($rankdata["Noise"], ["StopOn","StopOff","Ambient"]);
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
        
        if (empty($this->releaseCrossfadeLengthMs==0)) {
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