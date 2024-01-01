<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for General Combination object
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/General.php");
require_once (__DIR__ . "/../../GOClasses/Coupler.php");
require_once (__DIR__ . "/../../GOClasses/Manual.php");
require_once (__DIR__ . "/../../GOClasses/Stop.php");
require_once (__DIR__ . "/../../GOClasses/Sw1tch.php");
require_once (__DIR__ . "/../../GOClasses/Tremulant.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");

class GeneralTest extends \PHPUnit\Framework\TestCase {
    
    public function testGeneral() {
        $general=new General("Test General");
        
        $this->assertEquals(
            "[General001]\n"
            . "Name=Test General\n"
            . "NumberOfStops=0\n"
            . "NumberOfCouplers=0\n"
            . "NumberOfTremulants=0\n"
            . "NumberOfSwitches=0\n"
            . "NumberOfDivisionalCouplers=0\n" , (string) $general);
    }
    
    public function testCoupler() {
        new Organ("Test");
        $general=new General("Test General");
        new Coupler("Coupler 1");
        $coupler=new Coupler("Coupler 2");
        $manual=new Manual("Manual 1");
        $general->Coupler($coupler, $manual);
        $this->assertEquals(1,$general->NumberOfCouplers);
        $this->assertEquals(2,$general->CouplerNumber001);
        $this->assertEquals(0,$general->CouplerManual001);
    }

    public function testStop() {
        new Organ("Test");
        $general=new General("Test General");
        new Stop("Stop 1");
        $stop=new Stop("Stop 2");
        new Manual("Manual 1");
        $manual=new Manual("Manual 2");
        $general->Stop($stop, $manual);
        $this->assertEquals(1,$general->NumberOfStops);
        $this->assertEquals(2,$general->StopNumber001);
        $this->assertEquals(1,$general->StopManual001);
    }

    public function testSwitch() {
        new Organ("Test");
        $general=new General("Test General");
        new Sw1tch("Switch 1");
        $switch=new Sw1tch("Switch 2");
        $general->Switch($switch);
        $this->assertEquals(1,$general->NumberOfSwitches);
        $this->assertEquals(2,$general->SwitchNumber001);
    }

    public function testTremulant() {
        new Organ("Test");
        $general=new General("Test General");
        new Tremulant("Tremulant 1");
        $tremulant=new Tremulant("Tremulant 2");
        $general->Tremulant($tremulant);
        $this->assertEquals(1,$general->NumberOfTremulants);
        $this->assertEquals(2,$general->TremulantNumber001);
    }
}