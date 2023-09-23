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
 * Import Baroque Organ of the Benedictine Abbey in Tihany (Hungary) to GrandOrgue
 * 
 * @author andrew
 */
class Tihany extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Tihany/";
    const ODF="Tihany surround_demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Baroque Organ of the Benedictine Abbey in Tihany (Hungary)\n"
            . "https://hauptwerk-augustine.info/Tihany.php\n"
            . "\n";
    const TARGET=self::ROOT . "Tihany surround_demo.1.0.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Console"],
        2=>["SetID"=>2, "Name"=>"Original"],
        3=>["SetID"=>3, "Name"=>"Extended"],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"II",     "GroupIDs"=>[301,302,303], "InstanceIDs"=>[3=>48]],
         11=>["EnclosureID"=>"11", "Name"=>"Far",    "GroupIDs"=>[101,201,301], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Near",   "GroupIDs"=>[102,202,302], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",   "GroupIDs"=>[103,203,303], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>0],
         17=>["EnclosureID"=>"17", "Name"=>"Noises", "GroupIDs"=>[700],         "InstanceIDs"=>[1=>85135], "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
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
        93=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
        94=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
        95=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2]],
        96=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+3]],
        97=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-3]],
        98=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        99=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
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
                            $keyImageset["PositionX"]=$keyboard["KeyGen_DispKeyboardLeftXPos"];
                            $keyImageset["PositionY"]=$keyboard["KeyGen_DispKeyboardTopYPos"];
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
                ["Pedals/KeyImageSet008-",
                 "OrganInstallationPackages/002536/Images/Keys/"],  
                $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    /**
     * Run the import
     */
    public static function Tihany(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=58;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new Tihany(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getStops() as $id=>$stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
                unset($stop->Rank004PipeCount);
                unset($stop->Rank005PipeCount);
                unset($stop->Rank006PipeCount);
                if ($id==2006) { // PED_Trompete8
                    unset($stop->Rank001FirstAccessibleKeyNumber);
                    unset($stop->Rank002FirstAccessibleKeyNumber);
                    unset($stop->Rank003FirstAccessibleKeyNumber);
                }
                elseif ($id==2119) { // GRT_Cornet5f
                    $stop->Rank001FirstAccessibleKeyNumber=25; // 060-c
                    $stop->Rank002FirstAccessibleKeyNumber=25; // 060-c
                    $stop->Rank003FirstAccessibleKeyNumber=25; // 060-c
                }
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Tihany(
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
Tihany::Tihany();