<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\CP;
require_once __DIR__ . "/../../Import/Organ.php";

/**
 * Extension of Import\Organ to handle Coral Pipes (CP - https://coralpipesorgan.wixsite.com/coral-pipes//) Organs
 */
abstract class CPOrgan extends \Import\Organ {
    
    protected ?int $releaseCrossfadeLengthMs=0;
    protected string $root="";
    
    // protected array $positions=[1=>""];

    /*
     * Create Windchest Groups. 1 per position/division + 1 for all noise effects
     */
    /* public function createWindchestGroup(array $divisiondata): ?\GOClasses\WindchestGroup {
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
    } */

    public function createPanel($hwdata): ?\GOClasses\Panel {
        $pageid=$hwdata["PageID"];
        // error_log($hwdata["Name"]);
        $hwdata["AlternateConsoleScreenLayout0_Include"]="Y";
        foreach([0,1,2,3] as $layout) {
            if (isset($hwdata["AlternateConsoleScreenLayout{$layout}_Include"])
                    && $hwdata["AlternateConsoleScreenLayout{$layout}_Include"]=="Y") {
                $paneldata=array_merge($hwdata, $hwdata[$layout]);
                $paneldata["PanelID"]=(10*$pageid)+$layout;
                // error_log(print_r($hwdata,1));
                $panel=parent::createPanel($paneldata);
                $this->configurePanelImage($panel, $paneldata, $layout);
            }
        }
        return NULL;
    }
    
    public function configurePanelSwitchImage(\GOClasses\PanelElement $panelelement, array $data, int $layer) : void {
        $this->configureImage($panelelement, ["SwitchID"=>$data["SwitchID"]], $layer);        
        $imagedata=$this->getImageData($data, $layer);
        $panelelement->MouseRectWidth=$imagedata["ImageWidthPixels"];
        $panelelement->MouseRectHeight=$imagedata["ImageHeightPixels"];
    }

    /*
     * This might be OK for other providers ...
     */
    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        static $layouts=
                [0=>"", 1=>"AlternateScreenLayout1_",
                 2=>"AlternateScreenLayout2_", 3=>"AlternateScreenLayout3_"];
        
        if (!$switch) {return;}
        $switchid=intval($data["SwitchID"]);
        if (!$switchid) {return;}
        
        foreach ($this->hwdata->switchLink($switchid)["D"] as $dlink) {
            $slinkid=$dlink["SourceSwitchID"];
            foreach($this->hwdata->switchLink($slinkid)["S"] as $slink) {
                $switchdata=$this->hwdata->switch($destid=$slink["DestSwitchID"]);
                if (isset($switchdata["Disp_ImageSetInstanceID"]) && $switchdata["Disp_ImageSetInstanceID"]) {
                    // error_log(print_r($switchdata,1));
                    $instance=$this->hwdata->imageSetInstance($switchdata["Disp_ImageSetInstanceID"]);
                    // $set=$this->get()
                    // error_log(print_r($instance, 1));
                    foreach($layouts as $layout=>$prefix) {
                        if (isset($instance["${prefix}ImageSetID"]) &&
                                !empty($instance["${prefix}ImageSetID"])) {
                            $panelid=$instance["DisplayPageID"]*10+$layout;
                            $panel=$this->getPanel($panelid, FALSE);
                            if ($panel) {
                                $panelelement=$panel->GUIElement($switch);
                                $this->configurePanelSwitchImage(
                                        $panelelement, 
                                        ["SwitchID"=>$destid], 
                                        $layout);
                                if ($panelelement->MouseRectLeft) {
                                    $panelelement->MouseRectWidth-=$panelelement->MouseRectLeft;
                                    // error_log($panelelement . "\n");
                                }
                                if ($panelelement->MouseRectTop) {
                                    $panelelement->MouseRectHeight-=$panelelement->MouseRectTop;
                                    // error_log($panelelement . "\n");
                                }
                            }
                        }
                    }
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
        
        $rankid=intval($hwdata["RankID"]);
        if (!isset($hwdata["StopIDs"])) {
            $hwdata["StopIDs"]=[];
            foreach ($this->hwdata->rankStop($rankid) as $rankstop) {
                $hwdata["StopIDs"][]=$stopid=$rankstop["StopID"];
                $division=$this->hwdata->stop($stopid)["DivisionID"];
                $hwdata["GroupID"]=$division;
            }
        }
        
        if (isset($hwdata["GroupID"])) {
            $rank=parent::createRank($hwdata);
            return $rank;
        }
        return NULL;
    }
    
    /*
     * For AVO, stops in the range 1000-1999 are for noises
     * As we create our own we filter them out
     */
    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        $stopid=$hwdata["StopID"];
        // error_log($stopid . " - " . $hwdata["Name"]);
        return parent::createStop($hwdata);
    }
    
    /*
     * AVO noises are all in the 1 noise Windchest
     */
    public function createSwitchNoise(string $type, array $switchdata): void {
        //$switchdata["GroupID"]=700;
        //parent::createSwitchNoise($type, $switchdata);
    }
 
    public function getImageData(array $data, int $layout=0) : array {
        // error_log(print_r($data,1));
        $imagedata=parent::getImageData($data, $layout);
        if (sizeof($imagedata["Images"])>0) {
            $filename=getenv("HOME") . $this->root . reset($imagedata["Images"]);
            $sizes=getimagesize($filename);
            $width=isset($imagedata["ImageWidthPixels"]) ? intval($imagedata["ImageWidthPixels"]) : 99999999;
            $height=isset($imagedata["ImageHeightPixels"]) ? intval($imagedata["ImageHeightPixels"]) : 99999999;
            $sizes=getimagesize($filename);
            $imagedata["ImageWidthPixels"] = min($width, $sizes[0]);
            $imagedata["ImageHeightPixels"] = min($height, $sizes[1]);
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
        
        if ($this->releaseCrossfadeLengthMs==0) {
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
    
    protected function treeWalk($root, $dir="", &$results=[]) {
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

}