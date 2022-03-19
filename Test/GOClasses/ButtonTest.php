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
require_once (__DIR__ . "/../../GOClasses/Button.php");
require_once (__DIR__ . "/../../GOClasses/Sw1tch.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");

class ButtonTest extends \PHPUnit\Framework\TestCase {
    
    public function testButton() {
        $button=new Button("Test Button");
        
        $this->assertEquals(
            "[Button001]\n" .
            "Name=Test Button\n" , (string) $button);
        
        $button->DispLabelText="Label Text";
        $button->DispLabelColour="Label Colour";
        $this->assertEquals(
            "[Button001]\n" .
            "Name=Test Button\n" .
            "DispLabelText=Label Text\n" .
            "DispLabelColour=Label Colour\n", (string) $button);
        
        $button->Displayed="N";
        $this->assertFalse(isset($button->DispLabelText));
        $this->assertFalse(isset($button->DispLabelColour));
    }
    
    public function testPosRC() {
        $button=new Button("Test Button");
        $this->assertNull($button->Displayed);
        $button->posRC(3,4);
        $this->assertEquals(3, $button->DispButtonRow);
        $this->assertEquals(4, $button->DispButtonCol);
        $this->assertEquals("Y", $button->Displayed);
    }
    
    public function testPosXY() {
        $button=new Button("Test Button");
        $this->assertNull($button->Displayed);
        $button->posXY(12,34);
        $this->assertEquals(12, $button->PositionX);
        $this->assertEquals(34, $button->PositionY);
        $this->assertEquals("Y", $button->Displayed);
    }
    
    public function testSwitch() {
        $organ=new Organ("Test");
        $button=new Button("Test Button");
        new Sw1tch("Switch 1");
        $switch=new Sw1tch("Switch 2");
        $button->Switch($switch);
        $this->assertEquals(1,$button->SwitchCount);
        $this->assertEquals(2,$button->Switch001);
    }
    
}