<?php

/*******************************************************************************
 * Unit tests for Import\WavReader
 ******************************************************************************/

namespace Import;
require_once (__DIR__ . "/../../Import/WavReader.php");

class WavReaderTest extends \PHPUnit\Framework\TestCase {
    
    public function testHeader() {
        $reader=new WavReader(__DIR__ . "/../WAV/036-c.wav");
        $this->assertEquals(
                [
                    "chunkid" => "RIFF",
                    "chunksize" => 1849954,
                    "format" => "WAVE"
                ], $reader->header());
        
        $reader->open(__DIR__ . "/../WAV/048-c.wav");
        $this->assertEquals(
                [
                    "chunkid" => "RIFF",
                    "chunksize" => 2004934,
                    "format" => "WAVE"
                ], $reader->header());
        
    }

    public function testChunks() {
        $reader=new WavReader(__DIR__ . "/../WAV/055-g.wav");
        $reader->header();
        $fmt=$reader->chunk();
        $this->assertEquals("fmt ", $fmt["id"]);
        $this->assertEquals(16, $fmt["size"]);
        $data=$reader->chunk();
        $this->assertEquals("data", $data["id"]);
        $this->assertEquals(2039352, $data["size"]);
        $smpl=$reader->chunk();
        $this->assertEquals("smpl", $smpl["id"]);
        $this->assertEquals(60, $smpl["size"]);
        $this->assertEquals(55, $smpl["MIDINote"]);
        $this->assertEquals(0.26304239872843027, $smpl["MIDICents"]);
        print_r($smpl);
        $cue=$reader->chunk();
        $this->assertEquals("cue ", $cue["id"]);
        $this->assertEquals(28, $cue["size"]);
        $acid=$reader->chunk();
        $this->assertEquals("acid", $acid["id"]);
        $this->assertEquals(24, $acid["size"]);
        
        $reader->open(__DIR__ . "/../WAV/036-c.wav");
        $reader->header();
        $reader->chunk(); // fmt
        $reader->chunk(); // data
        $smpl=$reader->chunk();
        $this->assertEquals(36, $smpl["MIDINote"]);
        $this->assertEquals(10.452933493070304, $smpl["MIDICents"]);
        
        $reader->open(__DIR__ . "/../WAV/048-c.wav");
        $reader->header();
        $reader->chunk(); // fmt
        $reader->chunk(); // data
        $smpl=$reader->chunk();
        $this->assertEquals(47, $smpl["MIDINote"]);
        $this->assertEquals(98.63919438794255, $smpl["MIDICents"]);
        
        $reader->open(__DIR__ . "/../WAV/036-c (tracker).wav");
        $reader->header();
        $reader->chunk(); // fmt
        $reader->chunk(); // fmt
        $reader->chunk(); // fmt
        
    }
}