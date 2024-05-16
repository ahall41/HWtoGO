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
 * Import Aquincum Organ from Savaria to GrandOrgue
 * 
 * @author andrew
 */
class Savaria extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV//Savaria/";
    const SOURCE=self::ROOT . "OrganDefinitions/Savaria-surround.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Savaria surround demo 1.0.organ";
    const COMMENTS=
              "Aquincum Organ from Savaria\n"
            . "https://hauptwerk-augustine.info/Savaria.php\n"
            . "\n";

    // protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
        2=>["SetID"=>2],
        3=>["SetID"=>3],
        4=>["SetID"=>4, "Name"=>"Controls"],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"Swell", "GroupIDs"=>[301,302,303],     "InstanceIDs"=>[1=>64]],
        230=>[                     "Name"=>"Pos",   "GroupIDs"=>[401,402,403],     "InstanceIDs"=>[1=>66]],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[101,201,301,401], "InstanceIDs"=>[4=>85132], "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[102,202,302,402], "InstanceIDs"=>[4=>85133], "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303,403], "InstanceIDs"=>[4=>85134], "AmpMinimumLevel"=>1],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401,402,403]],
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"DivisionKeyAction_03 On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -4=>["StopID"=>-4, "DivisionID"=>4, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
      1710=>"DELETE",
      1720=>"DELETE",
      1730=>"DELETE"  
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
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

    public function xxcreateStop(array $hwdata): ?\GOClasses\Sw1tch {
        error_log(print_r($hwdata, 1));
        return parent::createStop($hwdata);
    }

    public function xxcreateRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        error_log(print_r($hwdata, 1));
        return parent::createRank($hwdata, $keynoise);
    }
    
    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        static $blue=[10394, 10397, 10400, 10292, 10295, 10298, 10301, 10304, 10307, 10310, 10313,
                      10316, 10319, 10322, 10325, 10328, 10331, 10334, 10337, 10340, 10343, 10346,
                      10349, 10352, 10355, 10358, 10361, 10364, 10367, 10370, 10373, 10376, 10379,
                      10382, 10385, 10388, 10391];
        static $red= [10154, 10157, 10160, 10163, 10166, 10205, 10208, 10238, 10241, 10244, 10247,
                      10250, 10283, 10286];
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

                            $panelelement->DispLabelFontSize=10;

                            if (in_array($switchid, $blue))
                                $panelelement->DispLabelColour="Blue";
                            elseif (in_array($switchid, $red))
                                $panelelement->DispLabelColour="Red";
                            else {
                                // echo $switchid, " ", $data["Name"], "\n";
                                $panelelement->DispLabelColour="Black";
                            }
                            
                            $panelelement->TextRectTop=10;

                            // echo $panelelement, "\n";
                            break; // Only the one?
                        }

                        if (!isset($panelelement->PositionX))
                            $panelelement->PositionX=0;
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
                ["pedals/",
                 "OrganInstallationPackages/002203/images/keys/"],   
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
        
        $hwi=new Savaria(self::SOURCE);
        $hwi->positions=$positions;
        $hwi->import();
        unset($hwi->getOrgan()->InfoFilename);
        echo $hwi->getOrgan()->ChurchName, "\n";

        foreach($hwi->getStops() as $stopid=>$stop) {
            for ($rn=1; $rn<=$stop->NumberOfRanks; $rn++) {
                $r=$stop->int2str($rn);
                $stop->unset("Rank{$r}PipeCount");
                if (in_array($stopid,[2014, 2128])) { // Chamade 4'/8'
                    $stop->unset("Rank{$r}PipeCount");
                    $stop->unset("Rank{$r}FirstPipeNumber");
                }
            }
        }
        $hwi->saveODF(self::TARGET, self::COMMENTS);
    }
    
    public static function Savaria() {
        self::build([1=>"Near", 2=>"Far", 3=>"Rear"]);
    }
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Savaria::Savaria();