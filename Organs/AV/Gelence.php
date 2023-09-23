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
 * Import Baumgartner organ from Gelence (Transylvania) to GrandOrgue
 * 
 * @author andrew
 */
class Gelence extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Gelence/";
    const ODF="Gelence extended.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Baumgartner organ from Gelence (Transylvania)\n"
            . "https://hauptwerk-augustine.info/Gelence.php\n"
            . "\n";
    const TARGET=self::ROOT . "Gelence extended.1.0.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
        2=>["SetID"=>2],
    ];
    
    protected $patchEnclosures=[
         11=>["EnclosureID"=>"11", "Name"=>"Front",       "GroupIDs"=>[101,201,301], "InstanceID"=>85132, "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Rear (orig)", "GroupIDs"=>[102,202,302], "InstanceID"=>85133, "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"12", "Name"=>"Rear (echo)", "GroupIDs"=>[103,203,303], "InstanceID"=>85134, "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302]],
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700],  // Blower
      2696=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700],  // Tympani
      2697=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700],  // Tympani1
      2698=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700]   // Tympani2
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        95=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1,+2,+3]],
        96=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2696]],
        97=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2697]],
        98=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2698]]
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
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
                if ($panelelement->MouseRectWidth==70)
                    $panelelement->MouseRectWidth=67; // !
            }
        }
    }
    
    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        foreach([2] as $panelid) {
            $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
            $this->configureEnclosureImage($panelelement, $data);
            $data["InstanceID"]++;
            $panelelement->DispLabelText="";
            if ($data["EnclosureID"]<100) break;
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
        
        $filename=str_replace(
                ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-"],
                ["Pedals/KeyImageSet008-",
                 "OrganInstallationPackages/002204/Images/Keys/"],   
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

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        if ($this->sampleMidiKey($hwdata)>87) return NULL;
        return parent::processSample($hwdata, $isattack);
    }
    /**
     * Run the import
     */
    public static function Gelence(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=52;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new Gelence(self::SOURCE);
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
                if ( ($id > 2120 && $id<2200) || ($id > 2120 && $id<2200)) {
                    $stop->Rank001FirstAccessibleKeyNumber=25;
                    $stop->Rank002FirstAccessibleKeyNumber=25;
                    $stop->Rank003FirstAccessibleKeyNumber=25;                }
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Gelence(
                    [1=>"Front", 2=>"Rear (orig)", 3=>"Rear (echo)"],
                    "surround");
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Gelence::Gelence();