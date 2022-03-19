<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Rank
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Rank.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");
require_once (__DIR__ . "/../../GOClasses/WindchestGroup.php");
require_once (__DIR__ . "/../../GOClasses/Pipe.php");

class RankTest extends \PHPUnit\Framework\TestCase {
    
    public function testRank() {
        $organ=new Organ("Test");
        $rank=new Rank("Test Rank");
        $this->assertEquals(
            "[Rank001]\n" .
            "Name=Test Rank\n" . 
            "FirstMidiNoteNumber=36\n" .
            "NumberOfLogicalPipes=1\n" .
            "WindchestGroup=1\n" . 
            "Percussive=N\n" .
            "AcceptsRetuning=Y\n" .
            "Pipe001=DUMMY\n", (string) $rank);
        $this->assertEquals(1, $organ->NumberOfRanks);
        
        $wg=new WindchestGroup("Test");
        $wg=new WindchestGroup("Test");
        $rank->WindchestGroup($wg);
        $this->assertEquals(2, $rank->WindchestGroup);
    }
    
    public function testPipes() {
        $organ=new Organ("Test");
        $rank=new Rank("Test Rank");
        
        $this->assertNull($rank->Pipe(36));
        $p36=$rank->Pipe(36,TRUE);
        $this->assertTrue($p36 instanceof Pipe);
        $this->assertEquals($p36, $rank->Pipe(36));
        
        $p37=new Pipe();
        $rank->Pipe(37,$p37);
        $this->assertEquals($p37, $rank->Pipe(37));

        $this->assertEquals($p36, $rank->Pipe(36, $p37));

        $this->assertEquals(2, sizeof($rank->Pipes()));
    }
}