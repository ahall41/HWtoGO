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
    protected string $section="Manual";
    private int $currentX=-1;
    
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
        $this->setObject("Switch", "NumberOfSwitches", $switch);
    }

    public function Coupler(Coupler $coupler) {
        $this->setObject("Coupler", "NumberOfCouplers", $coupler);
    }

    public function Sw1tch(Sw1tch $switch) {
        $this->setObject("Switch", "NumberOfSwitches", $switch);
    }
    
    public function Key(?int $key=NULL) : string {
        if ($key===NULL) {
            if (isset($this->DisplayKeys))
                $key=++$this->DisplayKeys;
            else    
                $key=$this->DisplayKeys=1;        }
        return "Key" . $this->int2str($key);
    }

    public function set(string $name, ?string $value): void {
        switch($name) {
            case "FirstAccessibleKeyMIDINoteNumber":
            case "NumberOfLogicalKeys":
                if (empty($value)) return;
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
            $key=$this->Key($this->DisplayKeys -1);
            $this->set("${key}Width", $x-$this->currentX);
        }
        $this->currentX=$x;
    }
    
}
