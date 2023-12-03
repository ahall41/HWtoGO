<?php

/* 
 * Write a .cmb file * 
 */

function Write_Cmb(array $data, string $filename) : void
{
    $fp=gzopen($filename, "wb");
    // gzwrite($fp, chr(0xbb) . chr(0xef) . chr(0x5b));
    foreach($data as $key => $val)
    {
        if(is_array($val))
        {
            //error_log("[$key]");
            gzwrite($fp, "[$key]\n");
            foreach($val as $skey => $sval) {
                //error_log("$skey=$sval\n");
                gzwrite ($fp, "$skey=$sval\n");
            }
        }
        else {
            // Shouldn't get here ... 
            //error_log("[$val]\n");
            gzwrite ($fp, "[$val]\n");
        }
    }
    gzclose($fp);  
}