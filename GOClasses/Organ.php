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
 * Representation of a GrandOrgue Organ, setting suitable default properties
 * 
 * There can only be 1 Organ at any time! Creaying a new organ resets 
 * everything
 * 
 * The NumberOfXXX are maintained whenever the corresponding object is instantiated
 *
 * @author andrew
 */
class Organ extends GOObject {
    protected string $section="Organ";
    private static ?Organ $organ=NULL;
    
    /**
     * 
     * @param type $name
     */
    public function __construct(string $name) {
        self::reset();
        parent::__construct();        
        $this->ChurchName=$name;
        $this->ChurchAddress=$name;
        $this->OrganBuilder="PHP ODF Builder";
        $this->OrganBuildDate=date("Y-m-d");
        $this->OrganComments="$name. Built by PHP GO Classes";
        $this->RecordingDetails="Built by PHP GO Classes";
        $this->NumberOfManuals=0;
        $this->HasPedals="Y";
        $this->NumberOfEnclosures=0;
        $this->NumberOfTremulants=0;
        $this->NumberOfWindchestGroups=0;
        $this->NumberOfReversiblePistons=0;
        $this->NumberOfGenerals=0;
        $this->NumberOfDivisionalCouplers=0;
        $this->NumberOfPanels=0;
        $this->NumberOfSwitches=0;
        $this->NumberOfRanks=0;
        $this->DivisionalsStoreIntermanualCouplers="N";
        $this->DivisionalsStoreIntramanualCouplers="N";
        $this->DivisionalsStoreTremulants="N";
        $this->GeneralsStoreDivisionalCouplers="N";
        self::$organ=$this;
    }
    
    /**
     * Only 1 instance of Organ
     * @return int|null
     */
    protected function nextInstance(): ?int {
        return NULL;
    }

    /**
     * Return (or create) the organ object
     * 
     * @param string|null $name
     * @return Organ
     */
    public static function Organ() : Organ {
        return self::$organ;
    }
}
