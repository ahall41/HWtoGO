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
require_once (__DIR__ . "/../../GOClasses/Panel.php");
require_once (__DIR__ . "/../../GOClasses/Organ.php");
require_once (__DIR__ . "/../../GOClasses/PanelElement.php");
require_once (__DIR__ . "/../../GOClasses/Stop.php");
require_once (__DIR__ . "/../../GOClasses/Manual.php");
require_once (__DIR__ . "/../../GOClasses/Sw1tch.php");
require_once (__DIR__ . "/../../GOClasses/Enclosure.php");

class PanelTest extends \PHPUnit\Framework\TestCase {
    
    public function testPanel() {
        new Organ("Test");
        $panel=new Panel("Test Panel");
        
        $this->assertEquals(
            "[Panel000]\n" .
            "Name=Test Panel\n" .
            "HasPedals=Y\n" .
            "DispButtonCols=10\n" .
            "DispButtonsAboveManuals=N\n" .
            "DispConsoleBackgroundImageNum=19\n" .
            "DispControlLabelFont=Arial\n" .
            "DispDrawstopBackgroundImageNum=17\n" .
            "DispDrawstopCols=4\n" .
            "DispDrawstopColsOffset=Y\n" .
            "DispDrawstopInsetBackgroundImageNum=19\n" .
            "DispDrawstopOuterColOffsetUp=N\n" .
            "DispDrawstopRows=7\n" .
            "DispExtraButtonRows=1\n" .
            "DispExtraDrawstopCols=3\n" .
            "DispExtraDrawstopRows=4\n" .
            "DispExtraDrawstopRowsAboveExtraButtonRows=Y\n" .
            "DispExtraPedalButtonRow=N\n" .
            "DispExtraPedalButtonRowOffset=Y\n" .
            "DispExtraPedalButtonRowOffsetRight=Y\n" .
            "DispGroupLabelFont=Arial\n" .
            "DispKeyHorizBackgroundImageNum=18\n" .
            "DispKeyVertBackgroundImageNum=13\n" .
            "DispPairDrawstopCols=N\n" .
            "DispScreenSizeHoriz=Medium\n" .
            "DispScreenSizeVert=Medium\n" .
            "DispShortcutKeyLabelColour=Yellow\n" .
            "DispShortcutKeyLabelFont=Arial\n" .
            "DispTrimAboveExtraRows=N\n" .
            "DispTrimAboveManuals=N\n" .
            "DispTrimBelowManuals=N\n" .
            "NumberOfGUIElements=0\n" .
            "NumberOfImages=0\n", (string) $panel);
    }
    
    public function testGUIElement() {
        new Organ("Test");
        $panel=new Panel("Test Panel");
        
        $stop=new Stop("Test Stop");
        $pstop=$panel->GUIElement($stop);
        $this->assertEquals(
                "[Panel000Element001]\n" .
                "Type=Stop\n" .
                "Stop=001\n", (string) $pstop);
        
        for ($i=0; $i<9; $i++) new Sw1tch("Test Switch $i");
        $switch=new Sw1tch("Test Switch 10");
        $pswitch=$panel->GUIElement($switch);
        $this->assertEquals(
                "[Panel000Element002]\n" .
                "Type=Switch\n" .
                "Switch=010\n", (string) $pswitch);
        
        $enclosure=new Enclosure("Test Enclosure");
        $penclosure=$panel->GUIElement($enclosure);
        $this->assertEquals(
                "[Panel000Element003]\n" .
                "Type=Enclosure\n" .
                "Enclosure=001\n", (string) $penclosure);
    }
    
    public function testLabel() {
        new Organ("Test");
        new Panel("Test Panel 1");
        $panel=new Panel("Test Panel 2");
        $panel->Label("Test Label 1");
        $element=$panel->Label("Test Label 2");
        $this->assertEquals(
                "[Panel001Element002]\n" .
                "Name=Test Label 2\n" .
                "Type=Label\n" .
                "DispImageNum=1\n", (string) $element);
    }
    
    public function testImage() {
        new Organ("Test");
        new Panel("Test Panel 1");
        $panel=new Panel("Test Panel 2");
        $element=$panel->Image("Path/Name", 12, 34);
        $this->assertEquals(
                "[Panel001Image001]\n" .
                "Image=Path/Name\n" .
                "PositionX=12\n" .
                "PositionY=34\n", (string) $element);
    }
}