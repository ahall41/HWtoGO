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
require_once(__DIR__ . "/Manual.php");

/**
 * Representation of a GrandOrgue Stop
 *
 * @author andrew
 */
class Stop extends Drawstop {
    protected string $section="Stop";
    private $ranks=[];

    public function __construct(string $name) {
        parent::__construct($name);
        $this->NumberOfRanks=0;
        $this->FirstAccessiblePipeLogicalKeyNumber=1;
        $this->NumberOfAccessiblePipes=Manual::$keys;
    } 
    
    /**
     * Add a rank to the stop
     * @param Rank $rank
     * @return void
     */
    public function Rank(Rank $rank) : void {
        $this->setObject("Rank", "NumberOfRanks", $rank);
        $r=$this->int2str($this->NumberOfRanks);
        $this->ranks[$r]=$rank;
    }
    
    /**
     * Get the attached ranks
     * @return array
     */
    public function Ranks() : array {
        return $this->ranks;
    }

    public function __toString() : string {
        if (get_class($this)=="GOClasses\Stop"
                && empty($this->NumberOfRanks)) {
            $data=$this->data;
            $this->FirstAccessiblePipeLogicalPipeNumber=1;
            $this->NumberOfLogicalPipes=1;
            $this->Pipe001="DUMMY";
            $this->WindchestGroup=1;
            $this->Percussive="N";
            $result=parent::__toString();
            $this->data=$data;
            return $result;
        }
        return parent::__toString();
    }
}