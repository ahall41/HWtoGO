<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/Button.php");

/**
 * Representation of a GrandOrgue General Combination object

 *  * @author andrew
 */
class General extends Button {
    protected string $section="General";

    function __construct(?string $name = NULL) {
        parent::__construct($name);
        $this->NumberOfStops=0;
        $this->NumberOfCouplers=0;
        $this->NumberOfTremulants=0;
        $this->NumberOfSwitches=0;
        $this->NumberOfDivisionalCouplers=0;
    }
    
    public function Coupler(Coupler $coupler, Manual $manual) {
        $this->setObject("CouplerNumber", "NumberOfCouplers", $coupler);
        $n=$this->int2str($this->NumberOfCouplers);
        $this->set("CouplerManual$n", $manual->instance());
    }

    public function Stop(Stop $stop, Manual $manual) {
        $this->setObject("StopNumber", "NumberOfStops", $stop);
        $n=$this->int2str($this->NumberOfStops);
        $this->set("StopManual$n", $manual->instance());
    }
    
    public function Switch(Sw1tch $switch) : void {
        $this->setObject("SwitchNumber", "NumberOfSwitches", $switch);
    }
    
    public function Tremulant(Tremulant $tremulant) : void {
        $this->setObject("TremulantNumber", "NumberOfTremulants", $tremulant);
    }
    
}