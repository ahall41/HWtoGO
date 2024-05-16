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
 * Import Organ of Heart of Jesus Church from Zenta (Serbia-Vojvodina) to GrandOrgue
 * 
 * @author andrew
 */
class Zenta extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Zenta/";
    const ODF="Zenta surround_demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Organ of Heart of Jesus Church from Zenta (Serbia-Vojvodina)\n"
            . "https://hauptwerk-augustine.info/Zenta.php\n"
            . "\n"
            . "1.1:  Adjusted keyboard layouts\n"
            . "\n";
    const TARGET=self::ROOT . "Zenta surround_demo.1.1.organ";

    // protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>27],
        2=>["SetID"=>28],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"II",    "GroupIDs"=>[301,302],         "InstanceIDs"=>[1=>128]],
        230=>[                     "Name"=>"III",   "GroupIDs"=>[401,402],         "InstanceIDs"=>[1=>130]],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[101,201,301,401], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[102,202,302,402], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>0],
         17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700],             "InstanceIDs"=>[1=>85135], "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401,402]]
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -4=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
        83=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
        84=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2]],
        85=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+3]],
        86=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-3]],
        87=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+4]],
        88=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-4]],
        96=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        97=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function createPanels($hwgeneral, array $hwpages): void {
        foreach([2,1] as $pageid) { // Console first!
            $hwpage=$hwpages[$pageid];
            $panel=$this->createPanel(array_merge($hwgeneral, $hwpage));
            $this->configurePanelImage($panel, $hwpage);
        }
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        $switchid=$data["SwitchID"];
        $slinkid=$this->hwdata->switchLink($switchid)["D"][0]["SourceSwitchID"];
        foreach($this->hwdata->switchLink($slinkid)["S"] as $link) {
            $switchdata=$this->hwdata->switch($destid=$link["DestSwitchID"]);
            if (isset($switchdata["Disp_ImageSetInstanceID"])) {
                $instancedata=$this->hwdata->imageSetInstance($instanceid=$switchdata["Disp_ImageSetInstanceID"]);
                $panel=$this->getPanel($instancedata["DisplayPageID"], FALSE);
                if ($panel!==NULL) {
                    $panelelement=$panel->GUIElement($switch);
                    $this->configureImage($panelelement, ["SwitchID"=>$destid]);
                    $textinstances=$this->hwdata->textInstance($instanceid);
                    if (sizeof($textinstances)>0) {
                        foreach($textinstances as $textInstance) {
                            $panelelement->DispLabelText=str_replace("\n", " ", $textInstance["Text"]);
                            break; // Only the one?
                        }
                        if (!isset($panelelement->PositionX))
                            $panelelement->PositionX=0;
                        $style=$this->hwdata->textStyle($textInstance["TextStyleID"]);
                        if (isset($style["Font_SizePixels"]))
                            $panelelement->DispLabelFontSize=$style["Font_SizePixels"];

                         switch($style["Name"]) {
                             case "CustCS_stops coup":
                                 $panelelement->DispLabelColour="Dark Blue";
                                 break;
                             
                             case "CustCS_stops reed":
                                 $panelelement->DispLabelColour="Dark Red";
                                 break;
                             
                             default:
                                 $panelelement->DispLabelColour="Black";
                         }

                    }
                }
            }
        }
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        foreach($data["InstanceIDs"] as $panelid=>$instanceid) {
            $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
            $data["InstanceID"]=$instanceid;
            $this->configureEnclosureImage($panelelement, $data);
            $panelelement->DispLabelText="";
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
                            $keyImageset["HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural"];
                            $keyImageset["HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF"];
                            $keyImageset["HorizSpacingPixels_LeftOfDASharpFromLeftOfDA"];
                            $keyImageset["HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp"];
                            $keyImageset["HorizSpacingPixels_LeftOfEBFromLeftOfDASharp"];
                            $keyImageset["HorizSpacingPixels_LeftOfAFromLeftOfGSharp"];
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
                ["OrganInstallationPackages/002382/Images/Pedals/",
                 "OrganInstallationPackages/002382/Images/Keys/"],   
                $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    public function processNoise(array $hwdata, $isattack): ?\GOClasses\Noise {
        $type=$this->hwdata->rank($hwdata["RankID"])["Noise"];
        if ($type=="Ambient") {
            $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
            $stopid=$this->hwdata->rank($hwdata["RankID"])["StopIDs"][0];
            if ($isattack)
                $this->configureAttack($hwdata, $this->getStop($stopid)->Ambience());
            else
                $this->configureRelease($hwdata, $this->getStop($stopid)->Ambience());
        }
        else 
            parent::processNoise($hwdata, $isattack);
        return NULL;
    }
    
    /**
     * Run the import
     */
    public static function Zenta(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=58;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new Zenta(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("sur new", $target, $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
            }
            foreach([33,133] as $rankid)
                $hwi->getRank($rankid)->PitchTuning=10;
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
            echo $hwi->getOrgan()->ChurchName, "\n";
        }
        else {
            self::Zenta([1=>"Near", 2=>"Far"], "");
        }
    }   
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Zenta::Zenta();