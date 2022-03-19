<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for GOLoader
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/GOLoader.php");

class TestClass extends GOLoader {
}

class GOLoaderTest extends \PHPUnit\Framework\TestCase {
    
    public function testOrgan() {
        $go=new GOLoader();
        $sections=$go->load(__DIR__ . "/../ODF/demo.organ");
        $this->assertEquals(129, sizeof($sections));
        foreach($sections as $name=>$section) {
            echo "ODF $name\n";
        }
        
        $go->index();
        $this->assertEquals(0, sizeof($go->Pipes));
        $this->assertEquals(0, sizeof($go->Ranks));
        $this->assertEquals(0, sizeof($go->Stops));
        $this->assertEquals(0, sizeof($go->StopRanks));
    }
    
    public function testCmb() {
        $go=new GOLoader();
        $sections=$go->load(__DIR__ . "/../ODF/Friesach.cmb", "gz");
        $this->assertEquals(2971, sizeof($sections));
        foreach($sections as $name=>$section) {
            echo "CMB $name\n";
        }
    }
    
}