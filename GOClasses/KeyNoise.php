<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/Rank.php");
require_once(__DIR__ . "/Noise.php");

/**
 * Representation of a GrandOrgue Rank of Key Noises
 *
 * @author andrew
 */
class KeyNoise extends Rank {
    public function __construct(string $name) {
        parent::__construct($name);
        $this->AcceptsRetuning="N";
    }

    /*
     * As Rank->Pipe, but returns a Noise
     */
    public function Pipe($midikey, $pipe=FALSE) : ? Pipe {
        if (array_key_exists($midikey, $this->pipes))
            return $this->pipes[$midikey];
        elseif ($pipe instanceof Pipe)
            return $this->pipes[$midikey]=clone($pipe);
        elseif ($pipe)
            return $this->pipes[$midikey]=new Noise();
        else
            return NULL;
    }
}