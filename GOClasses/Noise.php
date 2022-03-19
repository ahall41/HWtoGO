<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/Pipe.php");

/**
 * Representation of a GrandOrgue Noise effect (stop/key)
 * Extension of pipe; PitchTuning is ignored.
 * Typically this will be used for attack (percussive) or release (non-percussive),
 * in which case Percussive is set as appropriate
 * If no attack is given then a default (see static $blankloop) blank loop 
 * will be used. Obtain one from Piotr Grabowski's sample sets! 
 *
 * @todo: Suppress duplicates of the same file?
 * 
 * @author andrew
 */
class Noise extends Pipe {

    public static $blankloop="";
    
    public function __construct() {
        parent::__construct();
        $this->Pipe=1;
        $this->Percussive="Y";
    }
    
    public function set(string $property, ?string $value) : void  {
        switch ($property) {
            case "PitchTuning":
                return;
        }

        parent::set($property, $value);

        switch ($property) {
            case "Attack":
                parent::set("AttackLoadRelease", "N");
                break;
            
            case "Release":
                $this->Percussive="N";
                break;
        }
    }

    public function __toString() : string {
        if ($this->AttackCount<0) {
            $data=$this->data;
            $this->Attack=self::$blankloop;
            $result=parent::__toString();
            $this->data=$data;
            return $result;
        }
        else
            return parent::__toString();
    }
    
}