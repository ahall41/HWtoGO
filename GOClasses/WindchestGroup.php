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
class WindchestGroup extends GOObject {
    protected string $section="WindchestGroup";
    
    public function __construct(string $name) {
        parent::__construct($name);
        $this->NumberOfEnclosures=0;
        $this->NumberOfTremulants=0;
        Organ::Organ()->NumberOfWindchestGroups++;
    }  
    
    /**
     * Set an enclosure
     * @param type $enclosure
     */
    public function Enclosure($enclosure) {
        $this->setObject("Enclosure", "NumberOfEnclosures", $enclosure);
    }
    
    /**
     * Set a tremulant
     * @param type $tremulant
     */
    public function Tremulant($tremulant) {
        $this->setObject("Tremulant", "NumberOfTremulants", $tremulant);
    }

}