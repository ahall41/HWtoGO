<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Drawstop
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Drawstop.php");
require_once (__DIR__ . "/../../GOClasses/Sw1tch.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");

class DrawstopTest extends \PHPUnit\Framework\TestCase {
    
    public function testDrawstop() {
        $organ=new Organ("Test");
        $drawstop=new Drawstop("Test Drawstop");
        
        $this->assertEquals(
            "[Drawstop001]\n" .
            "Name=Test Drawstop\n" .
            "DefaultToEngaged=N\n", (string) $drawstop);
    }
    
    public function testSwitch() {
        new Organ("Test");
        $drawstop=new Drawstop("Test Drawstop");
        new Sw1tch("Switch 1");
        $switch=new Sw1tch("Switch 2");
        $drawstop->Switch($switch);
        $this->assertEquals(1,$drawstop->SwitchCount);
        $this->assertEquals(2,$drawstop->Switch001);
    }
    
}