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
 * Import Renaissance replica from Szár (Paulus Organ Factory, Hungary) to GrandOrgue
 * 
 * @author andrew
 */
class Szar extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Szar/";
    const ODF="Szar-surround two manuals_demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Renaissance replica from Szár (Paulus Organ Factory, Hungary)\n"
            . "https://hauptwerk-augustine.info/Szár-sample--set.php\n"
            . "\n";
    const TARGET=self::ROOT . "Szar-surround two manuals_demo.1.0.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"Swell", "GroupIDs"=>[301,302,303], "InstanceID"=>2],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[102,202,302], "InstanceID"=>85132, "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[101,201,301], "InstanceID"=>85133, "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceID"=>85134, "AmpMinimumLevel"=>0],
         17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700],         "InstanceID"=>85135, "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
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
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        99=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        94=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
        95=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2,+3]],
        96=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2,-3]],
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
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
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    /**
     * Run the import
     */
    public static function Szar(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=51;
        \GOClasses\Manual::$pedals=27;
        if (sizeof($positions)>0) {
            $hwi=new Szar(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("sur new", $target, $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            foreach([2114,2234] as $stopid) {
                $voxhumana=$hwi->getStop($stopid);
                $voxhumana->Rank001FirstAccessibleKeyNumber=13;
                $voxhumana->Rank002FirstAccessibleKeyNumber=13;
                $voxhumana->Rank003FirstAccessibleKeyNumber=13;
            }
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
            self::Szar(
                    [1=>"Far", 2=>"Near"],
                    "surround");
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Szar::Szar();