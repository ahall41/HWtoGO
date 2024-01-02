<?php 

/**
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 * @todo: Combinations
 *        Reversible Pistons 
 * 
 */

namespace Organs\BA;
require_once(__DIR__ . "/BAOrgan.php");

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
        1720=>["Type"=>"Synth", "GroupIDs"=>[301], "Period"=>200, "AmpModDepth"=>20],
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
    
    public function createManuals(array $keyboards): void {
        foreach([1,4,2,3] as $id) {
            parent::createManual($keyboards[$id]);
        }
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (isset($hwdata["Noise"]) && $hwdata["Noise"]=="Ambient")
            return NULL;
        else
            return parent::createRank($hwdata, $keynoise);
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

    private function readPitchData(string $file) : array {
        $fp=fopen($file, "r");
        $result=[];
        while (!feof($fp)) {
            $line=trim(fgets($fp));
            if (substr($line,0,1)=="/") {
                $folder=substr($line,1);
            }
            elseif (substr($line,-4)==".wav") {
                $file=$line;
            }
            elseif (substr($line,0,13)=="MIDIKeyNumber") {
                $split=explode("=",$line);
                $midi=intval(trim($split[1]));
                $result[$folder][$file]["K"]=$midi;
            }
            elseif (substr($line,0,17)=="MIDIPitchFraction") {
                $split=explode("=",$line);
                $fraction=floatval(trim($split[1]));
                $result[$folder][$file]["F"]=$fraction;
            }
        }
        fclose($fp);
        return $result;
    }
    
    protected function setPitch(\GOClasses\Pipe $pipe) : void {
        static $pitches=NULL;
        if (empty($pitches)) {$pitches=$this->readPitchData(__DIR__ . "/CheltenhamPitch.txt");}
        
        if (!isset($pipe->MIDIKeyNumber) && $pipe->AttackCount==0) {
            $base=basename($pipe->Attack);
            $dir=dirname($pipe->Attack);
            
            if (isset($pitches[$dir][$base]["K"])) {
                $pipe->MIDIKeyOverride=$pitches[$dir][$base]["K"];
            }
            
            if (isset($pitches[$dir][$base]["F"])) {
                $pipe->MIDIPitchFraction=$pitches[$dir][$base]["F"];
            }
        }
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
    
    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $pipe=parent::processSample($hwdata, $isattack);
        // if ($isattack && $pipe) {$this->setPitch($pipe);}
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
    const TARGET=self::ROOT . "Cheltenham demo.1.0.organ";
    
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
    const TARGET=self::ROOT . "Cheltenham full.1.0.organ";

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