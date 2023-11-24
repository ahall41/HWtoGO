<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for read_cmb()
 ******************************************************************************/

require_once (__DIR__ . "/../../CMBUtils/Copy_MIDI.php");

class Copy_MIDITestClass extends \PHPUnit\Framework\TestCase {
    
    public function testCopyMIDI() {
        $argv=["", __DIR__ . "/../CMB/Source.cmb", __DIR__ . "/../CMB/Target.cmb", "Output.cmb"];
        Copy_MIDI($argv);
        $this->assertTrue(TRUE);
        
        $target=Read_Cmb(__DIR__ . "/../CMB/Target.cmb");
        $this->assertFalse(array_key_exists("MIDIEventType001", $target["Enclosure006"]));
        $this->assertFalse(array_key_exists("MIDIDevice001", $target["Switch088"]));
        
        $output=Read_Cmb("Output.cmb");
        $this->AssertEquals("ControlChange", $output["Enclosure006"]["MIDIEventType001"]);
        $this->AssertEquals("alsa: USB MIDI Interface MIDI 1", $output["Switch088"]["MIDIDevice001"]);
    }
}