<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/Drawstop.php");

/**
 * Representation of a GrandOrgue Switch
 *
 * @author andrew
 */

class Sw1tch extends Drawstop {
    protected string $section="Switch";
    
    public function __construct(string $name) {
        parent::__construct($name);
        Organ::Organ()->NumberOfSwitches++;
    }
    
    /**
     * Link a switch. There should only be one
     * @param Sw1tch $switch
     * @return void
     */
    public function Switch(Sw1tch $switch) : void {
        parent::Switch($switch); 
        unset($this->SwitchCount);
    }
}