<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Divisional Combination object
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Divisional.php");
require_once (__DIR__ . "/../../GOClasses/Coupler.php");
require_once (__DIR__ . "/../../GOClasses/Stop.php");
require_once (__DIR__ . "/../../GOClasses/Sw1tch.php");
require_once (__DIR__ . "/../../GOClasses/Tremulant.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");

class DivisionalTest extends \PHPUnit\Framework\TestCase {
    
    public function testDivisional() {
        $divisional=new Divisional("Test Divisional");
        
        $this->assertEquals(
            "[Divisional001]\n"
            . "Name=Test Divisional\n"
            . "NumberOfStops=0\n"
            . "NumberOfCouplers=0\n"
            . "NumberOfTremulants=0\n"
            . "NumberOfSwitches=0\n" , (string) $divisional);
    }
    
    public function testCoupler() {
        new Organ("Test");
        $divisional=new Divisional("Test Divisional");
        new Coupler("Coupler 1");
        $coupler=new Coupler("Coupler 2");
        $divisional->Coupler($coupler);
        $this->assertEquals(1,$divisional->NumberOfCouplers);
        $this->assertEquals(2,$divisional->Coupler001);;
    }

    public function testStop() {
        new Organ("Test");
        $divisional=new Divisional("Test Divisional");
        new Stop("Stop 1");
        $stop=new Stop("Stop 2");
        $divisional->Stop($stop);
        $this->assertEquals(1,$divisional->NumberOfStops);
        $this->assertEquals(2,$divisional->Stop001);
    }

    public function testSwitch() {
        new Organ("Test");
        $divisional=new Divisional("Test Divisional");
        new Sw1tch("Switch 1");
        $switch=new Sw1tch("Switch 2");
        $divisional->Switch($switch);
        $this->assertEquals(1,$divisional->NumberOfSwitches);
        $this->assertEquals(2,$divisional->Switch001);
    }

    public function testTremulant() {
        new Organ("Test");
        $divisional=new Divisional("Test Divisional");
        new Tremulant("Tremulant 1");
        $tremulant=new Tremulant("Tremulant 2");
        $divisional->Tremulant($tremulant);
        $this->assertEquals(1,$divisional->NumberOfTremulants);
        $this->assertEquals(2,$divisional->Tremulant001);
    }
}