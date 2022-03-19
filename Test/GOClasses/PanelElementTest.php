<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Panel ELement
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/PanelElement.php");

class PanelElementTest extends \PHPUnit\Framework\TestCase {
    
    public function testPanelElement1() {
        $panelElement=new PanelElement("Panel123Element");
        
        $this->assertEquals(
            "[Panel123Element001]\n", (string) $panelElement);
    }

    public function testPanelElement2() {
        new PanelElement("Panel456Element");
        $panelElement=new PanelElement("Panel456Element");
        $panelElement->Name="Name";
        $panelElement->DispLabelText="Label";
        
        $this->assertEquals(
            "[Panel456Element002]\n" .
            "Name=Name\n" .
            "DispLabelText=Label\n", (string) $panelElement);
    }
}