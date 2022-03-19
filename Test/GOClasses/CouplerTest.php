<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Button
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Coupler.php");

class CouplerTest extends \PHPUnit\Framework\TestCase {
    
    public function testCoupler() {
        $coupler=new Coupler("Test Coupler");
        $this->assertEquals(
            "[Coupler001]\n" .
            "Name=Test Coupler\n" .
            "DefaultToEngaged=N\n" .
            "DispLabelColour=Dark Green\n" .
            "UnisonOff=N\n" .
            "DestinationKeyshift=0\n" .
            "CoupleToSubsequentUnisonIntermanualCouplers=N\n" .
            "CoupleToSubsequentUpwardIntermanualCouplers=N\n" .
            "CoupleToSubsequentDownwardIntermanualCouplers=N\n" .
            "CoupleToSubsequentUpwardIntramanualCouplers=N\n" .
            "CoupleToSubsequentDownwardIntramanualCouplers=N\n",             
                (string) $coupler);
        
        $coupler->UnisonOff=1;
        $this->assertEquals(
            "[Coupler001]\n" .
            "Name=Test Coupler\n" .
            "DefaultToEngaged=N\n" .
            "DispLabelColour=Dark Green\n" .
            "UnisonOff=1\n", (string) $coupler);
        
    }
}