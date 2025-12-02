<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/GOBase.php");

/**
 * Representation of a GrandOrgue Pipe.
 * Usually initiated within Rank->Pipe($key, TRUE)
 *
 * @author andrew
 */
class Pipe extends GOBase {
    private $dummy=FALSE;
    private $pipe="000";
    private $storeRelease=TRUE;
    private $midikey=0;
        
    public function __construct() {
        $this->AttackCount=-1;
        $this->ReleaseCount=0;
    }
    
    public function Dummy(bool $isdummy=TRUE) {
        $this->dummy=$isdummy;
    }
    
    public function IsDummy() : bool {
        return  $this->dummy;
    }
    
    public function set(string $property, ?string $value) : void  {
        if ($value==="" || $value===NULL) return;
        
        switch ($property) {
            case "Pipe":
                $this->pipe=$this->int2str($value);
                break;
                
            case "Attack":
                $this->AttackCount++;
                $attack=$this->attack();
                parent::set($attack,$value);
                break;
            
            case "Attack000":
                parent::set("",$value);
                break;

            case "Release":
                // HW release is same as attack ?
                $matched=FALSE;
                $attack="";
                for ($a=0; $a<=$this->AttackCount; $a++) {
                    $attack=$this->attack($a);
                    if (strtoupper($this->data["${attack}"])==strtoupper($value)) {
                        $matched=TRUE;
                        break;
                    }
                }
                if ($matched) {
                    $this->storeRelease=FALSE;
                    parent::set("${attack}LoadRelease","Y");
                } 
                else {
                    $this->ReleaseCount++;
                    $release=$this->release();
                    parent::set($release, $value);
                    $this->storeRelease=TRUE;
                    parent::set("LoadRelease","N");
                    for ($a=1; $a<=$this->AttackCount; $a++)
                        parent::set(sprintf("Attack%03dLoadRelease", $a), "N");
                    parent::set("${release}MaxKeyPressTime", -1);
                }
                break;
                
            case "AttackVelocity":
            case "AttackStart":
                parent::set($this->attack() . $property, $value);
                break;

            case "AttackLoadRelease":
            case "AttackMaxKeyPressTime":
            case "AttackIsTremulant":
            case "AttackReleaseEnd":
                parent::set(
                        $this->attack() . str_replace("Attack","",$property), 
                        $value);
                break;

            case "AttackCuePoint":
            case "AttackMaxTimeSinceLastRelease":
                if ($value>0)
                    parent::set(
                            $this->attack() . str_replace("Attack","",$property), 
                            $value);
                break;

            case "LoopStart":
                $loopCount=$this->attack() . "LoopCount";
                parent::set($loopCount, parent::get($loopCount, 0)+1);
                parent::set($this->loop() . "Start", $value);
                break;
                
            case "LoopEnd":
                parent::set($this->loop() . "End", $value);
                break;
            
            case "ReleaseMaxKeyPressTime":
                if ($this->storeRelease)
                    $this->data[$this->release() . "MaxKeyPressTime"]=
                        ($value>=99999) ? -1 : $value;
                break;

            case "ReleaseIsTremulant":
                if ($this->storeRelease)
                    parent::set(
                            $this->release() . str_replace("Release","",$property), 
                            $value);
                break;

            case "ReleaseCuePoint":
                if (!empty($value) && $this->storeRelease)
                    parent::set(
                            $this->release() . str_replace("Release","",$property), 
                            $value);
                break;

            case "ReleaseEnd":
                if (!empty($value) && $this->storeRelease )
                    parent::set(
                            $this->release() . $property, 
                            $value);
                break;

            case "PitchTuning":
                if (empty($value)) 
                    parent::unset($property);
                else {
                    $value=sprintf("%f",$value);
                    if (floatval($value)==0.0)
                        parent::unset($property);
                    else
                        parent::set($property, sprintf("%f",$value));
                }
                break;

            case "Gain":
                if (!empty($value))
                    parent::set($property, floatval($value));
                break;

            case "MIDIKeyNumber":
                $this->midikey=$value;
                break;
            
            case "MIDIKeyOverride":
                parent::set("MIDIKeyNumber", $value);
                break;
            
            case "ReleaseCrossfadeLength":
                if ($this->storeRelease) {
                    parent::set(
                            $this->release() . $property, 
                            $value);
                }
                break;    
                
            
            case "LoopCrossfadeLength":
                parent::set(
                        $this->attack() . $property,
                        $value);
                break;    

            default:
                parent::set($property, $value);

        }
    }

    public function get(string $property, ?string $default=NULL) : ? string {
        switch ($property) {
            case "Attack":
                return $this->get("");
            case "MIDIKeyNumber":
                return $this->midikey;
            case "MIDIKeyOverride":
                return parent::get("MIDIKeyNumber");
            default:
                return parent::get($property, $default);
        }
    }
    
    /**
     * Current attack ("", "Attack001" etc) prefix 
     * @param int $attack. Optional override of the current attack number
     * @return string
     */
    public function attack(?int $attack=NULL) : string {
        if (empty($attack)) $attack=$this->AttackCount;
        if ($attack>0)
            return "Attack" . $this->int2str($attack);
        else
            return "";
    }   

    /**
     * Current release ("Release001" ...) as string
     * @param int $release. Optional override of the current attack number
     * @return type
     */
    public function release(?int $release=NULL) : string {
        if (empty($release)) $release=$this->ReleaseCount;
        return "Release" . $this->int2str($release);
    }

    /**
     * Current attack loop ("Attack999Loop999") as a string
     * @return string
     */
    public function loop() : string {
        return ($attack=$this->attack()) . "Loop" . $this->int2str($this->data["${attack}LoopCount"]);
    }

    public function __toString() : string {
        $result="";
        $pipe=$this->pipe;
        if ($this->dummy)
            $result .= "Pipe{$pipe}=DUMMY\n";
        else {
            foreach ($this->data as $name => $value) 
                $result .= "Pipe{$pipe}${name}=${value}\n";
            }
        return $result;
    }
}