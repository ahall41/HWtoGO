<?php 

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\AV;
require_once(__DIR__ . "/AVOrgan.php");

/**
 * Import Szikszo Organ of Szikszó (Hungary) from Frankfurt to GrandOrgue
 * 
 * @author andrew
 */
class Szikszo extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/Szikszo/";
    const ODF="Klais v2 wet_extend demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Klais Organ of Szikszó (Hungary) from Frankfurt\n"
            . "https://hauptwerk-augustine.info/Klais.php\n"
            . "\n";
    const TARGET=self::ROOT . "Klais v2 wet_extend demo.1.0.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Original"],
        2=>["SetID"=>2],
        3=>["SetID"=>3, "Name"=>"Extended"],
    ];
    
    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2,+3,+4,+5]],
        94=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2,-3,-4,-5]],
        95=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2691]],
        96=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        97=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
    ];

    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"DivisionKeyAction_04 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +5=>["Name"=>"DivisionKeyAction_05 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -4=>["StopID"=>-4, "DivisionID"=>4, "Name"=>"DivisionKeyAction_04 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -5=>["StopID"=>-5, "DivisionID"=>5, "Name"=>"DivisionKeyAction_05 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
      2691=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700]  // Bell
    ];


    protected $patchEnclosures=[
        220=>[                     "Name"=>"Pos",   "GroupIDs"=>[301], "InstanceID"=>63],
        230=>[                     "Name"=>"Swell", "GroupIDs"=>[401], "InstanceID"=>65],
        240=>[                     "Name"=>"Solo",  "GroupIDs"=>[501], "InstanceID"=>67],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401]],
        1740=>["Type"=>"Synth", "GroupIDs"=>[501]]
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        $panelelement=$this->getPanel(2)->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->DispLabelText="";
    }

    // cf Ada.php
    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        static $reeds=[2010, 2011, 2012, 2123, 2232, 2341, 2442, 2444, 2445, 2446, 2447, 2450, 2451];
        
        $switchid=$data["SwitchID"];
        $slinkid=$this->hwdata->switchLink($switchid)["D"][0]["SourceSwitchID"];
        foreach($this->hwdata->switchLink($slinkid)["S"] as $link) {
            $switchdata=$this->hwdata->switch($destid=$link["DestSwitchID"]);
            if (isset($switchdata["Disp_ImageSetInstanceID"])) {
                $instancedata=$this->hwdata->imageSetInstance($instanceid=$switchdata["Disp_ImageSetInstanceID"]);
                $panel=$this->getPanel($instancedata["DisplayPageID"]);
                $panelelement=$panel->GUIElement($switch);
                $this->configureImage($panelelement, ["SwitchID"=>$destid]);
                unset($panelelement->MouseRectTop);
                unset($panelelement->MouseRectHeight);
                foreach($this->hwdata->textInstance($instanceid) as $textInstance) {
                    $panelelement->DispLabelText=str_replace("\n", " ", $textInstance["Text"]);
                    break; // Only the one?
                }
                if (!isset($panelelement->PositionX))
                    $panelelement->PositionX=0;
                $style=$this->hwdata->textStyle($textInstance["TextStyleID"]);
                if (isset($style["Font_SizePixels"]))
                    $panelelement->DispLabelFontSize=$style["Font_SizePixels"];

                switch($style["Name"]) {
                    case "CustCS_Button051 Drawstops";
                        $panelelement->DispLabelColour="Dark Blue";
                        break;

                    case "CustCS_Button050 Drawstops";
                        $reed=isset($data["StopID"]) && in_array($data["StopID"], $reeds);
                        $panelelement->DispLabelColour=($reed ? "Dark Red" : "Black");
                        break;

                    case "CustCS_Button053 Drawstops";
                    case "CustCS_Button052 Drawstops";
                        $panelelement->DispLabelColour="White";
                        break;
                }
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
                            $manual->Displayed="N";
                        }
                    }
                    if ($keyboard["KeyGen_DisplayPageID"]==1)
                        $keyImageset["PositionY"]-=100;
                }
            }
        }
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
        
        $filename=str_replace(
                ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-"],
                ["OrganInstallationPackages/001761/Images/Pedals/",
                 "OrganInstallationPackages/001761/Images/Keys/"],   
                $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    /**
     * Run the import
     */
    public static function Szikszo(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new Szikszo(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("sur new", $target, $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            foreach($hwi->getStops() as $id=>$stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
                $stop->Rank001FirstAccessibleKeyNumber=13;
                $stop->Rank002FirstAccessibleKeyNumber=13;
                $stop->Rank003FirstAccessibleKeyNumber=13;
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Szikszo(
                    [1=>"Far"], // [1=>"Near", 2=>"Far", 3=>"Rear"],
                    "surround");
        }
    }   
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Szikszo::Szikszo();