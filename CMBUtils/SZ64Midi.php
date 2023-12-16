<?php

require_once (__DIR__ . "/Read_Cmb.php");
require_once (__DIR__ . "/Write_Cmb.php");

/**
 * Add midi events buttons for SubZero ControlPad 64
 *
 * @author andrew
 */
class SZ64Midi {
    
    private $data=NULL;
    
    public static function main(array $argv) : void {
        unset ($argv[0]);
        foreach ($argv as $item=>$data) {
            (new SZ64Midi())->run($item, $data);
        }
    }
    
    public function run(string $sourcefn, array $divisions) : void {
        $this->data=$source=Read_Cmb("$sourcefn.cmb");
        /* foreach ($source as $section=>$values) {
            if (substr($section, 0, 13)=="SetterGeneral") {
                echo "$section\n";
            } 
            foreach($values as $key=>$value) {
                if (substr($key, 0, 4)=="MIDI") {
                    echo "$section: $key=$value\n";
                }
            }
        } */

        $this->purge();
        
        $ndiv=sizeof($divisions);
        $row=0;
        foreach ($divisions as $div) {
            if ($div==0) {
                $this->setCXRow();
                continue;
            }
            $s=sprintf("Setter%03dDivisional", $div);
            if ($this->setRow($s, $row)) {
                $ndiv++;
                $n=sprintf("Setter%03dDivisionalNextBank", $div);
                $this->setNote($n, 7, 2);
                $p=sprintf("Setter%03dDivisionalPrevBank", $div);
                $this->setNote($p, 7, 4);
            }
            ++$row;
        }

        $general=0;
        while ($row<7) {
            for ($col=0; $col<8; $col++) {
                $s=sprintf("SetterGeneral%d", $general);
                $this->setNote($s, $row, $col);
                ++$general;
            }
            ++$row;
        }

        
        $this->setNote("SetterNext", 7, 0);
        $this->setNote("SetterP10", 7, 1);
        $this->setNote("SetterP100", 7, 2);
        $this->setNote("SetterGeneralNext", 7, 2);
        $this->setNote("SetterCurrent", 7, 3);
        $this->setNote("SetterGeneralPrev", 7, 4);
        $this->setNote("SetterM100", 7, 4);
        $this->setNote("SetterM10", 7, 5);
        $this->setNote("SetterPrev", 7, 6);
        $this->setNote("SetterSet", 7, 7);
        
        Write_Cmb($this->data, "$sourcefn.SZ64.cmb");
        echo $sourcefn, "\n";
    }
    
    /**
     * Purge existing MIDI and Combinations
     * 
     * @return void
     */
    private function purge() : void {
        $numbers=[
            "NumberOfMIDIEvents", 
            "NumberOfCouplers",
            "NumberOfDivisionalCouplers",
            "NumberOfStops",
            "NumberOfSwitches",
            "NumberOfTremulants"];
        
        $excludes=["Enclosure", "Manual", "SetterGC", "Switch", "General"];
        
        foreach($this->data as $section=>$values) {
            $skip=FALSE;
            foreach ($excludes as $exclude) {
                if (substr($section, 0, strlen($exclude))==$exclude) {
                    $skip=TRUE;
                    break;
                }
            }
            if ($skip) {continue;}
            
            foreach($numbers as $number) {
                if (isset($values[$number]) && $values[$number]>0) {
                    $this->data[$section][$number]=0;
                    // echo $section, "\t", $number, "\n";
                }
            }
        }
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
        $this->setNote("Setter000DivisionalNextBank", 7, 2);
        $this->setNote("Setter000DivisionalPrevBank", 7, 4);
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
            $this->data[$section]["MIDIUpperLimit001"]=1;
            $this->data[$section]["NumberOfMIDIEvents"]=1;
            //echo "setNote($section, $row, $col) -> $note\n";
            return TRUE;
        }
        // echo "$section=Not set\n";
        return FALSE;
    }
}

if ($argc>1) {
    SZ64Midi::main($argv);
}
else {
    $p="/home/andrew/GrandOrgue/Settings";
    SZ64Midi::main([
        0, 
        "$p/Buckeburg"=>[0,1,2,3],
        "$p/Burton-Berlin"=>[0,1,2,3],
        "$p/Friesach"=>[0,1,2,3],
        "$p/Redeemer"=>[0,1,2,3,4],
        "$p/Szikszo"=>[0,1,2,3,4],
    ]);
}