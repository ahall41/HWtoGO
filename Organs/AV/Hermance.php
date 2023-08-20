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
 * Import Mascioni Organ from Hermance (Kanton of Geneva, Switzerland) to GrandOrgue
 * 
 * @author andrew
 */
class Hermance extends AVOrgan {

    const ROOT="/GrandOrgue/Organs/AVO/Hermance/";
    const SOURCE=self::ROOT . "/OrganDefinitions/Mascioni-Hermance_%s.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Mascioni-Hermance %s 1.0.organ";
    const COMMENTS=
              "Mascioni Organ from Hermance (Kanton of Geneva, Switzerland)\n"
            . "https://hauptwerk-augustine.info/Hermance.php\n"
            . "\n";
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }
    
    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        foreach ($data["InstanceIDs"] as $panelid=>$instanceid) {
            if ($this->hwdata->imageSetInstance($instanceid, TRUE)) {
                $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
                $data["InstanceID"]=$instanceid;
                $this->configureEnclosureImage($panelelement, $data);
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
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        if ($this->sampleTuning($hwdata) > 1800) return NULL;
        return parent::processSample($hwdata, $isattack);
    }
    

    /**
     * Run the import
     */
    public static function Build(AVOrgan $hwi, array $positions)  {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        $hwi->positions=$positions;
        $hwi->import();
        unset($hwi->getOrgan()->InfoFilename);
        echo $hwi->getOrgan()->ChurchName, "\n";
        
        // Increase volume of key noise effects
        foreach ([93, 94] as $rankid) {
            $rank=$hwi->getRank($rankid);
            foreach ($rank->Pipes() as $pipe) unset($pipe->Gain);
        }
    }
}

class Original extends Hermance {

    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchStops=[
        +1=>["StopID"=>+2, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["StopID"=>+2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      1006=>"DELETE",
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+2]],
        94=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+1]],
    ];

    protected $patchEnclosures=[
         11=>["EnclosureID"=>"11", "Name"=>"Far",   "GroupIDs"=>[201], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Near",  "GroupIDs"=>[202], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[203], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>1],
    ];
    
    public function createCouplers(array $keyactions) : void {
        $coupler=$this->newCoupler(10112, "Grt. Ped.8");
        $coupler->DefaultToEngaged="Y";
        $coupler->DestinationManual=1;
        $coupler->GCState=1;
        $coupler->Displayed="N";
        unset($coupler->DispLabelColour);
        $this->getManual(1)->Coupler($coupler);
    }

    public function createSwitchNoise(string $type, array $switchdata): void {
        if ($type!==self::CouplerNoise)
            parent::createSwitchNoise($type, $switchdata);
    }

    protected function correctFileName(string $filename): string {
        $filename=str_replace(
            ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
             "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-"],
            ["OrganInstallationPackages/002377/Images/Pedals/",
             "OrganInstallationPackages/002377/Images/Keys/"],   
             $filename);
        return parent::correctFileName($filename);
    }
 
    public static function Build(AVOrgan $hwi, array $positions) {
        parent::Build($hwi, $positions);
        foreach($hwi->getStops() as $stopid=>$stop) {
            for ($rn=1; $rn<=$stop->NumberOfRanks; $rn++) {
                $rn=$stop->int2str($rn);
                $stop->unset("Rank{$rn}PipeCount"); 
                $stop->unset("Rank{$rn}FirstPipeNumber");
                
                switch ($stopid) {
                    case 2102:
                    case 2104:
                    case 2110:
                    case 2107:
                        $stop->set("Rank{$rn}FirstAccessibleKeyNumber", 22);
                        break;
                }
            }
        }
    }
    
    
    public static function Original() {
        \GOClasses\Manual::$keys=45;
        \GOClasses\Manual::$pedals=19;
        
        $hwi=new Original(sprintf(self::SOURCE, "original_semidry"));
        self::Build($hwi, [1=>"Near"]);
        $hwi->saveODF(sprintf(self::TARGET, "original semidry"), self::COMMENTS);

        $hwi=new Original(sprintf(self::SOURCE, "original_wet"));
        self::Build($hwi, [1=>"Far"]);
        $hwi->saveODF(sprintf(self::TARGET, "original wet"), self::COMMENTS);

        $hwi=new Original(sprintf(self::SOURCE, "original_surround"));
        self::Build($hwi, [1=>"Near", 2=>"Far", 3=>"Rear"]);
        $hwi->saveODF(sprintf(self::TARGET, "original surround"), self::COMMENTS);
    }
    
}

class Extended extends Hermance {

    protected $patchDisplayPages=[
        1=>["SetID"=>1],
        2=>["SetID"=>2],
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      1105=>"DELETE",
      1107=>"DELETE",
      1006=>"DELETE",
      1210=>"DELETE",
      1011=>"DELETE",
      1111=>"DELETE",
      1206=>"DELETE",
      1710=>"DELETE",
      1720=>"DELETE",
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+2]],
        94=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+1]],
    ];
    
    protected $patchEnclosures=[
         11=>["EnclosureID"=>"11", "Name"=>"Far",   "GroupIDs"=>[101,201,301], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Near",  "GroupIDs"=>[102,202,302], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>1],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
    ];
    
    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        if (empty($switch)) return;
        $switchid=$data["SwitchID"];
        $slinkid=$this->hwdata->switchLink($switchid)["D"][0]["SourceSwitchID"];
        foreach($this->hwdata->switchLink($slinkid)["S"] as $link) {
            $switchdata=$this->hwdata->switch($destid=$link["DestSwitchID"]);
            if (isset($switchdata["Disp_ImageSetInstanceID"])) {
                $instancedata=$this->hwdata->imageSetInstance($instanceid=$switchdata["Disp_ImageSetInstanceID"]);
                $panel=$this->getPanel($instancedata["DisplayPageID"], FALSE);
                if ($panel!==NULL) {
                    $panelelement=$panel->GUIElement($switch);
                    $this->configureImage($panelelement, ["SwitchID"=>$destid]);
                    $textinstances=$this->hwdata->textInstance($instanceid);
                    if (sizeof($textinstances)>0) {
                        foreach($textinstances as $textInstance) {
                            $panelelement->DispLabelText=str_replace("\n", " ", $textInstance["Text"]);

                            $panelelement->DispLabelFontSize=10;
                                $panelelement->DispLabelColour="Black";
                            break; // Only the one?
                        }

                        if (!isset($panelelement->PositionX))
                            $panelelement->PositionX=0;
                    }
                }
            }
        }
    }

    protected function correctFileName(string $filename): string {
        $filename=str_replace(
            ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
             "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-"],
            ["OrganInstallationPackages/002376/Images/Pedals/",
             "OrganInstallationPackages/002376/Images/Keys/"],   
             $filename);
        return parent::correctFileName($filename);
    }
    
    public static function Build(AVOrgan $hwi, array $positions) {
        parent::Build($hwi, $positions);
        foreach($hwi->getStops() as $stopid=>$stop) {
            for ($rn=1; $rn<=$stop->NumberOfRanks; $rn++) {
                $rn=$stop->int2str($rn);
                $stop->unset("Rank{$rn}PipeCount"); 
                $stop->unset("Rank{$rn}FirstPipeNumber");
                
                switch ($stopid) {
                    case 2107:
                    case 2109:
                        $stop->set("Rank{$rn}FirstAccessibleKeyNumber", 26);
                        break;
 
                    case 2112:
                    case 2115:
                    case 2220:
                        $stop->set("Rank{$rn}FirstAccessibleKeyNumber", 24);
                        break;
                }
            }
        }
        // Missing pipe - P Vox Humana 
        foreach ([5, 15, 12, 21, 105, 112, 115, 121, 205, 212, 215, 221] as $rankid) {
            if (($rank=$hwi->getRank($rankid))) {;
                $pipe=$rank->Pipe(60, $rank->Pipe(61));
                $pipe->PitchTuning=-100;
            }
        }

    }
    
    public static function Extended() {
        \GOClasses\Manual::$keys=51;
        \GOClasses\Manual::$pedals=27;

        $hwi=new Extended(sprintf(self::SOURCE, "extend_semidry"));
        self::Build($hwi, [1=>"Near"]);
        $hwi->saveODF(sprintf(self::TARGET, "extended semidry"), self::COMMENTS);

        $hwi=new Extended(sprintf(self::SOURCE, "extend_wet"));
        self::Build($hwi, [1=>"Wet"]);
        $hwi->saveODF(sprintf(self::TARGET, "extended wet"), self::COMMENTS);

        $hwi=new Extended(sprintf(self::SOURCE, "extend_surround"));
        self::Build($hwi, [1=>"Near", 2=>"Far", 3=>"Rear"]);
        $hwi->saveODF(sprintf(self::TARGET, "extended surround"), self::COMMENTS);
    }
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");

Original::Original();
Extended::Extended();