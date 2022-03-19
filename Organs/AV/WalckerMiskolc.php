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
 * Import The Walcker organ of Miskolc Catholic church of Iron factory (from Bad Ems) to GrandOrgue
 * 
 * @author andrew
 */
class WalckerMiskolc extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/WalckerMiskolc/";
    const ODF="Walcker_Miskolc v2 surround_demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Walcker organ of Miskolc Catholic church of Iron factory (from Bad Ems)\n"
            . "https://hauptwerk-augustine.info/WalckerMiskolc.php\n"
            . "\n";
    const TARGET=self::ROOT . "Walcker_Miskolc %s_demo.1.0.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"II",    "GroupIDs"=>[301,302,303], "InstanceID"=>45],
        230=>[                     "Name"=>"III",   "GroupIDs"=>[401,402,403], "InstanceID"=>47],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[102,202,302], "InstanceID"=>85132, "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[101,201,301], "InstanceID"=>85133, "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceID"=>85134, "AmpMinimumLevel"=>0],
         17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700],         "InstanceID"=>85135, "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401,402,403]],
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"DivisionKeyAction_04 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    protected $patchRanks=[
         91=>["RankID"=>91, "Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
         92=>["RankID"=>92, "Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
         93=>["RankID"=>93, "Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[1,2,3,4]],
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

                if ($style["Colour_Blue"]>100) 
                    $panelelement->DispLabelColour="Dark Blue";
                elseif($style["Colour_Red"]>100) 
                    $panelelement->DispLabelColour="Dark Red";
                else
                    $panelelement->DispLabelColour="Black";
                $style=$this->hwdata->textStyle($textInstance["TextStyleID"]);
                if (isset($style["Font_SizePixels"]))
                    $panelelement->DispLabelFontSize=$style["Font_SizePixels"];

                if ($style["Colour_Blue"]>100) 
                    $panelelement->DispLabelColour="Dark Blue";
                elseif($style["Colour_Red"]>100) 
                    $panelelement->DispLabelColour="Dark Red";
                else
                    $panelelement->DispLabelColour="Black";
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
    public static function WalckerMiskolc(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=58;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new WalckerMiskolc(self::SOURCE);
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
                unset($stop->Rank004PipeCount);
                unset($stop->Rank005PipeCount);
                unset($stop->Rank006PipeCount);
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::WalckerMiskolc(
                    [1=>"Far", 2=>"Near", 3=>"Rear"],
                    "surround");
        }
    }   
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
}
set_error_handler("Organs\AV\ErrorHandler");
WalckerMiskolc::WalckerMiskolc();