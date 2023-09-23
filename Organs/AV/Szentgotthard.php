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
 * Import Baroque Organ from Szentgotthard to GrandOrgue
 * 
 * @author andrew
 */
class Szentgotthard extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV//Szentgotthard/";
    const SOURCE=self::ROOT . "OrganDefinitions/Szentgotthard surround_demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Szentgotthard surround demo 1.0.organ";
    const COMMENTS=
              "Baroque Organ from Szentgotthard\n"
            . "https://hauptwerk-augustine.info/Szentgotthard.php\n"
            . "\n";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Extended"],
        2=>["SetID"=>2, "Name"=>"Console"],
        3=>["SetID"=>3, "Name"=>"Original"],
    ];
    
    protected $patchEnclosures=[
        210=>[                     "Name"=>"Great", "GroupIDs"=>[201,202,203], "InstanceIDs"=>[1=>52]],
        220=>[                     "Name"=>"Swell", "GroupIDs"=>[301,302,303], "InstanceIDs"=>[1=>54]],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[101,201,301], "InstanceIDs"=>[2=>85132], "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[102,202,302], "InstanceIDs"=>[2=>85133], "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceIDs"=>[2=>85134], "AmpMinimumLevel"=>1],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
      2691=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Bell 1
      2692=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Bell 2
      2693=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Bell 3
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2,+3]],
        94=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        95=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2691]],
        96=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2692]],
        97=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2693]],
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        static $blue=[10232, 10234, 10185, 10187, 10189, 10191, 10193, 10195, 10198, 10200, 10202, 10204, 10206, 10209,
                      10211, 10213, 10216, 10218, 10220, 10222, 10224, 10228, 10230];
        static $red= [10131, 10155, 10175];
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
                            $style=$this->hwdata->textStyle($textInstance["TextStyleID"]);
                            if (isset($style["Font_SizePixels"]))
                                $panelelement->DispLabelFontSize=$style["Font_SizePixels"];

                            if (in_array($switchid, $blue))
                                $panelelement->DispLabelColour="Dark Blue";
                            elseif (in_array($switchid, $red))
                                $panelelement->DispLabelColour="Dark Red";
                            else {
                                // echo $switchid, " ", $data["Name"], "\n";
                                $panelelement->DispLabelColour="Black";
                            }

                            break; // Only the one?
                        }
                        if (!isset($panelelement->PositionX))
                            $panelelement->PositionX=0;
                    }
                    
                    switch ($switchid) {
                        case 10177:
                        case 10179:
                        case 10181:
                        case 10183:
                            $panelelement->PositionX-=350;
                            $panelelement->PositionY-=50;
                            break;
                    }
                }
            }
        }
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        foreach($data["InstanceIDs"] as $panelid=>$instanceid) {
            $instance=$this->hwdata->imageSetInstance($instanceid, TRUE);
            if ($instance!==NULL) {
                $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
                $data["InstanceID"]=$instanceid;
                $this->configureEnclosureImage($panelelement, $data);
                $panelelement->DispLabelText="";
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
                            if ($keyaction["DestKeyboardID"]==1) {
                                $keyImageset["PositionX"]-=5;
                                $keyImageset["PositionY"]+=5;
                            }
                            $keyImageset["HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural"];
                            $keyImageset["HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF"];
                            $keyImageset["HorizSpacingPixels_LeftOfDASharpFromLeftOfDA"];
                            $keyImageset["HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp"];
                            $keyImageset["HorizSpacingPixels_LeftOfEBFromLeftOfDASharp"];
                            $keyImageset["HorizSpacingPixels_LeftOfAFromLeftOfGSharp"];
                            $this->configureKeyImage($panelelement, $keyImageset);
                            $manual->Displayed="N";
                            unset($manual->DisplayKeys);
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
                ["OrganInstallationPackages/002372/Images/Pedals/",
                 "OrganInstallationPackages/002372/Images/Keys/"],   
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
        $rankid=$hwdata["RankID"];
        //error_log(print_r($hwdata, TRUE));
        if (in_array($rankid % 100, [1,2,3,4,5,6])) {
            if (isset($hwdata["NormalMIDINoteNumber"])
                    && ($hwdata["NormalMIDINoteNumber"]>67)) return NULL;
        }
        return parent::processSample($hwdata, $isattack);
    }
    
    /**
     * Run the import
     */
    public static function Build(array $positions=[]) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        
        $hwi=new Szentgotthard(self::SOURCE);
        $hwi->positions=$positions;
        $hwi->import();
        unset($hwi->getOrgan()->InfoFilename);
        echo $hwi->getOrgan()->ChurchName, "\n";
        /* foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
        foreach($hwi->getStops() as $id=>$stop) {
            for ($rn=1; $rn<=$stop->NumberOfRanks; $rn++) {
                $r=$stop->int2str($rn);
                $stop->unset("Rank{$r}PipeCount");
                if ($id==2121)
                    $stop->set("Rank{$r}FirstAccessibleKeyNumber", 18);
            }
        }
        
        foreach([1,101,201] as $rankid) { // Bourdon 32
           $rank=$hwi->getRank($rankid);
           if ($rank!==NULL)
               $rank->Pipe(36)->PitchTuning=-100;
        } */
        $hwi->saveODF(self::TARGET, self::COMMENTS);
    }
    
    public static function Szentgotthard() {
        self::build([1=>"Near", 2=>"Far", 3=>"Rear"]);
    }
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Szentgotthard::Szentgotthard();