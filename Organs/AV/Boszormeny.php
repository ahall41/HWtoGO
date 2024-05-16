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
 * Import Angster Organ from Reformed Church in Hajdúböszörmény (Hungary) to GrandOrgue
 * 
 * @author andrew
 */
class Boszormeny extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Boszormeny/";
    const ODF="Boszormeny surround_demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Angster Organ from Reformed Church in Hajdúböszörmény (Hungary)\n"
            . "https://hauptwerk-augustine.info/Boszormeny.php\n"
            . "\n"
            . "1.1 Removed cross fades\n"
            . "\n";
    const TARGET=self::ROOT . "Boszormeny %s demo.1.1.organ";

    //protected int $releaseCrossfadeLengthMs=0;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Console"],
        2=>["SetID"=>2, "Name"=>"Controls"],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"II",     "GroupIDs"=>[301,302,303],     "InstanceID"=>23],
        230=>[                     "Name"=>"III",    "GroupIDs"=>[401,402,403],     "InstanceID"=>25],
         11=>["EnclosureID"=>"11", "Name"=>"Far",    "GroupIDs"=>[101,201,301,601], "InstanceID"=>85133, "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Near",   "GroupIDs"=>[102,202,302,602], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",   "GroupIDs"=>[103,203,303,603], "InstanceID"=>85134, "AmpMinimumLevel"=>1],
         17=>["EnclosureID"=>"17", "Name"=>"Noises", "GroupIDs"=>[700],             "InstanceID"=>85135, "AmpMinimumLevel"=>1],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401,402,403]],
    ];
    
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
    protected $patchRanks=[
        91=>["Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"StopOff", "GroupID"=>700, "StopIDs"=>[]],
        94=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+2]],
        95=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-2]],
        96=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+3]],
        97=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-3]],
        98=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+1]],
        99=>["Noise"=>"KeyOff",  "GroupID"=>700, "StopIDs"=>[-1]],
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }
   
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (isset($hwdata["Noise"]) && $hwdata["Noise"]=="Ambient")
            return NULL;
        else
            return parent::createRank($hwdata, $keynoise);
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
                foreach($this->hwdata->textInstance($instanceid) as $textInstance) {
                    $panelelement->DispLabelText=str_replace("\n", " ", $textInstance["Text"]);
                    switch ($textInstance["TextStyleID"]) {
                        
                        case 4:
                            $panelelement->DispLabelColour="Dark Red";
                            break;
                            
                        case 5:
                            $panelelement->DispLabelColour="Dark Blue";
                            break;
                        
                        case 6:
                            $panelelement->DispLabelColour="Dark Green";
                            break;
                        
                        default:
                            $panelelement->DispLabelColour="Black";
                            
                    }
                    break; // Only the one?
                }
                if (!isset($panelelement->PositionX)) $panelelement->PositionX=0;
                $panelelement->DispLabelFontSize=12;
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
        $panelid=1;
        $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
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
    public static function Boszormeny(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=56;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new Boszormeny(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            unset($hwi->getOrgan()->InfoFilename);
            $hwi->getOrgan()->ChurchName=str_replace("surround", $target, $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getStops() as $id=>$stop) {
                for($n=1; $n<=$stop->NumberOfRanks; $n++) {
                    $s=$stop->int2str($n);
                    $stop->unset("Rank{$s}PipeCount");
                }
            }
            
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        
        else {
            self::Boszormeny(
                    [1=>"Far"], "far");
            self::Boszormeny(
                    [2=>"Near"], "near");
            self::Boszormeny(
                    [3=>"Rear"], "rear");
            self::Boszormeny(
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
Boszormeny::Boszormeny();