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

require_once (__DIR__ . "/../../CMBUtils/ODF.php");

class ODFTest extends \PHPUnit\Framework\TestCase {
    
    private function write(string $data, string $filename) : void {
        $fp=fopen($filename, "w");
        fwrite($fp, $data);
        fclose($fp);
    }
    
    private function read(string $filename) : string {
        $fp=fopen($filename, "r");
        $buffer="";
        while (!feof($fp)) {
            $buffer .= fgets($fp);
        }
        fclose($fp);
        return $buffer;
    }    
    
    /**
     * @covers read(), getLine(), getItem();
     */
    public function testRead() {
       
        $this->write(
                 ";comment\n"
                . "\n" // Empty
                . "[Section1]\n"
                . "A=Element A\n"
                . "B=Element B\n"
                . ";Another comment\n"
                . "[Section2]\n"
                . "\n" // Empty
                . "[Section3]\n"
                . "A=Element A\n"
                . "C=Element C\n",
                "read.cmb");
        $odf=new ODF();
        $odf->read("read.cmb");
        
        $this->assertEquals(";comment", $odf->getLine(0));
        $this->assertEquals("", $odf->getLine(1));
        $this->assertEquals("[Section1]", $odf->getLine(2));
        $this->assertEquals(['A' => 'Element A'], $odf->getLine(3));
        $this->assertEquals(['B' => 'Element B'], $odf->getLine(4));
        $this->assertEquals(";Another comment", $odf->getLine(5));
        $this->assertEquals("[Section2]", $odf->getLine(6));
        $this->assertEquals("", $odf->getLine(7));
        $this->assertEquals("[Section3]", $odf->getLine(8));
        $this->assertEquals(['A' => 'Element A'], $odf->getLine(9));
        $this->assertEquals(['C' => 'Element C'], $odf->getLine(10));
        
        $this->assertEquals('Element A', $odf->getItem("Section3", "A"));
    }
    
    /**
     * @covers write, setLine(), newSection();
     * 
     */
    function testWrite() {
        
        $data=";comment\n"
                . "\n" // Empty
                . "[Section1]\n"
                . "A=Element A\n"
                . "B=Element B\n"
                . ";Another comment\n"
                . "[Section2]\n"
                . "\n" // Empty
                . "[Section3]\n"
                . "A=Element A\n"
                . "C=Element C\n";
        $this->write($data, "read.cmb");
        $odf=new ODF("read.cmb");
        $odf->write("write.cmb");
        $this->assertEquals($data, $this->read("write.cmb"));
        
        $odf->setLine(0, ";A new comment");
        $odf->setLine(10, ["X"=>"Element X", "Y"=>"Element Y"]);
        $odf->write("write.cmb");
        $this->assertEquals(";A new comment\n"
                . "\n" // Empty
                . "[Section1]\n"
                . "A=Element A\n"
                . "B=Element B\n"
                . ";Another comment\n"
                . "[Section2]\n"
                . "\n" // Empty
                . "[Section3]\n"
                . "A=Element A\n"
                . "X=Element X\n"
                . "Y=Element Y\n", 
            $this->read("write.cmb"));
    }
    
    /**
     * @covers newLine(), newSection(), getSection();
     */
    public function testNewSection() {
        $odf=new ODF();
        $data=["A"=>"Element A", "B"=>"ELement B"];
        $this->assertEquals(0, $odf->newLine(";comment 1"));
        $odf->newSection("X", $data);
        $this->assertEquals(3, $odf->newLine(";comment 2"));
        $odf->newSection("Y", $data);
        $this->assertEquals(6, $odf->newLine(";comment 3"));
        
        $this->assertEquals("Element A", $odf->getItem("X", "A"));
        $this->assertEquals(["A"=>4, "B"=>5], $odf->getSection("Y"));
    }
}