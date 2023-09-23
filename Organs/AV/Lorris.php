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
 * Import Organ from Notre Dame Church of Lorris to GrandOrgue
 * 
 * @author andrew
 */
class Lorris extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Lorris/";
    const ODF="Lorris_wet_and_dry.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Organ from Notre Dame Church of Lorris\n"
            . "https://hauptwerk-augustine.info/Lorris.php\n"
            . "\n";
    const TARGET=self::ROOT . "Lorris_wet_and_dry.1.0.organ";
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchDivisions=[
        3=>"DELETE"
    ];
    
    protected $patchEnclosures=[
        11=>["EnclosureID"=>"11", "Name"=>"Dry",   "GroupIDs"=>[101,201], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
        12=>["EnclosureID"=>"12", "Name"=>"Wet",   "GroupIDs"=>[102,202], "InstanceID"=>85133, "AmpMinimumLevel"=>1],
        17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700], "InstanceID"=>85135, "AmpMinimumLevel"=>1],
        
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        93=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
        94=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
        95=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2]],
        96=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        99=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
    ];
    
    protected $patchStops=[
      +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,301,302]]
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<3)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        $panelelement=$this->getPanel(1)->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->DispLabelText="";
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

    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        $coupler=parent::createCoupler($hwdata);
        $coupler->DefaultToEngaged="Y";
        $coupler->GCState=1;
        return $coupler;
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
                "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
                "OrganInstallationPackages/002537/Images/Pedals/",
                $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    /**
     * Run the import
     */
    public static function Lorris(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=49;
        \GOClasses\Manual::$pedals=19;
        if (sizeof($positions)>0) {
            $hwi=new Lorris(self::SOURCE);
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
            $hwi->saveODF(self::TARGET, self::COMMENTS);
        }
        else {
            self::Lorris(
                    [1=>"Wet", 2=>"Dry"],
                    "surround");
        }
    }   
}
Lorris::Lorris();