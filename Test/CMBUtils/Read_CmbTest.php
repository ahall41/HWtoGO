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

require_once (__DIR__ . "/../../CMBUtils/Read_Cmb.php");

class Read_CmbTest extends \PHPUnit\Framework\TestCase {
    
    function gzwrite(string $data, string $filename) : void {
        $fp=gzopen($filename, "w");
        gzwrite($fp, chr(0xbb) . chr(0xef) . chr(0x5b));
        gzwrite($fp, $data);
        gzclose($fp);
    }
    
    public function testRead() {
       
        $this->gzwrite(
                "[Section1]\n"
                . "A=Element A\n"
                . "B=Element B\n"
                . "[Section2]\n"
                . "[Section3]\n"
                . "A=Element A\n"
                . "C=Element C\n",
                "read.cmb");
        // error_log(print_r(read_cmb("read.cmb"),1));
        $this->assertEquals(["Section1"=>["A"=>"Element A", "B"=>"Element B"],
                             "Section2"=>[],
                             "Section3"=>["A"=>"Element A", "C"=>"Element C"]
                            ], read_cmb("read.cmb"));
    }
}