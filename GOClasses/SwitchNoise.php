<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/Stop.php");

/**
 * Representation of a GrandOrgue Stop Noise
 * @todo: Rename as SwitchNoise!
 *
 * @author andrew
 */
class SwitchNoise extends Stop {

    private Noise $noise;

    public function __construct($name, $section="", $instance=TRUE) {
        if (empty($section)) $section="Stop";
        parent::__construct($name, $section, $instance); 
        // Default properties
        unset($this->NumberOfRanks);
        $this->FirstAccessiblePipeLogicalKeyNumber=1;
        $this->FirstAccessiblePipeLogicalPipeNumber=1;
        $this->NumberOfAccessiblePipes=1;
        $this->NumberOfLogicalPipes=1;
        $this->StoreInDivisional="N";
        $this->StoreInGeneral="N";
        $this->Displayed="N";
        $this->AcceptsRetuning="N";
        $this->DefaultToEngaged="Y";
        
        $this->noise=new Noise();
    } 
    
    /**
     * The associated Noise
     * @return Noise
     */
    public function Noise() : Noise {
        return $this->noise;
    }
    
    /**
     * Set a rank - disabled !!!
     */
    public function Rank($rank) : void {
        throw new \Exception("Rank disabled for class NoiseStop");
    }

    /**
     * Set the WindchestGroup
     * @param type $windchestgroup
     * @return WindchestGroup
     */
    public function WindchestGroup($windchestgroup) : void {
        $this->WindchestGroup=intval($windchestgroup->instance());
    }
    
    public function __toString() : string {
        $this->Percussive=$this->noise->Percussive;
        $result=parent::__toString() . $this->noise; 
        return $result;
    }
}