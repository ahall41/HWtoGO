<?php

/* 
 * Read a .cmb file * 
 */

function Read_Cmb(string $filename) : array
{
    $fp=gzopen($filename, "r");
    gzread($fp, 3);
    $buffer=[];
    $section=NULL;
    while (!gzeof($fp)) {
        $line=trim(gzgets($fp));
        if (preg_match("/^\[.*\]/", $line)) {
            $section=trim($line, "[]");
            $buffer[$section]=[];
        }
        else {
            $split=explode("=", $line, 2);
            $buffer[$section][$split[0]]=$split[1];
        }
    }
    gzclose($fp);
    return $buffer;
}