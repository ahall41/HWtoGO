<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for GOObject
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Organ.php");

class OrganTest extends \PHPUnit\Framework\TestCase {
    
    public function testOrgan() {
        $organ1=new Organ("Organ 1");
        $this->assertEquals(
            "[Organ]\n" .
            "ChurchName=Organ 1\n" .
            "ChurchAddress=Organ 1\n" .
            "OrganBuilder=PHP ODF Builder\n" .
            "OrganBuildDate=" . date("Y-m-d") . "\n" .
            "OrganComments=Organ 1. Built by PHP GO Classes\n" .
            "RecordingDetails=Built by PHP GO Classes\n" .
            "NumberOfManuals=0\n" .
            "HasPedals=Y\n" .
            "NumberOfEnclosures=0\n" .
            "NumberOfTremulants=0\n" .
            "NumberOfWindchestGroups=0\n" .
            "NumberOfReversiblePistons=0\n" .
            "NumberOfGenerals=0\n" .
            "NumberOfDivisionalCouplers=0\n" .
            "NumberOfPanels=0\n" .
            "NumberOfSwitches=0\n" .
            "NumberOfRanks=0\n" .
            "DivisionalsStoreIntermanualCouplers=N\n" .
            "DivisionalsStoreIntramanualCouplers=N\n" .
            "DivisionalsStoreTremulants=N\n" .
            "GeneralsStoreDivisionalCouplers=N\n", (string) $organ1);
        $this->assertEquals($organ1,Organ::Organ());
        
        $organ2=new Organ("Organ 2");
        $this->assertEquals("Organ 2", $organ2->ChurchName);
        $this->assertEquals("Organ 2", Organ::Organ()->ChurchName);
        $this->assertEquals($organ2,Organ::Organ());
    }
}