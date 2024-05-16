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
 * Import Baumgartner organ from Stahlhuth (Transylvania) to GrandOrgue
 * 
 * @author andrew
 */
class Stahlhuth extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Stahlhuth/";
    const ODF="Stahlhuth_positive_ext_four_channels.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Stahlhuth positive organ\n"
            . "https://hauptwerk-augustine.info/Stahlhuth_positive.php\n"
            . "\n";
    const TARGET=self::ROOT . "Stahlhuth_positive_ext_four_channels.1.1.organ";

    // protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchEnclosures=[
         11=>["EnclosureID"=>"11", "Name"=>"Dry", "GroupIDs"=>[201], "InstanceID"=>85132, "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Wet", "GroupIDs"=>[202], "InstanceID"=>85133, "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202]],
    ];
    
    protected $patchStops=[
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>2, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700],  // Blower
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
    ];
    
    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $organ=parent::createOrgan($hwdata);
        $organ->HasPedals="N";
        return $organ;
    }

    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        return;
        unset($data["SwitchID"]);
        foreach([1] as $panelid) {
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
                    $panelelement->DispLabelColour="Black";
                }
            }
        }
    }

    public function createSwitchNoise(string $type, array $switchdata): void {
        $switchdata["DivisionID"]=2;
        parent::createSwitchNoise($type, $switchdata);
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
        
        /* $filename=str_replace(
                ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-"],
                ["Pedals/KeyImageSet008-",
                 "OrganInstallationPackages/002204/Images/Keys/"],   
                $filename); */
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
    public static function Stahlhuth(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=52;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new Stahlhuth(self::SOURCE);
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
                if (in_array($id,[2102,2104,2106,2108,2110])) {
                    $stop->Rank001FirstAccessibleKeyNumber=26;
                    $stop->Rank002FirstAccessibleKeyNumber=26;
                }
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Stahlhuth(
                    [1=>"Dry", 2=>"Wet"],
                    "surround");
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Stahlhuth::Stahlhuth();