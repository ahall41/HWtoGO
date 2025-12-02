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

    public function createPanel($hwdata): ?\GOClasses\Panel {
        $pageid=$hwdata["PageID"];
        $hwdata["AlternateConsoleScreenLayout0_Include"]="Y";
        foreach([0,1,2,3] as $layout) {
            if (isset($hwdata["AlternateConsoleScreenLayout{$layout}_Include"])
                    && $hwdata["AlternateConsoleScreenLayout{$layout}_Include"]=="Y") {
                $paneldata=array_merge($hwdata, $hwdata[$layout]);
                $paneldata["PanelID"]=(10*$pageid)+$layout;
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
                    $instance=$this->hwdata->imageSetInstance($switchdata["Disp_ImageSetInstanceID"]);
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
                                }
                                if ($panelelement->MouseRectTop) {
                                    $panelelement->MouseRectHeight-=$panelelement->MouseRectTop; 
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        $switch=parent::createStop($hwdata);
        if ($switch) {
            $stopranks=$this->hwdata->stopRank($hwdata["StopID"]);
            foreach($stopranks as $sr) {
                if (!empty($sr["SwitchIDToSwitchToAlternateRank"])) {
                    $switchid=$sr["SwitchIDToSwitchToAlternateRank"];
                    if ($this->getSwitch(-$switchid, FALSE)) {
                        $stopid=$hwdata["StopID"];
                        $hwdata["StopID"]=-$stopid;
                        $hwdata["Name"] .= " (trem)";
                        parent::createStop($hwdata);
                        $this->getStop(+$stopid)->Switch($this->getSwitch(-$switchid));
                        $this->getStop(-$stopid)->Switch($this->getSwitch(+$switchid));
                        break;
                    }
                }
            }
        }
        return $switch;
    }
    
    public function createRank(array $hwdata, bool $keynoise=FALSE): ?\GOClasses\Rank {
        if (isset($hwdata["Noise"]) && $hwdata["Noise"]=="Ambient") {
            return NULL;
        }
        $rankid=intval($hwdata["RankID"]);
        $stopids=isset($hwdata["StopIDs"]);

        if (!$stopids) {
            $hwdata["StopIDs"]=[];
            foreach ($this->hwdata->rankStop($rankid) as $rankstop) {
                $hwdata["StopIDs"][]=$stopid=$rankstop["StopID"];
                $division=$this->hwdata->stop($stopid)["DivisionID"];
                $hwdata["GroupID"]=$division;
            }
        }
        
        if (isset($hwdata["GroupID"])) {
            $rank=parent::createRank($hwdata);
            if ($rank) {return $rank;}
        }
        
    
        if (!$stopids) {
            $hwdata["StopIDs"]=[];
            unset($hwdata["GroupID"]);
            foreach ($this->hwdata->altRankStop($rankid) as $rankstop) {
                $hwdata["StopIDs"][]=-($stopid=$rankstop["StopID"]);
                $division=$this->hwdata->stop($stopid)["DivisionID"];
                $hwdata["GroupID"]=$division;
            }
            if (isset($hwdata["GroupID"])) {
                $rank=parent::createRank($hwdata);
                if ($rank) {return $rank;}
            }
        }
        
        return NULL;
        
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