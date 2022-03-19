<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for GO Enclosure Object
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Enclosure.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");

class EnclosureTest extends \PHPUnit\Framework\TestCase {
    
    public function testEnclosure() {
        $organ=new Organ("Test Organ");
        $enclosure=new Enclosure("Test Enclosure");
        $this->assertEquals(
            "[Enclosure001]\n" .
            "Name=Test Enclosure\n" .
            "AmpMinimumLevel=20\n", (string) $enclosure);
        $this->assertEquals(1, $organ->NumberOfEnclosures);
        $enclosure=new Enclosure("Test Enclosure", "e2");
        $this->assertEquals(2, $organ->NumberOfEnclosures);
        
        $enclosure->MouseRectLeft="";
        $enclosure->MouseRectTop=NULL;
        $this->assertEquals(0, $enclosure->MouseRectLeft);
        $this->assertEquals(0, $enclosure->MouseRectTop);
    }
}