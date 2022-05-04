<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for GOBase
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/GOBase.php");

class BaseClass extends GOBase {
}

class GOBaseTest extends \PHPUnit\Framework\TestCase {
    
    public function testBase() {
        $object=new BaseClass();
        $this->assertTrue($object instanceof GOBase);
        $this->assertEquals("", (string) $object);
        
        $object->Property="Value";
        $object->set("OtherProperty", "OtherValue");
        $this->assertEquals("Value", $object->get("Property"));
        $this->assertEquals("OtherValue", $object->OtherProperty);
        $this->assertNull($object->Nothing);
        $this->assertNull($object->get("Nothing"));
        $this->assertEquals(
                "Property=Value\n" .
                "OtherProperty=OtherValue\n", (string) $object);
        
        $this->assertTrue(isset($object->Property));
        $this->assertTrue($object->isset("OtherProperty"));
        $this->assertFalse(isset($object->Nothing));
        $this->assertFalse($object->isset("Nothing"));
        
        unset($object->OtherProperty);
        $object->unset("Property");
        $this->assertFalse(isset($object->Property));
        $this->assertFalse($object->isset("OtherProperty"));
        $this->assertEquals("", (string) $object);
    }
}