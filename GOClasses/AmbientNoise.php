<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/Ambience.php");
require_once(__DIR__ . "/Stop.php");

/**
 * Representation of a an Ambient Noise sound effect.
 * Extension of Stop, with suitable defaults (e.g. Engaged)
 *
 * @author andrew
 */
class AmbientNoise extends Stop {

    private Ambience $ambience;

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
        $this->Percussive="N";
        
        $this->ambience=new Ambience();
        
    } 
    
    public function Ambience() : Ambience {
        return $this->ambience;
    }
    
    public function Rank($rank, $firstPipe=NULL, $pipeCount=NULL, $firstKey=NULL) : void {
    }

    public function WindchestGroup($windchestgroup) : void {
        $this->WindchestGroup=intval($windchestgroup->instance());
    }
    
    
    public function __toString() : string {
        $result=parent::__toString() . $this->ambience; 
        return $result;
    }
}