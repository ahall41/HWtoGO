<?php

/* 
 * Copy MIDI settings from 1 .cmb file to another
 */

require_once (__DIR__ . "/Read_Cmb.php");
require_once (__DIR__ . "/Write_Cmb.php");

function Copy_MIDI(array $args=[]) {
    if (sizeof($args)==0) {$args=$argv;}
    $source=$args[1];
    $target=$args[2];
    $outfile=isset($args[3]) ? $args[3] : $args[2];
    
    $sourcedata=Read_Cmb($source);
    $targetdata=Read_Cmb($target);
    
    foreach($sourcedata as $sectionkey=>$section) {
        foreach($section as $key=>$value) {
            if (substr($key, 0, 4)=="MIDI" || $key=="NumberOfMIDIEvents"    ) {
                if (array_key_exists($sectionkey, $targetdata)) {
                    echo "$sectionkey: $key=$value\n";
                    $targetdata[$sectionkey][$key]=$value;
                }
            }
        }
    }
    Write_Cmb($targetdata, $outfile);
}
