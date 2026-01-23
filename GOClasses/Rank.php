<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/GOObject.php");

/**
 * Representation of a GrandOrgue Rank - a set of 1 or more pipes
 *
 * @author andrew
 */
class Rank extends GOObject {
    protected string $section="Rank";
    protected $pipes=[];
    private bool $sorted=FALSE;
    
    public function __construct(string $name) {
        parent::__construct($name);
        $this->FirstMidiNoteNumber=0;
        $this->NumberOfLogicalPipes=0;
        $this->WindchestGroup=1;
        $this->Percussive="N";
        $this->AcceptsRetuning="Y";
        Organ::Organ()->NumberOfRanks++;
    }

    /**
     * Set WindchestGroup for the rank
     * @param WindchestGroup $windchestgroup
     * @return void
     */
    public function WindchestGroup(WindchestGroup $windchestgroup) : void  {
        $this->WindchestGroup=intval($windchestgroup->instance());
    }

    /**
     * Update pipe data prior to extraction
     * 
     * @return void
     */
    private function storePipes() : void {
        if (!$this->sorted) ksort($this->pipes, SORT_NUMERIC);
        $this->sorted=TRUE;
        if (($this->NumberOfLogicalPipes=sizeof($this->pipes))) {
            $firstMidi=array_key_first($this->pipes);
        }
            
        else {
            $firstMidi=36;
            $this->NumberOfLogicalPipes=1;
            $this->Pipe001="DUMMY";
        }
        if (empty($this->FirstMidiNoteNumber)) {
            $this->FirstMidiNoteNumber=$firstMidi;
        }
    }    

    /**
     * The current set of pipes
     * @return array
     */
    public function Pipes() : array {
        return  $this->pipes;
    }

    /**
     * Get (or create) a pipe
     * @param int $midikey
     * @param mixed $pipe. Either an instance of Pipe (for cloning), or TRUE
     * @return Pipe|null
     */
    public function Pipe(int $midikey, $pipe=NULL) : ? Pipe {
        if ($pipe instanceof Pipe)
            return $this->pipes[$midikey]=clone($pipe);
        elseif (array_key_exists($midikey, $this->pipes))
            return $this->pipes[$midikey];
        elseif ($pipe)
            return $this->pipes[$midikey]=new Pipe();
        else
            return NULL;
    }
    
    /**
     * Remove a pipe
     * 
     * @param int $midikey
     */
    public function removePipe(int $midikey) {
        unset($this->pipes[$midikey]);
    }
    
    public function Substitute(int $fromKey, int $toKey) : ?Pipe {
        $pipe=$this->Pipe($fromKey);
        if ($pipe) {
            $pipe=$this->Pipe($toKey, $pipe);
            $pipe->PitchTuning+=100*($toKey-$fromKey);
        }
        return $pipe;
    }
    
    public function __toString() : string {
        $this->storePipes();
        $result=parent::__toString();
        $pid=0;
        foreach ($this->pipes as $pipe) {
            $pipe->Pipe=++$pid;
            $result .= $pipe;
        }
        return $result;
    }
}