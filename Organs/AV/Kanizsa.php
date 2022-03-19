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
 * Import Baroque Organ of the Holy Guardian Angels Church from Magyarkanizsa (Serbia) to GrandOrgue
 * 
 * @author andrew
 */
class Kanizsa extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/Kanizsa/";
    const ODF="Kanizsa demo surround.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Baroque Organ of the Holy Guardian Angels Church from Magyarkanizsa (Serbia)\n"
            . "https://hauptwerk-augustine.info/Kanizsa.php\n"
            . "\n";
    const TARGET=self::ROOT . "Kanizsa demo %s.1.0.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Original"],
        2=>["SetID"=>2, "Name"=>"Extended"],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"Pos",   "GroupIDs"=>[301,302,303], "InstanceIDs"=>[2=>73]],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[102,202,302], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[101,201,301], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>0],
         17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700],         "InstanceIDs"=>[1=>85135], "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]]
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
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
        95=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
        96=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2]],
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
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
                            $keyImageset["PositionX"]=$keyboard["KeyGen_DispKeyboardLeftXPos"]-25;
                            $keyImageset["PositionY"]=$keyboard["KeyGen_DispKeyboardTopYPos"];
                            $keyImageset["HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural"]+=1;
                            $keyImageset["HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF"]+=1;
                            $keyImageset["HorizSpacingPixels_LeftOfDASharpFromLeftOfDA"]+=1;
                            $keyImageset["HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp"]+=1;
                            $keyImageset["HorizSpacingPixels_LeftOfEBFromLeftOfDASharp"]+=1;
                            $keyImageset["HorizSpacingPixels_LeftOfAFromLeftOfGSharp"]+=1;
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
    public static function Kanizsa(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=51;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new Kanizsa(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("sur new", $target, $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            foreach($hwi->getStops() as $stop) {
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
            self::Kanizsa(
                    [1=>"Far", 2=>"Near", 3=>"Rear"],
                    "surround");
        }
    }   
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Kanizsa::Kanizsa();