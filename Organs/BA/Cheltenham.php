<?php 

/**
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 * @todo: Combinations + Reversible Pistons 
 * 
 */

namespace Organs\BA;
require_once(__DIR__ . "/BAOrgan.php");
require_once(__DIR__ . "/../../Import/AubioPitch.php");
require_once(__DIR__ . "/../../GOClasses/ReversiblePiston.php");


/**
 * Import Father Willis Organ of St. Matthew, Cheltenham to GrandOrgue
 * 
 * @author andrew
 */
class Cheltenham extends BAOrgan {
    
    const ROOT="/GrandOrgue/Organs/BA/Cheltenham/";
    const COMMENTS=
              "Father Willis, St. Matthew, Cheltenham\n"
            . "https://barrittaudio.co.uk/pages/st-matthew-cheltenham\n"
            . "\n"
            . "1.1 Added MIDKeyNumbers (derived from sample file name)\n"
            . "    Added Combinations and Reversible Pistons\n"
            . "1.2 Added MIDIPitchFraction\n"
            . "1.3 Corrected Sesquialter Harmonic Number; softened tremulant\n"
            . "\n";

    protected $patchDisplayPages=[
        1=>["SetID"=>1],
        2=>["SetID"=>2],
        3=>["SetID"=>3],
    ];
    
    protected $patchEnclosures=[
        220=>["GroupIDs"=>[301], "Panels"=>[1=>18, 2=>20], "AmpMinimumLevel"=>30],
    ];
    
    protected $patchTremulants=[
        1720=>["Type"=>"Synth", "GroupIDs"=>[301], "Period"=>200, "AmpModDepth"=>10],
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"Pedal On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"Choir On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"Great On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"Swell On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -1=>["StopID"=>-1, "DivisionID"=>2, "Name"=>"Pedal Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>1, "Name"=>"Choir Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"Great Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -4=>["StopID"=>-4, "DivisionID"=>4, "Name"=>"Swell Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      1006=>["DivisionID"=>1], 
      1011=>["DivisionID"=>1],   
      1016=>["DivisionID"=>1],  
      1110=>["DivisionID"=>1],  
      1111=>["DivisionID"=>1],  
      1112=>["DivisionID"=>1],   
      1116=>["DivisionID"=>1], 
      1720=>["DivisionID"=>4, "Ambient"=>TRUE, "GroupID"=>700], // Tremulant */
      2601=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    // Inspection of Ranks object
    protected $patchRanks=[
        100=>["Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2601]],
        101=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
        102=>["Noise"=>"StopOff", "GroupID"=>700, "StopIDs"=>[]],
        103=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+2]],
        104=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-2]],
        105=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+1]],
        106=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-1]],
        107=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+3]],
        108=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-3]],
        109=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+4]],
        110=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-4]],
    ];
    
    protected $combinations=[ // Don't show on panel 1 - its a 1x1 .png !!!
        ["SwitchIDs"=>[/* 1=>10068, */ 2=>10069], "ManualID"=>2, "SetterID"=>0],
        ["SwitchIDs"=>[/* 1=>10070, */ 2=>10071], "ManualID"=>2, "SetterID"=>1],
        ["SwitchIDs"=>[/* 1=>10072, */ 2=>10073], "ManualID"=>2, "SetterID"=>2],
        ["SwitchIDs"=>[/* 1=>10074, */ 2=>10075], "ManualID"=>2, "SetterID"=>3],
        ["SwitchIDs"=>[/* 1=>10076, */ 2=>10077], "ManualID"=>3, "SetterID"=>0],
        ["SwitchIDs"=>[/* 1=>10078, */ 2=>10079], "ManualID"=>3, "SetterID"=>1],
        ["SwitchIDs"=>[/* 1=>10080, */ 2=>10081], "ManualID"=>3, "SetterID"=>2],
    ];
    
    protected $pistons=[
        ["SwitchIDs"=>[1=>10230, 2=>10231], "SwitchID"=>10207, "Name"=>"Sw to Gt"],
        ["SwitchIDs"=>[1=>10233, 2=>10234], "SwitchID"=>10103, "Name"=>"Ped Reed 16"],
        ["SwitchIDs"=>[1=>10236, 2=>10237], "SwitchID"=>10216, "Name"=>"Gt to Ped"],
    ];
    
    public function createManuals(array $keyboards): void {
        foreach([1,4,2,3] as $id) {
            parent::createManual($keyboards[$id]);
        }
    }
    
    public function import() : void {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 2:
                    echo ($instanceID=$instance["ImageSetInstanceID"]), "\t",
                         isset($instance["AlternateScreenLayout1_ImageSetID"]) ? 1 : "", "\t",
                         isset($instance["AlternateScreenLayout2_ImageSetID"]) ? 2 : "", "\t",
                         $instance["Name"], ": ";
                    foreach ($this->hwdata->switches() as $switch) {
                        if (isset($switch["Disp_ImageSetInstanceID"])  && 
                               $switch["Disp_ImageSetInstanceID"]==$instanceID)
                            echo $switch["SwitchID"], " ",
                                 $switch["Name"], ", ";
                    }
                    echo "\n";
            }
        } 
        exit(); //*/
        parent::import();
        $this->createCombinations();
        $this->createPistons();
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (isset($hwdata["Noise"]) && $hwdata["Noise"]=="Ambient")
            return NULL;
        else
            return parent::createRank($hwdata, $keynoise);
    }
  
    private function createCombinations() : void {
        foreach ($this->combinations as $combinationdata) {
            foreach($combinationdata["SwitchIDs"] as $panelid=>$switchid) {
                $panel=$this->getPanel($panelid);
                $pe=$panel->Element();
                $pe->Type=sprintf("Setter%03dDivisional%03d",$combinationdata["ManualID"],$combinationdata["SetterID"]);
                $this->configurePanelSwitchImage($pe, ["SwitchID"=>$switchid]);
            }
        }
    }

    private function createPistons() : void {
        foreach ($this->pistons as $pistondata) {
            $switch=$this->getSwitch($pistondata["SwitchID"]);
            $piston=new \GOClasses\ReversiblePiston($pistondata["Name"]);
            $piston->Switch($this->getSwitch($pistondata["SwitchID"]));
            foreach($pistondata["SwitchIDs"] as $panelid=>$switchid) {
                $panel=$this->getPanel($panelid);
                $pe=$panel->GUIElement($piston);
                $this->configurePanelSwitchImage($pe, ["SwitchID"=>$switchid]);
            }
        }
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        $switchid=$data["SwitchID"];
        $slinkid=$this->hwdata->switchLink($switchid)["D"][0]["SourceSwitchID"];
        foreach($this->hwdata->switchLink($slinkid)["S"] as $link) {
            $switchdata=$this->hwdata->switch($destid=$link["DestSwitchID"]);
            if (isset($switchdata["Disp_ImageSetInstanceID"])) {
                $instancedata=$this->hwdata->imageSetInstance($instanceid=$switchdata["Disp_ImageSetInstanceID"]);
                $panel=$this->getPanel($instancedata["DisplayPageID"]);
                $panelelement=$panel->GUIElement($switch);
                $this->configureImage($panelelement, ["SwitchID"=>$destid]);
                foreach($this->hwdata->textInstance($instanceid) as $textInstance) {
                    $panelelement->DispLabelText=str_replace("\n", " ", $textInstance["Text"]);
                    switch ($textInstance["TextStyleID"]) {
                        
                        case 4:
                            $panelelement->DispLabelColour="Dark Red";
                            break;
                            
                        case 5:
                            $panelelement->DispLabelColour="Dark Blue";
                            break;
                        
                        case 6:
                            $panelelement->DispLabelColour="Dark Green";
                            break;
                        
                        default:
                            $panelelement->DispLabelColour="Black";
                            
                    }
                    break; // Only the one?
                }
                if (!isset($panelelement->PositionX)) $panelelement->PositionX=0;
                $panelelement->DispLabelFontSize=12;
            }
        }
    }

    public function configureKeyImages(array $keyImageSets, array $keyboards) : void {
        foreach($keyboards as $keyboardid=>$keyboard) {
            if (isset($keyboard["KeyGen_DisplayPageID"])) {
                $panel=$this->getPanel($keyboard["KeyGen_DisplayPageID"]);
                if ($panel!==NULL) {
                    foreach($this->hwdata->keyActions() as $keyaction) {
                        if ($keyaction["SourceKeyboardID"]==$keyboardid) {
                            $manual=$this->getManual($keyaction["DestKeyboardID"]);
                            $panelelement=$panel->GUIElement($manual);
                            $keyImageset=$keyImageSets[$keyboard["KeyGen_KeyImageSetID"]];
                            $keyImageset["ManualID"]=$keyaction["DestKeyboardID"];
                            $keyImageset["PositionX"]=$keyboard["KeyGen_DispKeyboardLeftXPos"];
                            $keyImageset["PositionY"]=$keyboard["KeyGen_DispKeyboardTopYPos"];
                            $this->configureKeyImage($panelelement, $keyImageset);
                            $panelelement->ImageOn_FirstC=$panelelement->ImageOn_C;
                            $panelelement->ImageOff_FirstC=$panelelement->ImageOff_C;
                            $manual->Displayed="N";
                            unset($manual->DisplayKeys);
                        }
                    }
                }
            }
        }
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        foreach($data["Panels"] as $panelid=>$instanceid) {
            $data["InstanceID"]=$instanceid;
            $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
            $this->configureEnclosureImage($panelelement, $data);
            $panelelement->DispLabelText="";
        }
    }

    public function configureImage(\GOClasses\GOObject $object, array $data, int $layout=0) : void {
        parent::configureImage($object, $data, $layout);
        $imagedata=$this->getImageData($data, $layout);
        $object->MouseRectWidth=$imagedata["ImageWidthPixels"];
        $object->MouseRectHeight=$imagedata["ImageHeightPixels"];
        //unset($object->MouseRadius);
    }
    
    private function treeWalk($root, $dir="", &$results=[]) {
        $files=scandir("$root$dir");
        foreach ($files as $key => $value) {
            if (!is_dir("$root$dir/$value")) {
                $results[strtolower("$dir/$value")] = "$dir/$value";
            } else if ($value != "." && $value != "..") {
                $this->treeWalk($root, ltrim("$dir/$value", "/"), $results);
            }
        }
        return $results;
    }

    protected function correctFileName(string $filename): string {
        static $files=[];
        if (sizeof($files)==0)
            $files=$this->treeWalk(getenv("HOME") . self::ROOT);
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        $rankdata=$this->patchRanks[$rankid=$hwdata["RankID"]];
        
        if ($rankdata["Noise"]=="Ambient") {
            $stop=$this->getStop($rankdata["StopIDs"][0]);
            if ($stop!==NULL) {
                $ambience=$stop->Ambience();
                if ($isattack) {
                    $this->configureAttack($hwdata, $ambience);
                    $ambience->LoadRelease="Y";
                }
                else {
                    $this->configureRelease($hwdata, $ambience);
                    $ambience->LoadRelease="N";
                }
                return $ambience;
            }
        }
        else {
            $midikey=$hwdata["Pitch_NormalMIDINoteNumber"];
            foreach($this->hwdata->rankStop($rankid) as $stopid=>$rsdata) {
                if ($rsdata["MIDINoteNumIncrementFromDivisionToRank"]==$midikey) {
                    $stop=NULL;
                    $stopdata=$this->hwdata->stop($stopid, FALSE);
                    if ($stopdata) {
                        $stop=$this->getSwitchNoise(($isattack ? 1 : -1) * $stopdata["ControllingSwitchID"]);
                        if ($stop) {
                            $noise=$stop->Noise();
                            if ($noise->AttackCount<1) {
                                $this->configureAttack($hwdata, $noise);
                            }
                            return $noise;
                        }
                        return NULL;
                    }
                }
            }
        }
        return NULL;
    }
    
    private function MIDIFraction(\GOClasses\Pipe $pipe) : void {
        static $pitchdata=[];
        if (sizeof($pitchdata)==0) {
            $fp=fopen(__DIR__ . "/Cheltenham.csv", "r");
            fgets($fp); // header line
            while (!feof($fp)) {
                $line=trim(fgets($fp));
                $explode=explode("\t",$line);
                // error_log(print_r($explode,1));
                if (array_key_exists(16, $explode) && is_numeric($explode[16])) {
                    $file="OrganInstallationPackages" . $explode[0] . "/" . $explode[4];
                    $correction=-floatval($explode[16]);
                    $pitchdata[$file]=$correction;
                    // error_log("pitchdata[$file]=$correction");
                }
            }
        }
        
        $correction=$pitchdata[$pipe->Attack];
        $offset=floor($correction/100);
        $fraction=$correction-($offset*100);
        $pipe->MIDIKeyOverride+=$offset;
        $pipe->MIDIPitchFraction=$fraction;
        //error_log($pipe);        
    }
    
    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $pipe=parent::processSample($hwdata, $isattack);
        $rankdata=$this->hwdata->rank($hwdata["RankID"], FALSE);
        if ($isattack 
                && $pipe 
                && !isset($rankdata["Noise"])
                && !isset($pipe->MIDIKeyOverride) 
                && $pipe->AttackCount==0) {
            if ($hwdata["RankID"]==21) {$pipe->HarmonicNumber=36;}
            $pipe->MIDIKeyOverride=$this->sampleMidiKey(["SampleFilename"=>$hwdata["SampleFilename"]])
                    + intval(12*log($pipe->HarmonicNumber,2)) - 36;
            $this->MIDIFraction($pipe);
            //echo $pipe->MIDIKeyOverride, "\t", $hwdata["SampleFilename"], "\n";
        }
        return $pipe;
    }
    
    /**
     * Run the import
     */
    public static function Cheltenham(Cheltenham $hwi, string $target) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=56;
        \GOClasses\Manual::$pedals=30;

        $hwi->root=self::ROOT;
        $hwi->positions=[1=>""];
        $hwi->import();
        $hwi->addVirtualKeyboards(3, [1,2,3], [1,2,3]);
        
        unset($hwi->getOrgan()->InfoFilename);
        echo $hwi->getOrgan()->ChurchName, "\n";
        
        $salcional=$hwi->getStop(2203); // sic
        if ($salcional) {unset($salcional->Rank001PipeCount);}
        
        $hwi->saveODF($target, self::COMMENTS);
    }   
}

class CheltenhamDemo extends Cheltenham {
    const ODF="St. Matthew Cheltenham Demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Cheltenham demo.1.3.organ";
    
    protected $usedstops=[1,2,3,4,-1,-2,-3,-4,2002,2004,2005,2103,2105,2105,2201,2205,2209,2302,2303,2601,1720];

    static function Demo() {
        self::Cheltenham(new CheltenhamDemo(self::SOURCE), self::TARGET);
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        if (isset($data["StopID"]) &&
                !in_array($data["StopID"], $this->usedstops)) {
            return;
        }
        parent::configurePanelSwitchImages($switch, $data);
    }
    
}

class CheltenhamFull extends Cheltenham {
    const ODF="St. Matthew Cheltenham.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Cheltenham full.1.3.organ";

    static function Full () {
        self::Cheltenham(new CheltenhamFull(self::SOURCE), self::TARGET);
    }
    
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\BA\ErrorHandler");

CheltenhamFull::Full();
CheltenhamDemo::Demo();