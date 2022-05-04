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
require_once (__DIR__ . "/../../GOClasses/GOObject.php");

class ObjectClass1 extends GOObject {
    protected string $section="FirstClass";

    protected function nextInstance(): ?int {
        return NULL;
    }
}

class ObjectClass2 extends GOObject {
    protected string $section="SecondClass";
}

class GOObjectTest extends \PHPUnit\Framework\TestCase {
    
    public function testNoInstances() {
        $object=new ObjectClass1();
        $this->assertEquals("[FirstClass]\n", (string) $object);
        $object->Property="Value";
        $this->assertEquals("[FirstClass]\nProperty=Value\n", (string) $object);
    }
    
    public function testInstances() {
        $object=new ObjectClass2();
        $this->assertEquals("[SecondClass001]\n", (string) $object);
        $object->Property="Value";
        $this->assertEquals("[SecondClass001]\nProperty=Value\n", (string) $object);

        $object=new ObjectClass2();
        $this->assertEquals("[SecondClass002]\n", (string) $object);
    }
    
}