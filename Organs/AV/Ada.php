<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\AV;
require_once __DIR__ . "/AVOrgan.php";

/**
 * Import POM Lutheran's Organ from the Buda Hills Area to GrandOrgue
 * 
 * @author andrew
 */
class Ada extends AVOrgan{
    const ROOT="/GrandOrgue/Organs/AV/Ada/";
    const ODF="Ada demo surround.Organ_Hauptwerk_xml";
    const COMMENTS=
              "Romantic Organ from Ada (Serbia-Vojvodina) (" . self::ODF . ")\n"
            . "https://hauptwerk-augustine.info/Ada.php\n"
            . "\n"
            . "1.1 Removed cross fades\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Ada demo %s 1.1.organ";

    protected $patchDisplayPages=[
        1=>["SetID"=>1],
        2=>["SetID"=>2],
    ];
    
    protected $patchEnclosures=[
        210=>[                     "PanelID"=>1, "Name"=>"Great", "GroupIDs"=>[201,202,203], "InstanceID"=>43],
        220=>[                     "PanelID"=>1, "Name"=>"Swell", "GroupIDs"=>[301,302,303], "InstanceID"=>45],
         11=>["EnclosureID"=>"11", "PanelID"=>2, "Name"=>"Near",  "GroupIDs"=>[101,201,301], "InstanceID"=>85132, "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "PanelID"=>2, "Name"=>"Far",   "GroupIDs"=>[102,202,302], "InstanceID"=>85133, "AmpMinimumLevel"=>0],
         13=>["EnclosureID"=>"13", "PanelID"=>2, "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceID"=>85134, "AmpMinimumLevel"=>0],
    ];

    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]]
    ];

    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];
    
    protected $patchRanks=[
         23=>["PitchTuning"=>10],
        123=>["PitchTuning"=>10],
        223=>["PitchTuning"=>10],
         91=>["RankID"=>91, "Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
         92=>["RankID"=>92, "Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
         93=>["RankID"=>93, "Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[2]],
         94=>["RankID"=>94, "Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[1]],
         95=>["RankID"=>95, "Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[3]],
    ];
    
    public function import(): void {
        parent::import();
        $vc=$this->getStop(2223);
        for ($n=1; $n<=$vc->NumberOfRanks; $n++)
            $vc->set("Rank00${n}FirstAccessibleKeyNumber",13);
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        $panelelement=$this->getPanel($data["PanelID"])->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->DispLabelText="";
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
                $style=$this->hwdata->textStyle($textInstance["TextStyleID"]);
                if (isset($style["Font_SizePixels"]))
                    $panelelement->DispLabelFontSize=$style["Font_SizePixels"];

                if ($style["Colour_Blue"]>100) 
                    $panelelement->DispLabelColour="Dark Blue";
                elseif($style["Colour_Red"]>100) 
                    $panelelement->DispLabelColour="Dark Red";
                else
                    $panelelement->DispLabelColour="Black";
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
        
        $filename=str_replace("//","/",$filename);
        $filename=str_replace(
                "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
                "OrganInstallationPackages/002374/images/pedals/",
                $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (isset($hwdata["Noise"])) $hwdata["Name"]="Special: " . $hwdata["Noise"];
        return parent::createRank($hwdata, $keynoise);
    }

    private function configurePedalKeyImages() {
        $ped=$this->getManual(1);
        $ped->PositionX=325;
        $ped->PositionY=600;
        $dir="OrganInstallationPackages/002374/images/pedals/";
        $keyImages=[
            "NaturalWithSharpToTheRight"=>[0,2,5,7,9], // 
            "NaturalWithNoSharpToTheRight"=>[4,11],
            "Sharp"=>[1,3,6,8,10],
        ];
        for ($k=0; $k<30; $k++) {
            $key="Key" . $ped->int2str($k+1);
            foreach($keyImages as $image=>$keys) {
                if (in_array($k % 12, $keys)) {
                    $ped->set("${key}ImageOn",  "${dir}${image}Down.bmp");
                    $ped->set("${key}ImageOff", "${dir}${image}Up.bmp");
                    $ped->set("${key}Width", 12);
                    $ped->set("${key}MouseRectWidth", 12);
                }
            }
        }
        $ped->Key030ImageOn="${dir}NaturalWithNoSharpToTheRightDown.bmp";
        $ped->Key030ImageOff="${dir}NaturalWithNoSharpToTheRightUp.bmp";
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
    
    public function xconfigureKeyImages(array $keyImageSets, array $keyboards) : void {
        $this->configurePedalKeyImages();
        foreach ([2=>480,3=>420] as $manid=>$posy) {
            $manual=$this->getManual($manid);
            $this->configureKeyImage($manual, $keyImageSets[1]);
            $manual->PositionX=349;
            $manual->PositionY=$posy;
            $manual->Displayed="Y";
        }
    }
    
    public function processNoise(array $hwdata, $isattack): ?\GOClasses\Noise {
        $type=$this->hwdata->rank($hwdata["RankID"])["Noise"];
        if ($type=="Ambient") 
            return parent::processNoise($hwdata, $isattack);
        else {
            $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
            foreach($this->getSwitchNoises() as $id=>$stop)
                $this->configureAttack($hwdata, $stop->Noise());
        }
        return NULL;
    }
    
    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $st=$this->sampleTuning($hwdata);
        if (abs($st)<=1200)
            return parent::processSample($hwdata, $isattack);
        else
            return NULL;
    }
    
    /**
     * Run the import
     */
    public static function Ada(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new Ada(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            foreach($hwi->getManuals() as $manual) {
                unset($manual->DisplayKeys);
            }
            $hwi->getOrgan()->ChurchName=str_replace("surround", $target, $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Ada(
                    [1=>"Near", 2=>"Far", 3=>"Rear"],
                    "surround");
        }
    }   
}
Ada::Ada();