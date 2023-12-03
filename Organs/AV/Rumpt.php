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
 * Import Naber Organ of the Reformed Church from Rumpt (Netherlands) to GrandOrgue
 * 
 * @author andrew
 */
class Rumpt extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Rumpt/";
    const ODF="Rumpt_demo surround.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Naber Organ of the Reformed Church from Rumpt (Netherlands)\n"
            . "https://hauptwerk-augustine.info/Rumpt.php\n"
            . "\n"
            . "1.1 Correct stop sound effects"
            . "\n";
    const TARGET=self::ROOT . "Rumpt %s demo.1.1.organ";

    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Console"],
        2=>["SetID"=>2, "Name"=>"Controls"],
    ];
    
    protected $patchEnclosures=[
         11=>["EnclosureID"=>"11", "Name"=>"Far",    "GroupIDs"=>[101,201,301,601], "InstanceID"=>85133, "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Near",   "GroupIDs"=>[102,202,302,602], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
         17=>["EnclosureID"=>"17", "Name"=>"Noises", "GroupIDs"=>[700],             "InstanceID"=>85135, "AmpMinimumLevel"=>1],
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
        $panelid=2;
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
    public static function Rumpt(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
        \GOClasses\Manual::$pedals=27;
        if (sizeof($positions)>0) {
            $hwi=new Rumpt(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            unset($hwi->getOrgan()->InfoFilename);
            $hwi->getOrgan()->ChurchName=str_replace("surround", $target, $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getStops() as $id=>$stop) {
                for($n=1; $n<=$stop->NumberOfRanks; $n++) {
                    $s=$stop->int2str($n);
                    $stop->unset("Rank{$s}PipeCount");
                    switch ($id) {
                        case 2115: // Quint 3
                        case 2122: // Bourdon 16D
                        case 2130: // Trumpet 8b
                            $stop->set("Rank{$s}FirstAccessibleKeyNumber", 13);
                    }
                }
            }
            
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        
        else {
            self::Rumpt(
                    [1=>"Far"], "far");
            self::Rumpt(
                    [2=>"Near"], "near");
            self::Rumpt(
                    [1=>"Far", 2=>"Near"], "surround");
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Rumpt::Rumpt();