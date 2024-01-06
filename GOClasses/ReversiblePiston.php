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
require_once(__DIR__ . "/Organ.php");

/**
 * Representation of a GrandOrgue ReversiblePiston
 *
 * @author andrew
 */
class ReversiblePiston extends Button {
    protected string $section="ReversiblePiston";

    public function __construct(string $name) {
        parent::__construct($name);
        Organ::Organ()->NumberOfReversiblePistons++;
    } 
    
    private function setObjectType(GOObject $object, string $type) : void {
        $this->ObjectType=$type;
        $this->ObjectNumber=$object->instance();
    }

    public function Coupler(Coupler $coupler) : void {
        $this->setObjectType($coupler, "COUPLER");
    }

    public function Stop(Stop $stop) : void {
        $this->setObjectType($stop, "STOP");
    }
    
    public function Switch(Sw1tch $switch) : void {
        $this->setObjectType($switch, "SWITCH");
    }
    
    public function Tremulant(Tremulant $tremulant) : void {
        $this->setObjectType($tremulant, "TREMULANT");
    }
}
