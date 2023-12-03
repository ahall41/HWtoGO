<?php

require_once (__DIR__ . "/Read_Cmb.php");
require_once (__DIR__ . "/Write_Cmb.php");

/**
 * Description of SetCombinations
 *
 * @author andrew
 */
class SetCombinations {
    
    private $data=NULL;
    
    public static function main(array $argv) : void {
        unset ($argv[0]);
        foreach ($argv as $arg) {
            (new SetCombinations())->run($arg);
        }
    }
    
    public function run(string $sourcefn) : void {
        $this->data=$source=Read_Cmb("$sourcefn.cmb");
        foreach ($source as $section=>$values) {
            /* if (substr($section, 0, 13)=="SetterGeneral") {
                echo "$section\n";
            } 
            foreach($values as $key=>$value) {
                if (substr($key, 0, 4)=="MIDI") {
                    echo "$section: $key=$value\n";
                }
            } */
        }

        $this->setCXRow();
        
        $ndiv=0;
        for ($div=1; $div<=8; $div++) {
            $s=sprintf("Setter%03dDivisional", $div);
            if ($this->setRow($s, $div)) {$ndiv++;}
        }

        $general=0;
        for ($row=$ndiv; $row<8; $row++) {
            for ($col=0; $col<8; $col++) {
                if ($row==7 && $col==7) {continue;}
                $s=sprintf("SetterGeneral%d", $general);
                $this->setNote($s, $row, $col);
                $general++;
            }
        }

        $this->setNote("SetterSet", 7, 7);
        
        Write_Cmb($this->data, "$sourcefn.SZ64.cmb");
        echo $sourcefn, "\n";
    }
    
    private function setCXRow() : void {
        static $cx=[7, 74, 71, 76, 77, 93, 73, 75];
        for ($col=0; $col<8; $col++) {
            $section=sprintf("Setter000Divisional%03d", $col);
            if (isset($this->data[$section])) {
                $this->data[$section]["MIDIChannel001"]=1;
                $this->data[$section]["MIDIDebounce001"]=0;
                $this->data[$section]["MIDIDevice001"]="";
                $this->data[$section]["MIDIEventType001"]="ControlChange";
                $this->data[$section]["MIDIKey001"]=$cx[$col];
                $this->data[$section]["MIDILowerLimit001"]=0;
                $this->data[$section]["MIDIUpperLimit001"]=0;
                $this->data[$section]["NumberOfMIDIEvents"]=1;
            }
            // echo "$section=CX ", $cx[$col], "\n";
        }
    }
    
    private function setRow(string $setter, int $row) : bool {
        $isset=FALSE;
        for($col=0; $col<8; $col++) {
            $s=sprintf("${setter}%03d", $col);
            if ($this->setNote($s, $row, $col)) {$isset=TRUE;}
        }
        return $isset;
    }
    
    private function setNote(string $section, string $row, string $col) : bool {
        static $notes=[64, 65, 66, 67, 96, 97, 98, 99];
        if (isset($this->data[$section])) {
            $note=$notes[$col] - $row*4;
            $this->data[$section]["MIDIChannel001"]=10;
            $this->data[$section]["MIDIDebounce001"]=0;
            $this->data[$section]["MIDIDevice001"]="";
            $this->data[$section]["MIDIEventType001"]="NoteOn";
            $this->data[$section]["MIDIKey001"]=$note;
            //$this->data[$section]["MIDILowerLimit001"]=0;
            $this->data[$section]["MIDIUpperLimit001"]=0;
            $this->data[$section]["NumberOfMIDIEvents"]=1;
            // echo "$section=Note $note\n";
            return TRUE;
        }
        // echo "$section=Not set\n";
        return FALSE;
    }
}

if ($argc>1) {
    SetCombinations::main($argv);
}
else {
    $p="/home/andrew/GrandOrgue/Settings";
    SetCombinations::main([
        0, 
        "${p}/Demo",
        "${p}/Buckeburg",
        "${p}/Burton-Berlin",
        "${p}/Friesach",
        "${p}/New Haven"
    ]);
}