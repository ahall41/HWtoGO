<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for AmbientNoise
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/AmbientNoise.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");
require_once (__DIR__ . "/../../GOClasses/Manual.php");
require_once (__DIR__ . "/../../GOClasses/Noise.php");

class AmbientNoiseTest extends \PHPUnit\Framework\TestCase {
    
    public function testAmbientNoise() {
        Noise::$blankloop="Blankloop.wav";
        $organ=new Organ("Test");
        $stop=new AmbientNoise("Test Stop");
        
        $this->assertEquals(
            "[Stop001]\n" .
            "Name=Test Stop\n" .
            "DefaultToEngaged=Y\n" .
            "FirstAccessiblePipeLogicalKeyNumber=1\n" .
            "NumberOfAccessiblePipes=1\n" .
            "FirstAccessiblePipeLogicalPipeNumber=1\n" .
            "NumberOfLogicalPipes=1\n" .
            "StoreInDivisional=N\n" .
            "StoreInGeneral=N\n" .
            "Displayed=N\n" .
            "AcceptsRetuning=N\n" .
            "Percussive=N\n" .
            "Pipe001AttackCount=-1\n" .
            "Pipe001ReleaseCount=0\n" .
            "Pipe001Percussive=N\n", (string) $stop);
        
        $this->assertTrue($stop->Ambience() instanceof Ambience);
        
    }
}