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
class SzikszoExtended extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV//Szikszo/";
    const SOURCE=self::ROOT . "OrganDefinitions/%s.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "%s 1.2.organ";
    const COMMENTS=
              "Klais Organ of Szikszó (Hungary) from Frankfurt\n"
            . "https://hauptwerk-augustine.info/Klais.php\n"
            . "\n"
            . "1.1 Released with full version\n"
            . "1.2 Added coupler manuals\n"
            . "\n";
    
    private static $targets=[
        //"Klais v2 wet_extend demo"  =>[1=>"Far"],
        //"Klais v2 wet_extend"       =>[1=>"Far"],
        "Klais w2 semidry_extend"   =>[1=>"Near"],
        "Klais v2 surround_extend"  =>[1=>"Far", 2=>"Near"],
    ];
    
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
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        +4=>["Name"=>"DivisionKeyAction_04 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        +5=>["Name"=>"DivisionKeyAction_05 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -4=>["StopID"=>-4, "DivisionID"=>4, "Name"=>"DivisionKeyAction_04 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
        -5=>["StopID"=>-5, "DivisionID"=>5, "Name"=>"DivisionKeyAction_05 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700, "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"], // Blower
      2691=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700, "StoreInGeneral"=>"N", "StoreInDivisional"=>"N"]  // Bell
    ];

    protected $patchEnclosures=[
        220=>[                     "Name"=>"Pos",   "GroupIDs"=>[301,302], "InstanceID"=>63],
        230=>[                     "Name"=>"Swell", "GroupIDs"=>[401,402], "InstanceID"=>65],
        240=>[                     "Name"=>"Solo",  "GroupIDs"=>[501,502], "InstanceID"=>67],
         11=>["EnclosureID"=>"11", "Name"=>"Far",   "GroupIDs"=>[101,201,301,401,501], "InstanceID"=>85134, "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Near",  "GroupIDs"=>[102,202,302,402,502], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
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
        $instance=$this->hwdata->imageSetInstance($data["InstanceID"], TRUE);
        if ($instance!==NULL) {
            $panelid=($data["InstanceID"]<100 ? 2 : 1);
            $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
            $this->configureEnclosureImage($panelelement, $data);
            $panelelement->DispLabelText="";
        }
    }

    // cf Ada.php
    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        static $reeds=[2009, 2010, 2011, 2012, 2123, 2232, 2340, 2341, 2442, 2444, 2445, 2446, 2447, 2450, 2451];
        
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
                            if ($keyboardid>1) $keyImageset["PositionX"]+=8;
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

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        if ((($hwdata["RankID"] % 100) <= 12) 
                && isset($hwdata["NormalMIDINoteNumber"]) 
                && $hwdata["NormalMIDINoteNumber"]>67) return NULL;
        elseif (isset($hwdata["NormalMIDINoteNumber"]) 
                && $hwdata["NormalMIDINoteNumber"]>93) return NULL;
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the import
     */
    public static function SzikszoExtended() {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        
        foreach (self::$targets as $target=>$positions) {
            $hwi=new SzikszoExtended(sprintf(self::SOURCE, $target));
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->addCouplerManuals(3, [1,2,3,4], [1,2,3]);
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            foreach($hwi->getStops() as $id=>$stop) {
                for ($rn=1; $rn<=$stop->NumberOfRanks; $rn++) {
                    $r=$stop->int2str($rn);
                    $stop->unset("Rank{$r}PipeCount");
                    if ($id==2335 // Vox Coelestis
                        || $target=="Klais v2 wet_extend demo")
                            $stop->set("Rank{$r}FirstAccessibleKeyNumber", 13);
                }
            }
            foreach([35,135] as $rankid) { // Vox Coelestis
                $rank=$hwi->getRank($id, FALSE);
                if ($rank!==NULL)
                    $rank->PitchTuning=10;
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
    }   
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
SzikszoExtended::SzikszoExtended();
