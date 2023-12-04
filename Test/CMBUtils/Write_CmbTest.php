<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for write/read .cmb
 ******************************************************************************/

require_once (__DIR__ . "/../../CMBUtils/Write_Cmb.php");

class Write_CmbTestTestClass extends \PHPUnit\Framework\TestCase {
    
    function gzread(string $filename) : string {
        $fp=gzopen($filename, "r");
        gzread($fp, 3);
        $buffer="";
        while (!gzeof($fp)) {
            $buffer .= gzgets($fp);
        }
        gzclose($fp);
        return $buffer;
    }
    
    public function testWrite() {
        $data=["Section1"=>["A"=>"Element A","B"=>"Element B"],
               "Section2",
               "Section3"=>["A"=>"Element A","C"=>"Element C"]];
        write_cmb($data, "write.cmb");
        error_log($this->gzread("write.cmb"));
        $this->assertEquals(
                "[Section1]\n"
                . "A=Element A\n"
                . "B=Element B\n"
                . "[Section2]\n"
                . "[Section3]\n"
                . "A=Element A\n"
                . "C=Element C\n", $this->gzread("write.cmb"));
    }
}