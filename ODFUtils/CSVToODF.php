<?php

require_once(__DIR__ . "/ODF.php");
/**
 * Description of CSVToODF
 *
 * @author andrew
 */
class CSVToODF extends ODF {
    
    private array $pitchData=[];
    
    public function run(String $id) : void {
        $this->readPitchData(__DIR__ . "/$id.pc");
        $this->updateOrgan(false,"$id");
        $this->updateOrgan(true, "$id");
    }
    
    private function readPitchData(String $fn) : void {
        $fp=fopen($fn);
        while (!feof($fp)) {
            $line=explode("\t", trim(fgets($fp)));
            $pitchdata[$line[0][false]]=floatval($line[1]);
            $pitchdata[$line[0][true]]=floatval($line[2]);
        }
        fclose($fp);
    }
    
    private function updateOrgan(bool $usePraat, String $id) {
        $this->read(__DIR__ . "/$id.organ");
        foreach ($this->Index as $section=>$items) {
            if (substr($section, 0, 4)=="Rank") {
                $pipes=intval($odf->getItem($section, "NumberOfLogicalPipes"));
                for ($p=1; $p<=$pipes; $p++) {
                    $this->updatePitch($section, $p, $usePraat);
                }
            }
        }
    }
    
    private function updatePitch(String $rank, int $pipeNo, bool $usePraat) {
        $pipe=sprintf("Pipe%03d", $pipeNo);
        $attack=$this->getItem($pipe);
        if (!empty($attack)) {
            $midipitch=$this->getPitch($attack, $usePraat);
            if ($midipitch) {
                $override=floor($midipitch);
                $fraction=100*($midipitch-$override);
                
                if (empty($this->getItem($rank, "${pipe}MIDIKeyOverride"))) {
                    $this->buffer[$rank][$pipe] .= sprintf("/nMIDIKeyOverride=%d", $override);
                }
                else {
                    $this->buffer[$rank]["${pipe}MIDIKeyOverride"] = sprintf("%d", $override);
                }

                if (empty($this->getItem($rank, "${pipe}MIDIPitchFraction"))) {
                    $this->buffer[$rank][$pipe] .= sprintf("/nMIDIPitchFractione=%0.2f", $fraction);
                }
                else {
                    $this->buffer[$rank]["${pipe}MIDIPitchFraction"] = sprintf("%0.2f", $fraction);
                }
            }
        }
        $this->write(__DIR__ . "/$id." . ($usePraat ? "praat" : "std") . ".organ");
    }
}

(new CSVToODF())->run("Cheltanham");