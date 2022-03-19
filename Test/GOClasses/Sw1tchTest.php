<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Sw1tch
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Sw1tch.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");

class Sw1tchTest extends \PHPUnit\Framework\TestCase {
    
    public function testSw1tch() {
        $organ=new Organ("Test");
        $sw1tch=new Sw1tch("Test Switch");
        
        $this->assertEquals(
            "[Switch001]\n" .
            "Name=Test Switch\n" .
            "DefaultToEngaged=N\n", (string) $sw1tch);
        $this->assertEquals(1, $organ->NumberOfSwitches);
        
        $sw1tch->Switch(new Sw1tch("Linked Switch"));
        $this->assertEquals(2, $sw1tch->Switch001);
        $this->assertFalse(isset($sw1tch->SwitchCount));
    }
}