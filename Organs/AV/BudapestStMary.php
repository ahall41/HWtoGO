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
 * Import The Organ of the Mary the Virgin Church in Budapest to GrandOrgue
 * 
 * @author andrew
 */
class BudapestStMary extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/BudapestStMary/";
    const ODF="Mary the Virgin Budapest surround_demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "The Organ of the Mary the Virgin Church in Budapest\n"
            . "https://hauptwerk-augustine.info/BudapestStMary.php\n"
            . "\n"
            . "Version 1.1 Add III and IV to mixer\n"
            . "\n";
    const TARGET=self::ROOT . "Mary the Virgin Budapest %s_demo.1.1.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
        2=>["SetID"=>2],
        3=>["SetID"=>3],
        4=>["SetID"=>4],
    ];
    
    protected $patchEnclosures=[
        230=>[                     "Name"=>"III",   "GroupIDs"=>[401,402,403], "InstanceID"=>76],
        240=>[                     "Name"=>"IV",    "GroupIDs"=>[501,502,503], "InstanceID"=>77],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[101,201,301,401,501], "InstanceID"=>85132, "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[102,202,302,402,502], "InstanceID"=>85133, "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303,403,503], "InstanceID"=>85134, "AmpMinimumLevel"=>0],
         17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700],         "InstanceID"=>85135, "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401,402,403]],
        1740=>["Type"=>"Synth", "GroupIDs"=>[501,502,503]]
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
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    protected $patchRanks=[
        88=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        89=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        90=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
        91=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        92=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
        94=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2]],
        95=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+3]],
        96=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-3]],
        97=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+4]],
        98=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-4]],
        99=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+5]],
       100=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-5]],
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        $panelelement=$this->getPanel(1)->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->DispLabelText="";
    }

    // cf Ada.php
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
                    break; // Only the one?
                }
                if (!isset($panelelement->PositionX))
                    $panelelement->PositionX=0;
                $style=$this->hwdata->textStyle($textInstance["TextStyleID"]);
                if (isset($style["Font_SizePixels"]))
                    $panelelement->DispLabelFontSize=$style["Font_SizePixels"];

                switch($style["Name"]) {
                    case "CustCS_stop_red":
                    case "CustCS_stopsmall_reed":
                        $panelelement->DispLabelColour="Dark Red";
                        break;
                    
                    case "CustCS_stop_coup":
                    case "CustCS_stopsmall_coup2":
                    case "CustCS_stopsmall_coup3":
                        $panelelement->DispLabelColour="Dark Blue";
                        break;

                    default:
                        $panelelement->DispLabelColour="Black";
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
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    /**
     * Run the import
     */
    public static function BudapestStMary(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=51;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new BudapestStMary(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("sur new", $target, $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            foreach($hwi->getStops() as $id=>$stop) {
                if ($id==2131) {
                    $stop->Rank001FirstAccessibleKeyNumber=13;
                    $stop->Rank002FirstAccessibleKeyNumber=13;
                    $stop->Rank003FirstAccessibleKeyNumber=13;
                }
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
                unset($stop->Rank004PipeCount);
                unset($stop->Rank005PipeCount);
                unset($stop->Rank006PipeCount);
            }
            foreach([31,131,231] as $rankid)
                $hwi->getRank($rankid)->Percussive="Y";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::BudapestStMary(
                    [1=>"Near", 2=>"Far", 3=>"Rear"],
                    "surround");
        }
    }   
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
BudapestStMary::BudapestStMary();