<?php

require_once (__DIR__ . "/Read_Cmb.php");
require_once (__DIR__ . "/Write_Cmb.php");

/**
 * Replace USB MIDI with "Any"
 *
 * @author andrew
 */
class USBAny {
    
    private $data=NULL;
    
    public static function main(array $argv) : void {
        unset ($argv[0]);
        foreach ($argv as $item) {
            (new USBAny())->run($item);
        }
    }
    
    public function run(string $sourcefn) : void {
        $this->data=$source=Read_Cmb($sourcefn);
        $this->purge();
        Write_Cmb($this->data, $sourcefn);
        echo $sourcefn, "\n";
    }
    
    /**
     * Purge USB MIDI 
     * 
     * @return void
     */
    private function purge() : void {
        foreach($this->data as $section=>$values) {
            foreach($values as $item=>$data) {
                if (substr($item, 0, 10)=="MIDIDevice" &&
                    strpos($data, "alsa: USB MIDI Interface")!==FALSE) {
                    //echo "$section: $item=$data\n";
                    unset($this->data[$section][$item]);
                }
            }
        }
            
    }
}

if ($argc>1) {
    USBAny::main($argv);
}
else {
    $p="/home/andrew/Downloads/dry/Settings";
    USBAny::main([
        0, 
        "$p/Cheltenham.cmb"
    ]);
}