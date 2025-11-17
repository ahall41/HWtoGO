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

namespace Organs\CP;
require_once(__DIR__ . "/CPOrgan.php");

/**
 * Import Father Willis Organ of St. Matthew, ClarendonUMC to GrandOrgue
 * 
 * @author andrew
 */
class ClarendonUMC extends CPOrgan {
    
    const ROOT="/GrandOrgue/Organs/CP/ClarendonUMC/";
    const COMMENTS=
              "Schlicker, 1967, Clarendon United Methodist Church, Arlington, MD\n"
            . "https://coralpipesorgan.wixsite.com/coral-pipes/clarendon-umc\n"
            . "\n";

    protected $patchDisplayPages=[
        
        1=>[ // Console
            0=>["SetID"=>1000]
           ],
        2=>[ // Center
            0=>["SetID"=>1001]
           ],
        3=>[
            0=>["Name"=>"Landscape", "Group"=>"Left", "SetID"=>1002], 
            1=>["Name"=>"Portrait", "Group"=>"Left", "SetID"=>1003], 
           ],
        4=>[
            0=>["Name"=>"Landscape", "Group"=>"Right", "SetID"=>1004], 
            1=>["Name"=>"Portrait", "Group"=>"Right", "SetID"=>1005], 
           ],
        5=>[
            0=>["Name"=>"Landscape", "Group"=>"Simple", "SetID"=>1006], 
            1=>["Name"=>"Portrait", "Group"=>"Simple", "SetID"=>1007], 
           ],
        6=>"DELETE",
        9=>[ // Pistons
            0=>["SetID"=>1013]
           ],
        10=>[ // Blower
            0=>["SetID"=>1014]
           ],
        15=>[ 
            0=>["Name"=>"Landscape", "Group"=>"Info", "SetID"=>1019], 
            1=>["Name"=>"Portrait", "Group"=>"Info", "SetID"=>1020], 
           ],
    ];
    
    protected $patchEnclosures=[
        220=>["GroupIDs"=>[4], "AmpMinimumLevel"=>30],
    ];
    
    protected $patchTremulants=[
        1710=>"DELETE",
        1720=>["Type"=>"Synth", "GroupIDs"=>[4], "Period"=>250, "AmpModDepth"=>18],
        1730=>["Type"=>"Synth", "GroupIDs"=>[3], "Period"=>220, "AmpModDepth"=>15],
        1740=>"DELETE"
    ];
    
    protected $patchStops=[
    /*  +1=>["Name"=>"Pedal On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
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
      1720=>["DivisionID"=>4, "Ambient"=>TRUE, "GroupID"=>700], // Tremulant
      2601=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower */
      2402=>["Ambient"=>TRUE, "DivisionID"=>1, "GroupID"=>5]
    ];

    // Inspection of Ranks object
    protected $patchRanks=[
    /*  100=>["Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2601]],
        101=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
        102=>["Noise"=>"StopOff", "GroupID"=>700, "StopIDs"=>[]],
        103=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+2]],
        104=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-2]],
        105=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+1]],
        106=>["Noise"=>"KeyOff",  "GroupID"=>7400, "StopIDs"=>[-1]],
        107=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+3]],
        108=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-3]],
        109=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+4]],
        110=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-4]], */
    ];
    
//    protected $combinations=[ // Don't show on panel 1 - its a 1x1 .png !!!
//        ["SwitchIDs"=>[/* 1=>10068, */ 2=>10069], "ManualID"=>2, "SetterID"=>0],
//        ["SwitchIDs"=>[/* 1=>10070, */ 2=>10071], "ManualID"=>2, "SetterID"=>1],
//        ["SwitchIDs"=>[/* 1=>10072, */ 2=>10073], "ManualID"=>2, "SetterID"=>2],
//        ["SwitchIDs"=>[/* 1=>10074, */ 2=>10075], "ManualID"=>2, "SetterID"=>3],
//        ["SwitchIDs"=>[/* 1=>10076, */ 2=>10077], "ManualID"=>3, "SetterID"=>0],
//        ["SwitchIDs"=>[/* 1=>10078, */ 2=>10079], "ManualID"=>3, "SetterID"=>1],
//        ["SwitchIDs"=>[/* 1=>10080, */ 2=>10081], "ManualID"=>3, "SetterID"=>2],
//    ];
    
    /** protected $pistons=[
        ["SwitchIDs"=>[1=>10230, 2=>10231], "SwitchID"=>10207, "Name"=>"Sw to Gt"],
        ["SwitchIDs"=>[1=>10233, 2=>10234], "SwitchID"=>10103, "Name"=>"Ped Reed 16"],
        ["SwitchIDs"=>[1=>10236, 2=>10237], "SwitchID"=>10216, "Name"=>"Gt to Ped"],
    ]; */
    
    public function createManuals(array $keyboards): void {
        foreach([1,2,3,4] as $id) {
            $manual=parent::createManual($keyboards[$id]);
            unset($manual->PositionX);
            unset($manual->PositionY);
            $manual->Displayed="N";
            $manual->NumberOfAccessibleKeys=$manual->NumberOfLogicalKeys;
            /* $x=999999;
            $y=999999;
            foreach($this->hwdata->keyboardKeys() as $keyboardKey) {
                if ($keyboardKey["KeyboardID"]==$id) {
                    $imagedata=$this->getImageData(["SwitchID"=>$keyboardKey["SwitchID"]]);
                    $x=min($x, $imagedata["ImageWidthPixels"] + $imagedata["PositionX"]);
                    $y=min($y, $imagedata["ImageHeightPixels"] + $imagedata["PositionY"]);
                }
            }
            $manual->PositionX=$x;
            $manual->PositionY=$y; */
        }
        $manual=parent::createManual($keyboards[5]);
        unset($manual->PositionX);
        unset($manual->PositionY);
        $manual->Displayed="N";
    }
    
    /* public function createStops(array $stopsdata): void {
        return;
    }

    public function createRanks(array $ranksdata): void {
        return;
    }
    
    public function createCouplers(array $keyactions) : void {
        return;
    } */
    
    /* public function createEnclosures(array $enclosures) : void {        
        return;
    } */
    
    public function configureKeyboardKeys(array $keyboardKeys) : void {
        foreach($this->GetPanels() as $panel) {
            $panel->HasPedals="N";
        }
        $panel=\Import\Configure::createPanel(["PageID"=>0, "Name"=>"Keyboards"]);
        $panel->HasPedals="Y";
        foreach($this->getManuals() as $id=>$manual) {
            if ($id<5) {
                $panel->GUIElement($manual);
            }
        }
        // echo $panel;
        return;
        /* // Reorder source data
        $index=[];
        foreach($keyboardKeys as $keyboardKey) {
            $switch=$this->hwdata->switch($keyboardKey["SwitchID"]);
            if (isset($switch["Disp_ImageSetInstanceID"])
                    && isset($keyboardKey["NormalMIDINoteNumber"]))
                $index[$keyboardKey["KeyboardID"]][$keyboardKey["NormalMIDINoteNumber"]]
                        =$keyboardKey["SwitchID"];
        }

        $manuals=$this->getManuals();
        foreach($manuals as $manid=>$manual) {
            if (isset($index[$manid])) {
                $midikeys=$index[$manid];
                asort($midikeys);
                $first=TRUE;
                foreach($midikeys as $midikey=>$switchid) {
                    $key=$manual->Key();
                    $switch=$this->hwdata->switch($switchid);
                    $imagedata=$this->getImageData(["SwitchID"=>$switchid]);
                    $disengaged=$imagedata["Images"][$switch["Disp_ImageSetIndexEngaged"]];
                    $engaged=$imagedata["Images"][$switch["Disp_ImageSetIndexDisengaged"]];
                    if ($first) {
                        $manual->PositionX=$imagedata["PositionX"];
                        $manual->PositionY=$imagedata["PositionY"];
                        $manual->FirstAccessibleKeyMIDINoteNumber=$midikey;
                        $first=FALSE;
                    }
                    $manual->KeyWidth($imagedata["PositionX"]);
                    $manual->KeyOffsetY($imagedata["PositionY"]);
                    $manual->set("Display${key}", $midikey);
                    $manual->set("${key}ImageOn", $engaged);
                    $manual->set("${key}ImageOff", $disengaged);
                    if ($manual->DisplayKeys>3) {break;}
                }
                $manual->NumberOfAccessibleKeys=$manual->DisplayKeys;
                //echo $manual;
                //exit();
            }
        } */
    }

    public function xxconfigureKeyboardKey(\GOClasses\Manual $manual, int $switchid, int $midikey) : void {
        $manual->DisplayKeys++;
                $element=
        $key="Key" . $manual->int2str($manual->DisplayKeys);
        if ($manual->FirstAccessibleKeyMIDINoteNumber>$midikey)
            $manual->FirstAccessibleKeyMIDINoteNumber=$midikey;
        $switchdata=$this->hwdata->switch($switchid);
        $imagedata=$this->getImageData(["SwitchID"=>$switchid]);
        $engaged=$imagedata["Images"][$switchdata["Disp_ImageSetIndexEngaged"]];
        $disengaged=$imagedata["Images"][$switchdata["Disp_ImageSetIndexDisengaged"]];
        if (!isset($manual->PositionX)) $manual->PositionX=$imagedata["PositionX"];
        if (!isset($manual->PositionY)) $manual->PositionY=$imagedata["PositionY"];
        //print_r($switchdata);
        //print_r($imagedata);/
        $manual->KeyWidth($imagedata["ImageWidthPixels"]);
        $manual->set("Display${
                $element=key}", $midikey);
        $manual->set("${key}ImageOn", $engaged);
        $manual->set("${key}ImageOff", $disengaged);
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
        // $this->createCombinations();
        // $this->createPistons();
        foreach($this->getStops() as $stopid=>$stop) {
            switch ($stopid) {
                case 2305: // Swl: Viole Celeste 8 (TC)
                case 2311: // Swl: Sesquialtera II
                    $stop->NumberOfAccessiblePipes=$stop->Rank001PipeCount=49;
            }
        }
    }
    
    /* public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (isset($hwdata["Noise"]) && $hwdata["Noise"]=="Ambient")
            return NULL;
        else
            return parent::createRank($hwdata, $keynoise);
    } */
  
    /* private function createCombinations() : void {
        foreach ($this->combinations as $combinationdata) {
            foreach($combinationdata["SwitchIDs"] as $panelid=>$switchid) {
                $panel=$this->getPanel($panelid);
                $pe=$panel->Element();
                $pe->Type=sprintf("Setter%03dDivisional%03d",$combinationdata["ManualID"],$combinationdata["SetterID"]);
                $this->configurePanelSwitchImage($pe, ["SwitchID"=>$switchid]);
            }
        }
    } */

    /* private function createPistons() : void {
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
    } */

    /* public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
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
    } */

    /* public function configureKeyImages(array $keyImageSets, array $keyboards) : void {
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
    } */

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        $panel=$this->getPanel(0);
        $panel->GUIElement($enclosure);
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

    /* public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
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
    } */
    
    
    /* public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
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
            $this->MIDIPraat($pipe);
            //echo $pipe->MIDIKeyOverride, "\t", $hwdata["SampleFilename"], "\n";
        }
        return $pipe;
    } */
    
    /**
     * Run the import
     */
    public static function ClarendonUMC(ClarendonUMC $hwi, string $target) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;

        $hwi->root=self::ROOT;
        $hwi->import();
        
        unset($hwi->getOrgan()->InfoFilename);
        echo $hwi->getOrgan()->ChurchName, "\n";
        
        $hwi->saveODF($target, self::COMMENTS);
    }   
}

class ClarendonUMCDemo extends ClarendonUMC {
    const ODF="Clarendon United Methodist Church (Demo).Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Clarendon United Methodist Church (Demo).1.0.organ";
    
    // protected $usedstops=[1,2,3,4,-1,-2,-3,-4,2002,2004,2005,2103,2105,2105,2201,2205,2209,2302,2303,2601,1720];

    static function Demo() {
        self::ClarendonUMC(new ClarendonUMCDemo(self::SOURCE), self::TARGET);
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        $condSwitchID=isset($data["ConditionSwitchID"]) ? $data["ConditionSwitchID"] : 0;
        switch($condSwitchID) {
            case 10298: // Antiphonal 
            case 10301:
            case 10304:
            case 10207:
                return;
        }
        parent::configurePanelSwitchImages($switch, $data);
    }
    
}

/* class ClarendonUMCFull extends ClarendonUMC {
    const ODF="St. Matthew ClarendonUMC.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "ClarendonUMC full.1.4.organ";

    static function Full () {
        self::ClarendonUMC(new ClarendonUMCFull(self::SOURCE), self::TARGET);
    }
    
} */

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\CP\ErrorHandler");

// ClarendonUMCFull::Full();
ClarendonUMCDemo::Demo();