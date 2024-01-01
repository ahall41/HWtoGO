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
 *
 * @author andrew
 */
class Divisional extends Button {
    protected string $section="Divisional";
    
    function __construct(?string $name = NULL) {
        parent::__construct($name);
        $this->NumberOfStops=0;
        $this->NumberOfCouplers=0;
        $this->NumberOfTremulants=0;
        $this->NumberOfSwitches=0;
    }
    
    public function Coupler(Coupler $coupler) {
        $this->setObject("Coupler", "NumberOfCouplers", $coupler);
    }

    public function Stop(Stop $stop) {
        $this->setObject("Stop", "NumberOfStops", $stop);
        
    }
    
    public function Switch(Sw1tch $switch) : void {
        $this->setObject("Switch", "NumberOfSwitches", $switch);
    }
    
    public function Tremulant(Tremulant $tremulant) : void {
        $this->setObject("Tremulant", "NumberOfTremulants", $tremulant);
    }
}
