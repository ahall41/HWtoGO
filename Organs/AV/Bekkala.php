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
 * Import Országh organ of Szentbékkála (Hungary) to GrandOrgue
 * 
 * @author andrew
 */
class Bekkala extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV//Bekkala/";
    const SOURCE=self::ROOT . "OrganDefinitions/Bekkala surround demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Bekkala Demo %s.1.0.organ";
    const COMMENTS=
              "Országh organ of Szentbékkála (Hungary)\n"
            . "https://hauptwerk-augustine.info/Bekkala.php\n"
            . "\n";

    //protected int $releaseCrossfadeLengthMs=100;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Original"],
        2=>["SetID"=>2, "Name"=>"Extended"],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"Pos",    "GroupIDs"=>[301,302,303], "InstanceID"=>23],
         11=>["EnclosureID"=>"11", "Name"=>"Far",    "GroupIDs"=>[101,201,301], "InstanceID"=>85133, "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Near",   "GroupIDs"=>[102,202,302], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",   "GroupIDs"=>[103,203,303], "InstanceID"=>85134, "AmpMinimumLevel"=>1],
         17=>["EnclosureID"=>"17", "Name"=>"Noises", "GroupIDs"=>[700],         "InstanceID"=>85135, "AmpMinimumLevel"=>1],
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

    // Inspection of Ranks object
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
                    break; // Only the one?
                }
                if (!isset($panelelement->PositionX))
                    $panelelement->PositionX=0;
                if (!isset($panelelement->PositionY))
                    $panelelement->PositionY=0;
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
        $panelid=$data["InstanceID"]==23 ? 2 : 1;
        $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        if (empty($panelelement->PositionY)) $panelelement->PositionY=0;
        $panelelement->DispLabelText="";
        if ($panelelement->Bitmap007=="OrganInstallationPackages/002753/Images/expressionPedal/expres07.bmp")
            $panelelement->Bitmap007="expres07.bmp";
        if ($panelelement->Bitmap013=="OrganInstallationPackages/002753/Images/expressionPedal/expres13.bmp")
            $panelelement->Bitmap013="expres13.bmp";
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
        return parent::processNoise($hwdata, $isattack);
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        if ($this->sampleTuning($hwdata)<=1200)
            return parent::processSample($hwdata, $isattack);
        else
            return NULL;
    }

    /**
     * Run the import
     */
    public static function Bekkala(string $source="", array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
        \GOClasses\Manual::$pedals=27;
        if (sizeof($positions)>0) {
            $hwi=new Bekkala(sprintf($source, $target));
            $hwi->positions=$positions;
            $hwi->import();
            unset($hwi->getOrgan()->InfoFilename);
            echo ($hwi->getOrgan()->ChurchName=str_replace("surround", $target, $hwi->getOrgan()->ChurchName)), "\n";
            foreach($hwi->getStops() as $stopid=>$stop) {
                for ($rankid=1; $rankid<=$stop->NumberOfRanks; $rankid++) {
                    $rn=$stop->int2str($rankid);
                    $stop->unset("Rank{$rn}PipeCount");
                    $stop->unset("Rank{$rn}FirstAccessibleKeyNumber");
                
                    switch ($stopid) {
                        case 2001: // PED_Subbas-
                            if (($rankid % 2)!=0)
                                 $stop->set("Rank{$rn}PipeCount",13);
                            break;
 
                        case 2003: // PED_Violinbass-
                        case 2005: // PED_Oktavbass4
                            if (($rankid % 2)==0)
                                 $stop->set("Rank{$rn}FirstAccessibleKeyNumber",14);
                            else 
                                 $stop->set("Rank{$rn}PipeCount",13);
                            break;
                        
                    }
                }
            }
            
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            /* self::Bekkala(
                    self::SOURCE, [1=>"Far"], "far (ext)");
            self::Bekkala(
                    self::SOURCE, [2=>"Near"], "near (ext)");
            self::Bekkala(
                    self::SOURCE, [3=>"Rear"], "rear (ext)"); */
            self::Bekkala(
                    self::SOURCE, [1=>"Far", 2=>"Near", 3=>"Rear"], "surround (ext)");
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Bekkala::Bekkala();