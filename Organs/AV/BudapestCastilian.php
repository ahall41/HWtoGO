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
 * Import Aeris Castilian replica Organ from Budapest to GrandOrgue
 * 
 * @author andrew
 */
class BudapestCastilian extends AVOrgan {

    const ROOT="/GrandOrgue/Organs/AV//Budapest_Castilian/";
    const SOURCE=self::ROOT . "OrganDefinitions/Castilian Budapest surround %s_demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Castilian Budapest (%s) 1.0.organ";
    const COMMENTS=
              "Aeris Castilian replica Organ from Budapest\n"
            . "https://hauptwerk-augustine.info/Castilian.php\n"
            . "\n";
    
    protected $patchDivisions=[
        6=>["DivisionID"=>6, "Name"=>"Cornetta"]
    ];
    
    public function configurePanelSwitchImage(\GOClasses\PanelElement $panelelement, array $data): void {
        parent::configurePanelSwitchImage($panelelement, $data);
        unset($panelelement->MouseRectTop);
    }
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }
    
    public function getManual($id): \GOClasses\Manual {
        if ($id==6) $id=2;
        return parent::getManual($id);
    }
    
    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        foreach ($data["InstanceIDs"] as $panelid=>$instanceid) {
            $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
            $data["InstanceID"]=$instanceid;
            $this->configureEnclosureImage($panelelement, $data);
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
                            if ($keyboardid>1) $keyImageset["PositionX"]+=8;
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
                ["OrganInstallationPackages/002383/Images/Pedals/",
                 "OrganInstallationPackages/002383/Images/Keys/"],   
                $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the import
     */
    public static function BudapestCastilian(AVOrgan $hwi, array $positions)  {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=49;
        \GOClasses\Manual::$pedals=24;
        
        $hwi->positions=$positions;
        $hwi->import();
        unset($hwi->getOrgan()->InfoFilename);
        echo $hwi->getOrgan()->ChurchName, "\n"; 
        foreach ($hwi->getStops() as $stopid=>$stop) {
            for ($rankid=1; $rankid<=$stop->NumberOfRanks; $rankid++) {
                $rn=$stop->int2str($rankid);
                if (substr($stop->Name, -2)=="_R") {
                    $stop->set("Rank{$rn}FirstAccessibleKeyNumber", 26);
                }
            }
        }
 
        
        
    }
}

class Original extends BudapestCastilian {

    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Original"],
    ];
    
    protected $patchStopRanks=[
        47=>["RankID"=>212]
    ];
    
    protected $patchStops=[
        +2=>["StopID"=>+2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2105=>["DivisionID"=>6],
      2680=>"DELETE",
      2681=>"DELETE",
      2682=>"DELETE",
      2683=>"DELETE",
      2684=>"DELETE",
      2685=>"DELETE",
      2690=>["DivisionID"=>2, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
    ];

    protected $patchRanks=[
        80=>"DELETE", 180=>"DELETE", 280=>"DELETE", 
        81=>"DELETE", 181=>"DELETE", 281=>"DELETE", 
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
        94=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
        95=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2]],
    ];
    
    protected $patchEnclosures=[
        210=>[                     "Name"=>"Exp 1", "GroupIDs"=>[601,602,603],     "InstanceIDs"=>[1=>10]],
         11=>["EnclosureID"=>"11", "Name"=>"Far",   "GroupIDs"=>[101,201,301,601], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Near",  "GroupIDs"=>[102,202,302,601], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303,601], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>1],
    ];
    
    public function createOrgan(array $hwdata) : \GOClasses\Organ {
        $organ=parent::createOrgan($hwdata);
        $organ->HasPedals="N";
        return $organ;
    }
 
    public static function Original() {
        $hwi=new Original(sprintf(self::SOURCE, "original"));
        parent::BudapestCastilian($hwi, [1=>"Near", 2=>"Far", 3=>"Rear"]);
        $hwi->saveODF(sprintf(self::TARGET, "original"), self::COMMENTS);
    }
    
}

class Extended extends BudapestCastilian {

    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Original"],
        2=>["SetID"=>2, "Name"=>"Extended"],
    ];
    
    protected $patchStopRanks=[
        108=>["RankID"=>212]
    ];

    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -3=>["StopID"=>-3, "DivisionID"=>3, "Name"=>"DivisionKeyAction_03 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2105=>["DivisionID"=>6],
      2680=>"DELETE",
      2681=>"DELETE",
      2682=>"DELETE",
      2683=>"DELETE",
      2684=>"DELETE",
      2685=>"DELETE",
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
    ];

     protected $patchRanks=[
        80=>"DELETE", 180=>"DELETE", 280=>"DELETE", 
        81=>"DELETE", 181=>"DELETE", 281=>"DELETE", 
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1, +2, +3]],
        94=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
        95=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1, -2, -3]],
    ];
    
    protected $patchEnclosures=[
        210=>[                     "Name"=>"Exp 1", "GroupIDs"=>[601,602,603],     "InstanceIDs"=>[1=>19, 2=>21]],
        220=>[                     "Name"=>"Exp 2", "GroupIDs"=>[301,302,303],     "InstanceIDs"=>[2=>23]],
         11=>["EnclosureID"=>"11", "Name"=>"Far",   "GroupIDs"=>[101,201,301,601], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Near",  "GroupIDs"=>[102,202,302,601], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303,601], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>1],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301]],
    ];
    
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
                    $panelelement->DispLabelText=
                            str_replace(
                                    ["\n", "Trem. Red", "Trem.  Flue"], 
                                    [" ", "Trem. II", "Trem. I"],
                                    $textInstance["Text"]);
                    $style=$this->hwdata->textStyle($textInstance["TextStyleID"]);
                    if (isset($style["Font_SizePixels"]))
                        $panelelement->DispLabelFontSize=$style["Font_SizePixels"];

                    if ($style["Colour_Blue"]>100) 
                        $panelelement->DispLabelColour="Dark Blue";
                    elseif($style["Colour_Red"]>100) 
                        $panelelement->DispLabelColour="Dark Red";
                    else
                        $panelelement->DispLabelColour="Black";
                    break; // Only the one?
                }
                if (!isset($panelelement->PositionX)) $panelelement->PositionX=0;
                unset($panelelement->MouseRectTop);
            }
        }
    }
    
    public static function Extended() {
        $hwi=new Extended(sprintf(self::SOURCE, "extended"));
        parent::BudapestCastilian($hwi, [1=>"Near", 2=>"Far", 3=>"Rear"]);
        foreach ($hwi->getStops() as $stopid=>$stop) {
            for ($rankid=1; $rankid<=$stop->NumberOfRanks; $rankid++) {
                $rn=$stop->int2str($rankid);
                switch($stopid) {
                    case 2002:
                        $stop->unset("Rank${rn}FirstPipeNumber");
                        break;
                }
            }
        }
        
        // Missing pipe!
        foreach ([2, 102, 202] as $rankid) {
            $rank=$hwi->getRank($rankid);
            $pipe=$rank->Pipe(60, $rank->Pipe(61));
            $pipe->PitchTuning=-100;
        }
        $hwi->saveODF(sprintf(self::TARGET, "extended"), self::COMMENTS);
    }
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");

Original::Original();
Extended::Extended();
