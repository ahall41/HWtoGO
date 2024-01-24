<?php

/* 
 * Read in pitch data, and create a .csv spreadsheet for manipulation
 */

require_once __DIR__ . "/ODF.php";

class PitchToCSV {
    
    private array $data=[];
    
    static function main(array $args) : void {
        (new PitchToCSV())->run($args[1]);
    }
    
    public function run(String $id) : void {
        $this->readODF("/$id.organ");
        $this->readLA($id, "fft");
        $this->readLA($id, "hps");
        $this->readLA($id, "td");
        $this->readPraat($id);
        $this->writeCsv($id);
    }
        
    /**
     * 
     * @param String $filename
     * @return void
     */
    private function readODF(String $filename) : void {
        $odf=new ODF(__DIR__ . "$filename");
        foreach ($odf->getIndex() as $section=>$items) {
            if (substr($section, 0, 4)=="Rank") {
                $percussive=$odf->getItem($section, "Percussive", "N");
                $rankhn=$odf->getItem($section, "HarmonicNumber", 8);
                $pipes=intval($odf->getItem($section, "NumberOfLogicalPipes"));
                
                for ($p=1; $p<=$pipes; $p++) {
                    $pipe=sprintf("Pipe%03d", $p);
                    $attack=$odf->getItem($section, $pipe);
                    if (empty($attack)) {continue;}
                    if ($odf->getItem($section, "${pipe}Percussive", $percussive)=="Y") {
                        continue;
                    }

                    $hn=$odf->getItem($section, "${pipe}HarmonicNumber", $rankhn);
                   
                    $explode=explode("/", $attack);
                    $size=sizeof($explode);
                    if ($size>2) {
                        $fn=$this->maskFn($explode[$size-2] . "-" . $explode[$size-1]);
                        $this->data[$fn]["pipe"]=$p;
                        //echo "\$this->data[$fn][pipe]=",  $this->data[$fn]["pipe"], "\n";
                        $key=intval($explode[$size-1]);
                        $this->data[$fn]["key"]=intval($key);
                        //echo "\$this->data[$fn][key]=",  $this->data[$fn]["key"], "\n";
                        $this->data[$fn]["hn"]=intval($hn);
                        //echo "\$this->data[$fn][hn]=",  $this->data[$fn]["hn"], "\n";
                    }
                }
            }
        }
    }
    
    private function readLA($id, $type) {
        $fp=fopen(__DIR__ . "/$id.$type", "r");
        while (!feof($fp)) {
            $line=explode("=", trim(fgets($fp)));
            switch (trim($line[0])) {
                case "FFT detected pitch":
                case "HPS detected pitch":
                case "Detected pitch in time domain":
                    $this->data[$fn][$type]=floatval(trim($line[1]));
                    //echo "\$this->data[$fn][$type]=", $this->data[$fn][$type], "\n";
                    break;
                
                case "MIDIKeyNumber":
                case "MIDIPitchFraction":
                    break;
                
                default:
                    $fn=$this->maskFn(trim($line[0]));
            }
            
        }
        fclose($fp);
    }
    
    private function readPraat(String $id) : void {
        $fp=fopen(__DIR__ . "/$id.praat", "r");
        while (!feof($fp)) {
            $line=trim(fgets($fp));
            $explode=explode(" ",$line);
            switch (trim($explode[0])) {
                case "frames":
                    $frame=intval(trim($explode[1],"[ ]:"));
                    break;

                case "candidates":
                    $candidate=intval(trim($explode[1],"[ ]:"));
                    break;
            }

            $explode=explode("=",$line);
            switch (trim($explode[0])) {
                case "name":
                    $name=trim($explode[1], ' "');
                    $candidate=0;
                    break;

                case "frequency":
                    if ($candidate==1) {
                        $this->data[$name]["praat"][$frame]=floatval($explode[1]);
                        //echo "\$this->data[$name][praat][$frame]=", $this->data[$name]["praat"][$frame], "\n";
                    }
                    break;
            }
        }
        fclose($fp);
    }
    
    private function writeCsv(String $id) : void {
        $fp=fopen(__DIR__ . "/$id.csv", "w");
        fwrite($fp, "File\tPipe\tKey\tHN\tFFT\tHPS\tTD\tPraat\tPD\n");
        foreach ($this->data as $fn=>$data) {
            if (sizeof($data)==7) {
                fprintf($fp, "%s\t%s\t%s\t%s\t%s\t%s\t%s\t",
                        $fn, $data["pipe"], $data["key"], $data["hn"], $data["fft"], $data["hps"], $data["td"]);
                
                $praat=$data["praat"];
                $size=sizeof($praat);
                if ($size>2) {
                    $total=0.0;
                    $pd="";
                    for ($i=2; $i<$size; $i++) {
                        $total+=$praat[$i];
                        $pd.=sprintf("%0.2f,", $praat[$i]);
                    }
                    fprintf($fp, "%0.2f\t%s\n", $total/($size-2), trim($pd,","));
                }
                else {
                    fwrite($fp, "\n");
                }
                
            }
        }
    }
    
    private function maskFn(String $filename) : String {
        return str_replace(
                [".wav", " ", ".", ",", "#"],
                ["", "_", "_", "_", "_"],
                 $filename);
    }
}

PitchToCSV::main(["", "Cheltenham"]);