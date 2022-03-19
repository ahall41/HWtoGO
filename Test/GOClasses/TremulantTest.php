<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Button
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Tremulant.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");

class TremulantTest extends \PHPUnit\Framework\TestCase {
    
    public function testTremulant() {
        $organ=new Organ("Test");

        $synth=new Tremulant("Synth Tremulant");
        $this->assertEquals(
            "[Tremulant001]\n" .
            "Name=Synth Tremulant\n" .
            "DispLabelColour=Blue\n" .
            "Period=196\n" .
            "AmpModDepth=15\n" .
            "StartRate=10\n" .
            "StopRate=6\n", (string) $synth);
        
        $wave=new Tremulant("Wave Tremulant", TRUE);
        $this->assertEquals(
            "[Tremulant002]\n" .
            "Name=Wave Tremulant\n" .
            "DispLabelColour=Blue\n" .
            "TremulantType=Wave\n", (string) $wave);
        $this->assertEquals(2, $organ->NumberOfTremulants);
    }
}