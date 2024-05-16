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
 * Import Hungarian village organs compositeto GrandOrgue
 * 
 * @author andrew
 */
class Village extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Village/";
    const ODF="Hungarian Village Organs.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Hungarian village organs composite\n"
            . "https://hauptwerk-augustine.info/Hungarian_village.php\n"
            . "\n";
    const TARGET=self::ROOT . "Hungarian Village Organs.1.0.organ";

    // protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>27],
        5=>"DELETE",
        6=>"DELETE",
        7=>"DELETE",
        8=>"DELETE"
    ];
    
    protected $patchEnclosures=[
        210=>["Name"=>"Sachs",    "GroupIDs"=>[201], "InstanceID"=>181],
        220=>["Name"=>"Kolonics", "GroupIDs"=>[301], "InstanceID"=>191],
    ];
    
    protected $patchTremulants=[
        1700=>["Type"=>"Synth", "GroupIDs"=>[101]],
        1710=>["Type"=>"Synth", "GroupIDs"=>[201]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301]],
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700],  // Blower
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+1]],
        94=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+2]],
        95=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+3]],
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
        }
    }
    
    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
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
                $panel=$this->getPanel($keyboard["KeyGen_DisplayPageID"], FALSE);
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
                 "OrganInstallationPackages/001748/Images/Keys/"],   
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
        switch ($rankid=$hwdata["RankID"]) {
            case 1:
            case 2: 
                if ($this->sampleMidiKey($hwdata)>68) return NULL;
                break;
        }
        return parent::processSample($hwdata, $isattack);
    }
    /**
     * Run the import
     */
    public static function Village(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new Village(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("sur new", $target, $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            foreach($hwi->getManuals() as $manual) {
                unset($manual->DisplayKeys);
                unset($manual->PositionX);
                unset($manual->PositionY);
            }
            foreach($hwi->getStops() as $id=>$stop) {
                unset($stop->Rank001PipeCount);
                if ($id==2104) $stop->Rank001FirstAccessibleKeyNumber=1;
            }
            foreach($hwi->getRanks() as $id=>$rank) {
                switch ($id) {
                    case 1:
                        $rank->PitchTuning=100;
                        break;
                    
                    case 10:
                    case 11:
                    case 12:
                    case 13:
                    case 14:
                    case 15:
                        $rank->PitchTuning=-90;
                }
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
            echo $hwi->getOrgan()->ChurchName, "\n";
        }
        else {
            self::Village([1=>""], "");
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Village::Village();