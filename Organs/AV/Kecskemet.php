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
 * Import Jehmlich Organ from Kecskemét to GrandOrgue
 * 
 * @author andrew
 */
class Kecskemet extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Kecskemet/";
    const ODF="Jehmlich_Kecskemet_surround.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Jehmlich Organ from Kecskemét\n"
            . "https://hauptwerk-augustine.info/Jehmlich_Kecskemet.php\n"
            . "\n";
    const TARGET=self::ROOT . "Jehmlich_Kecskemet_surround.1.0.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchEnclosures=[
        210=>[                     "Name"=>"Pos",   "GroupIDs"=>[201,202,203], "InstanceIDs"=>[1=>51]],
        230=>[                     "Name"=>"Swell", "GroupIDs"=>[401,402,403], "InstanceIDs"=>[1=>53]],
         11=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[101,201,301], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[102,202,302], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401,402,403]]
    ];
    
    protected $patchStops=[
        +1=>["ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      1006=>"DELETE", // Coupler
      1011=>"DELETE", // Coupler
      1111=>"DELETE", // Coupler
      1116=>"DELETE", // Coupler
      1216=>"DELETE", // Coupler
      1206=>"DELETE", // Coupler
      1710=>"DELETE", // Tremulant 
      1720=>"DELETE", // Tremulant 
      1730=>"DELETE", // Tremulant 
      2354=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>700], // Zilberstern
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    protected $patchRanks=[
        54=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2354]],
       154=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2354]],
       254=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2354]],
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1,+2,+3,+4]],
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
                ], //"OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-"],
                ["OrganInstallationPackages/001745/Images/Pedals/",
                ], // "OrganInstallationPackages/001745/Images/Keys/"],   
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
    public static function Kecskemet(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=58;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new Kecskemet(self::SOURCE);
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
            self::Kecskemet(
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
Kecskemet::Kecskemet();