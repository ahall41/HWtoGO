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
 * Import Ziegler Organ of Sepsiszentgyörgy (Transylvania) - from Montreux to GrandOrgue
 * 
 * @author andrew
 */
class Sepsiszentgyorgy extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Sepsiszentgyorgy/";
    const SOURCE=self::ROOT . "OrganDefinitions/%s.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "%s 1.1.organ";
    const COMMENTS=
              "Ziegler Organ of Sepsiszentgyörgy (Transylvania) - from Montreux\n"
            . "https://hauptwerk-augustine.info/Ziegler_organ.php\n"
            . "\n";

    private static $targets=[
        "Ziegler dry"       =>[1=>"Near"],
        "Ziegler wet"       =>[2=>"Far"],
        "Ziegler surround"  =>[1=>"Near", 2=>"Far", 3=>"Rear"],
    ];
    
    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
        2=>["SetID"=>2],
        3=>["SetID"=>3],
        4=>["SetID"=>4, "Name"=>"Controls"],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"Recit", "GroupIDs"=>[301,302,303],     "InstanceIDs"=>[1=>58]],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[101,201,301,401], "InstanceIDs"=>[4=>85132], "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[102,202,302,402], "InstanceIDs"=>[4=>85133], "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303,403], "InstanceIDs"=>[4=>85134], "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401,402,403]]
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2688=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Bell
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700]  // Blower
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        88=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2688]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        94=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
        95=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2]],
        96=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+3]],
        97=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-3]],
        98=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+4]],
        99=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-4]],
        89=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        90=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        static $yellow=[10276, 10279, 10282, 10251, 10254, 10260, 10263, 10266, 10269, 10272, 10274, 10249, 10247, 10257];
        static $red=[10148, 10151, 10154, 10184, 10187, 10217, 10220, 10223, 10244];
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
                        
                        if (in_array($switchid, $yellow))
                            $panelelement->DispLabelColour="Yellow";
                        elseif (in_array($switchid, $red))
                            $panelelement->DispLabelColour="Red";
                        else {
                            // echo $switchid, " ", $data["Name"], "\n";
                            $panelelement->DispLabelColour="White";
                        }
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
                 "OrganInstallationPackages/002210/Images/Keys/"],   
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
    public static function Sepsiszentgyorgy(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        
        foreach (self::$targets as $target=>$positions) {
            $hwi=new Sepsiszentgyorgy(sprintf(self::SOURCE, $target));
            $hwi->positions=$positions;
            $hwi->import();
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
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
Sepsiszentgyorgy::Sepsiszentgyorgy();