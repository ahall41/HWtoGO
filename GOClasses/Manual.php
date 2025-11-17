<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace GOClasses;
require_once __DIR__ . "/GOObject.php";

/**
 * Representation of a GrandOrgue Manual, setting suitable default properties
 * 
 * @author andrew
 */
class Manual extends GOObject {
    public static $pedals=30;
    public static $keys=61;
    public static $firstLogicalKey=1;
    public static $firstMidiNote=36;
    public static $minWidth=10;
    protected string $section="Manual";
    private int $currentX=-1;
    private int $positionY=0;
    private $switches=[];
    
    public function __construct(string $name) {
        $section=$this->section;
        if (!isset(self::$Instances[$section])) 
            self::$Instances[$section]=Organ::Organ()->HasPedals=="Y" ? -1 : 0;
        parent::__construct($name);
        $instance=intval($this->instance());
        $iskeyboard=$instance>0;
        if ($iskeyboard) Organ::Organ()->NumberOfManuals++;

        // Default properties
        $this->Name=$name;
        $this->NumberOfLogicalKeys=($iskeyboard ? self::$keys : self::$pedals);
        $this->FirstAccessibleKeyLogicalKeyNumber=self::$firstLogicalKey;
        $this->FirstAccessibleKeyMIDINoteNumber=self::$firstMidiNote;
        $this->NumberOfAccessibleKeys=$this->NumberOfLogicalKeys;   
        $this->MIDIInputNumber=$instance+1;
        $this->Displayed="Y";
        $this->NumberOfStops=0;
        $this->NumberOfCouplers=0;
        $this->NumberOfDivisionals=0;
        $this->NumberOfTremulants=0;
        $this->NumberOfSwitches=0;
    }
    
    public function Stop(Stop $stop) : void {
        $this->setObject("Stop", "NumberOfStops", $stop);
    }

    public function Switch(Sw1tch $switch) : void {
        if (!isset($this->switches[$switch->instance()])) {
            $this->switches[$switch->instance()]=TRUE;
            $this->setObject("Switch", "NumberOfSwitches", $switch);
        }
        $switch->setYaml($this->instance(), $this->Name, $this->int2str($this->NumberOfSwitches));
    }

    public function Coupler(Coupler $coupler) {
        $this->setObject("Coupler", "NumberOfCouplers", $coupler);
    }

    public function Divisional(Divisional $divisional) {
        $this->setObject("Divisional", "NumberOfDivisionals", $divisional);
    }
    
    public function Key(?int $key=NULL) : string {
        if ($key===NULL) {
            if (isset($this->DisplayKeys))
                $key=++$this->DisplayKeys;
            else    
                $key=$this->DisplayKeys=1;
        }
        return "Key" . $this->int2str($key);
    }

    public function set(string $name, ?string $value): void {
        switch($name) {
            case "FirstAccessibleKeyMIDINoteNumber":
            case "NumberOfLogicalKeys":
                if (empty($value)) return;
                break;
                
            case "PositionY":
                $this->positionY=intval($value);
                break;
        }
        parent::set($name, $value);
    }
    
    /**
     * Set keywidth for required X position
     * 
     * @param int $x
     */
    public function KeyWidth(int $x) : void {
        if ($this->DisplayKeys>1) {
            $key=$this->Key($this->DisplayKeys-1);
            $delta=$x-$this->currentX;
            if ($delta<0) {
                $this->set("${key}Width",self::$minWidth);
                $this->set("${key}Offset",$delta);
                $this->currentX+=self::$minWidth;
            }
            else {
                $this->set("${key}Width", $x-$this->currentX);
                $this->currentX=$x;
            }
        }
        else {
            $this->currentX=$x;
        }
        
    }
    
    public function KeyOffsetY(int $y) : void {
        if ($y!=$this->positionY) {
            $key=$this->Key($this->DisplayKeys);
            $this->set("${key}Offset",$this->positionY-$y);
        }
        
    }
}
