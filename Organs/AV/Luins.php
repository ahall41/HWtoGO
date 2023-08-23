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
 * Import Armagni & Mingot organ from Luins (Switzerland) to GrandOrgue
 * 
 * @author andrew
 */
class Luins extends AVOrgan {

    const ROOT="/GrandOrgue/Organs/AVO/Luins/";
    const SOURCE=self::ROOT . "/OrganDefinitions/Luins_%s.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Luins %s 1.0.organ";
    const COMMENTS=
              "Armagni & Mingot organ from Luins (Switzerland)\n"
            . "https://hauptwerk-augustine.info/Luins.php\n"
            . "\n";
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchStops=[
        +1=>["DivisionID"=>1, "Name"=>"DivisionKeyAction_01 On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["DivisionID"=>2, "Name"=>"DivisionKeyAction_02 On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["DivisionID"=>3, "Name"=>"DivisionKeyAction_02 On",   "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      1002=>"DELETE",
      1105=>"DELETE",
      1107=>"DELETE",
      1005=>"DELETE",
      1006=>"DELETE",
      1007=>"DELETE",
      1210=>"DELETE",
      1212=>"DELETE",
      1010=>"DELETE",
      1011=>"DELETE",
      1012=>"DELETE",
      1110=>"DELETE",
      1111=>"DELETE",
      1112=>"DELETE",
      1206=>"DELETE",
      1103=>"DELETE",
      1114=>"DELETE",
      1710=>"DELETE",
      1720=>"DELETE",
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+2]],
        94=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+3]],
        95=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[+1]],
    ];

    protected $patchEnclosures=[
        220=>[                     "Name"=>"Swell", "GroupIDs"=>[301,302,303],     "InstanceIDs"=>[1=>40]],
         11=>["EnclosureID"=>"11", "Name"=>"Far",   "GroupIDs"=>[201], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Near",  "GroupIDs"=>[202], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[203], "InstanceIDs"=>[1=>85134], "AmpMinimumLevel"=>1],
    ];
    
    
    protected $patchTremulants=[
        1700=>"DELETE",
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
    ];

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

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        if (empty($switch)) return;
        static $yel=[10185, 10187, 10150, 10152, 10154, 10156, 10160, 10162, 10164, 
                     10166, 10170, 10172, 10176, 10178, 10180, 10182, 10116, 10120,
                     10126, 10136, 10146, 10108, 10112, 10114];
        
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

                            // echo $switchid, " ", /* $data["Name"]," ", */ $panelelement->DispLabelText, "\n";
                            if (in_array($switchid, $yel))
                                $panelelement->DispLabelColour="Yellow";
                            else {
                                $panelelement->DispLabelColour="White";
                            }
                            
                            $panelelement->TextRectTop=10;

                            break; // Only the one?
                        }

                        if (!isset($panelelement->PositionX))
                            $panelelement->PositionX=0;
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
        
        $filename=str_replace(
            ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
             "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-"],
            ["Pedals/",
             "OrganInstallationPackages/002201/Images/Keys/"],   
             $filename);

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
        
        foreach($hwi->getStops() as $stopid=>$stop) {
            for ($rn=1; $rn<=$stop->NumberOfRanks; $rn++) {
                $rn=$stop->int2str($rn);
                $stop->unset("Rank{$rn}PipeCount"); 
                $stop->unset("Rank{$rn}FirstPipeNumber");
                $stop->unset("Rank{$rn}FirstAccessibleKeyNumber");
            }
        }
        
        // Increase volume of key noise effects
        foreach ([93, 94, 95] as $rankid) {
            $rank=$hwi->getRank($rankid);
            foreach ($rank->Pipes() as $pipe) $pipe->Gain+=10;
        }
    }

    public static function Luins() {
        \GOClasses\Manual::$keys=58;
        \GOClasses\Manual::$pedals=32;
        
        $hwi=new Luins(sprintf(self::SOURCE, "dry"));
        self::Build($hwi, [1=>"Near"]);
        $hwi->saveODF(sprintf(self::TARGET, "dry"), self::COMMENTS);

        $hwi=new Luins(sprintf(self::SOURCE, "wet"));
        self::Build($hwi, [1=>"Far"]);
        $hwi->saveODF(sprintf(self::TARGET, "wet"), self::COMMENTS);

        $hwi=new Luins(sprintf(self::SOURCE, "surround "));
        self::Build($hwi, [1=>"Near", 2=>"Far", 3=>"Rear"]);
        $hwi->saveODF(sprintf(self::TARGET, "surround"), self::COMMENTS);
    }
    
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");

Luins::Luins();