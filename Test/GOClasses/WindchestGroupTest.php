<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for GO WindchestGroup Object
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/WindchestGroup.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");
require_once (__DIR__ . "/../../GOClasses/Enclosure.php");
require_once (__DIR__ . "/../../GOClasses/Tremulant.php");

class WindchestGroupTest extends \PHPUnit\Framework\TestCase {
    
    public function testWindchestGroup() {
        $organ=new Organ("Test");
        $windchestGroup=new WindchestGroup("Test WindchestGroup");
        
        $this->assertEquals(
            "[WindchestGroup001]\n" .
            "Name=Test WindchestGroup\n" .
            "NumberOfEnclosures=0\n" .
            "NumberOfTremulants=0\n" , (string) $windchestGroup);
        $this->assertEquals(1, $organ->NumberOfWindchestGroups);
        
        $enclosure=new Enclosure("Test");
        $windchestGroup->Enclosure($enclosure);
        $this->assertEquals(
            "[WindchestGroup001]\n" .
            "Name=Test WindchestGroup\n" .
            "NumberOfEnclosures=1\n" .
            "NumberOfTremulants=0\n" . 
            "Enclosure001=1\n", (string) $windchestGroup);

        $tremulant=new Tremulant("Test");
        $windchestGroup->Tremulant($tremulant);
        $this->assertEquals(
            "[WindchestGroup001]\n" .
            "Name=Test WindchestGroup\n" .
            "NumberOfEnclosures=1\n" .
            "NumberOfTremulants=1\n" . 
            "Enclosure001=1\n" . 
            "Tremulant001=1\n",  (string) $windchestGroup);
    }
}