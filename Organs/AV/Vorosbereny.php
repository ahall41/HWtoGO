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
 * Import Silbermann style  Organ from Vörösberény (Hungary)to GrandOrgue
 * 
 * @author andrew
 */
class Vorosbereny extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Vorosbereny/";
    const ODF="Vorosbereny surround_demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Silbermann style  Organ from Vörösberény (Hungary)\n"
            . "https://hauptwerk-augustine.info/Vorosbereny.php\n"
            . "\n"
            . "1.1 Removed Cross Fades\n"
            . "\n";
    const TARGET=self::ROOT . "Vorosbereny Surround Demo.1.1.organ";

    // protected int $releaseCrossfadeLengthMs=30;
    
    // SetID derived from ImageSetElement object for surround.jpg and extend.jpg
    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Original"],
        2=>["SetID"=>2, "Name"=>"Extended"]
    ];
    
    // Inspection of Enclosure object for Swell (230)
    // expresnn.bmp -> ImageSet=3, Instance=23/24
    // Additional enclosures for perspectives (inspection of Rank object)
    // slidernn.bmp -> ImageSet=85142, Instance=85132/3/4/5
    protected $patchEnclosures=[
        230=>[                     "Name"=>"Swell",   "GroupIDs"=>[301,302,303],     "InstanceID"=>23],
         11=>["EnclosureID"=>"11", "Name"=>"Front 1", "GroupIDs"=>[101,201,301,401], "InstanceID"=>85132, "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Front 2", "GroupIDs"=>[102,202,302,402], "InstanceID"=>85133, "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",    "GroupIDs"=>[103,203,303,403], "InstanceID"=>85134, "AmpMinimumLevel"=>0],
         17=>["EnclosureID"=>"17", "Name"=>"Noises",  "GroupIDs"=>[700],             "InstanceID"=>85135, "AmpMinimumLevel"=>0],
    ];
    
    // Inspection of Tremulant object + specification
    protected $patchTremulants=[
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401,402,403]],
    ];
    
    // Inspection of Stops object. Note that stops in the range 10-1999 are filtered out,
    // and we need separate "stops" for on/off, which will be linked to the same switch
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"DivisionKeyAction_04 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -4=>["StopID"=>-4, "DivisionID"=>4, "Name"=>"DivisionKeyAction_04 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    // Inspection of Ranks object
    /** @todo: echo key actions ??? */
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

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        $panelelement=$this->getPanel(1)->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->DispLabelText="";
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
        if ($filename=="OrganInstallationPackages/002546/images/expressionPedal/expres07.bmp")
            return "expres07.bmp";
        elseif ($filename=="OrganInstallationPackages/002546/images/expressionPedal/expres13.bmp")
            return "expres13.bmp";
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
    public static function Vorosbereny(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=56;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new Vorosbereny(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("sur new", $target, $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
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
            self::Vorosbereny(
                    [1=>"Front 1", 2=>"Front 2", 3=>"Rear"],
                    "surround");
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Vorosbereny::Vorosbereny();