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
 * Import POM - French style Baroque organ from Zamárdi (Hungary) to GrandOrgue
 * 
 * @author andrew
 */
class Zamardi extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Zamardi/";
    const ODF="Zamardi surround_demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "POM - French style Baroque organ from Zamárdi (Hungary)\n"
            . "https://hauptwerk-augustine.info/Zamardi.php\n"
            . "\n";
    const TARGET=self::ROOT . "Zamardi %s_demo.1.0.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Original"],
        2=>["SetID"=>2, "Name"=>"Extended"],
    ];
    
    protected $patchEnclosures=[
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[102,202,302], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[101,201,301], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>0],
         17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700],         "InstanceIDs"=>[1=>85135], "AmpMinimumLevel"=>0],
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
        -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -4=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      1006=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Coupler ???
      1011=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Coupler ???
      1216=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Coupler ???
      1206=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Coupler ???
      1710=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Tremulant ???
      1720=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Tremulant ???
      1730=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Tremulant ???
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
      2691=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700]  // Rossignol
    ];

    protected $patchRanks=[
        36=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2691]],
       136=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2691]],
       236=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2691]],
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        99=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        94=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
        95=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2,+3, +4]],
        96=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2,-3, -4]],
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
    public static function Zamardi(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=56;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new Zamardi(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("sur new", $target, $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            foreach([2331,2334] as $stopid) {
                $cornet=$hwi->getStop($stopid);
                $cornet->Rank001FirstAccessibleKeyNumber=25;
                $cornet->Rank002FirstAccessibleKeyNumber=25;
                $cornet->Rank003FirstAccessibleKeyNumber=25;
            }
            $bourdon16=$hwi->getStop(2226);
            $bourdon16->Rank002FirstAccessibleKeyNumber=13;
            $bourdon16->Rank004FirstAccessibleKeyNumber=13;
            $bourdon16->Rank006FirstAccessibleKeyNumber=13;
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
            self::Zamardi(
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
Zamardi::Zamardi();