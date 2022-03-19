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
 * Representation of a GrandOrgue ambient sound (typically a blower)
 * Extension of Pipe, to be used in conjunction with AmbientNoise
 *
 * @todo: Suppress duplicates of the same file?
 * @todo: Extension of Noise?
 * 
 * @author andrew
 */
class Ambience extends Pipe {

    public static $blankloop="";
    
    public function __construct() {
        parent::__construct();
        parent::set("Pipe",1);
        $this->Percussive="N";
    }
    
    public function set(string $name, ?string $value): void {
        if ($name!="Pipe")
            parent::set($name, $value);
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