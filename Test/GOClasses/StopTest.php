<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Stop
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Stop.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");
require_once (__DIR__ . "/../../GOClasses/Manual.php");
require_once (__DIR__ . "/../../GOClasses/Rank.php");

class StopTest extends \PHPUnit\Framework\TestCase {
    
    public function testStop() {
        $organ=new Organ("Test");
        $stop=new Stop("Test Stop");
        
        $this->assertEquals(
            "[Stop001]\n" .
            "Name=Test Stop\n" .
            "DefaultToEngaged=N\n" .
            "NumberOfRanks=0\n" .
            "FirstAccessiblePipeLogicalKeyNumber=1\n" .
            "NumberOfAccessiblePipes=61\n" .
            "FirstAccessiblePipeLogicalPipeNumber=1\n" .
            "NumberOfLogicalPipes=1\n" .
            "Pipe001=DUMMY\n" .
            "WindchestGroup=1\n" .
            "Percussive=N\n", (string) $stop);
        
        $rank=new Rank("test");
        $stop->Rank($rank);
        $this->assertEquals(
            "[Stop001]\n" .
            "Name=Test Stop\n" .
            "DefaultToEngaged=N\n" .
            "NumberOfRanks=1\n" .
            "FirstAccessiblePipeLogicalKeyNumber=1\n" .
            "NumberOfAccessiblePipes=61\n" .
            "Rank001=1\n", (string) $stop);

        $rank=new Rank("test");
        $stop->Rank($rank);
        $this->assertEquals(2, $stop->NumberOfRanks);
        $this->assertEquals(2, $stop->Rank002);
    }
}