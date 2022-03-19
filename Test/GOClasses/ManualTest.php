<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for GO Manual Object
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Manual.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");

class ManualTest extends \PHPUnit\Framework\TestCase {
    
    public function testManual() {
        $organ=new Organ("Test Organ");
        $manual=new Manual("Test Manual");
        $this->assertEquals("000", $manual->instance());
        $this->assertEquals(
            "[Manual000]\n" .
            "Name=Test Manual\n" .
            "NumberOfLogicalKeys=30\n" .
            "FirstAccessibleKeyLogicalKeyNumber=1\n" .
            "FirstAccessibleKeyMIDINoteNumber=36\n" .
            "NumberOfAccessibleKeys=30\n" .
            "MIDIInputNumber=1\n" .
            "Displayed=Y\n" .
            "NumberOfStops=0\n" .
            "NumberOfCouplers=0\n" .
            "NumberOfDivisionals=0\n" .
            "NumberOfTremulants=0\n" .
            "NumberOfSwitches=0\n", (string) $manual);
        $this->assertEquals(0, $organ->NumberOfManuals); // Exclude pedal
        $manual=new Manual("Test Manual", "m2");
        $this->assertEquals(1, $organ->NumberOfManuals);
        
        $organ=new Organ("Test Organ");
        $organ->HasPedals=0;
        $manual=new Manual("Test Manual", "m1");
        $this->assertEquals(
            "[Manual001]\n" .
            "Name=Test Manual\n" .
            "NumberOfLogicalKeys=61\n" .
            "FirstAccessibleKeyLogicalKeyNumber=1\n" .
            "FirstAccessibleKeyMIDINoteNumber=36\n" .
            "NumberOfAccessibleKeys=61\n" .
            "MIDIInputNumber=2\n" .
            "Displayed=Y\n" .
            "NumberOfStops=0\n" .
            "NumberOfCouplers=0\n" .
            "NumberOfDivisionals=0\n" .
            "NumberOfTremulants=0\n" .
            "NumberOfSwitches=0\n", (string) $manual);
        $this->assertEquals(1, $organ->NumberOfManuals);
        $manual=new Manual("Test Manual", "m2");
        $this->assertEquals(2, $organ->NumberOfManuals);
    }
    /** @todo Stop(), Switch() etc */
}