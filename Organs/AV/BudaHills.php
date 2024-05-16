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
class BudaHills extends AVOrgan{
    const ROOT="/GrandOrgue/Organs/AV/BudaHills/";
    const ODF="Lutheran Buda_surround.Organ_Hauptwerk_xml";
    const COMMENTS=
              "POM Lutheran's Organ from the Buda Hills Area (" . self::ODF . ")\n"
            . "https://hauptwerk-augustine.info/Pom_Buda.php\n"
            . "\n"
            . "Version 1.1 Correct reed tuning and organ gain\n"
            . "Version 1.2 Add key action noises\n"
            . "Version 1.3 Removed unused pipes and cross fades\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Lutheran Buda_%s.1.3.organ";

    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"Swell", "GroupIDs"=>[301,302,303], "InstanceID"=>41],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[102,202,302], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[101,201,301], "InstanceID"=>85133, "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceID"=>85134, "AmpMinimumLevel"=>1],
         17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700],         "InstanceID"=>85135, "AmpMinimumLevel"=>1],
    ];

    protected $patchTremulants=[
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]]
    ];

    protected $patchStops=[
         1=>["Name"=>"Key Action 1", "DivisionID"=>1, "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
         2=>["Name"=>"Key Action 2", "DivisionID"=>2, "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
         3=>["Name"=>"Key Action 3", "DivisionID"=>3, "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2005=>["ControllingSwitchID"=>10142], // Plenum 
      2110=>["ControllingSwitchID"=>10142], // Assumed all on same switch ???      
      2218=>["ControllingSwitchID"=>10142],       
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];
    
    protected $patchRanks=[
         91=>["RankID"=>91, "Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
         92=>["RankID"=>92, "Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
         93=>["RankID"=>93, "Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[1,2,3]],
          3=>["PitchTuning"=>100],
         16=>["PitchTuning"=>100],
        103=>["PitchTuning"=>100],
        116=>["PitchTuning"=>100],
        203=>["PitchTuning"=>100],
        216=>["PitchTuning"=>100],
    ];
    
    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        $panelelement=$this->getPanel(1)->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->DispLabelText="";
    }

    protected function correctFileName(string $filename): string {
        return str_replace(
                ["/images/",  "/stops/"], 
                ["/Images/",  "/Stops/"], $filename);
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
        $ped->PositionY=580;
        $dir="OrganInstallationPackages/001744/Images/pedals/";
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
        $this->configurePedalKeyImages();
        $keyImageset=$keyImageSets[1];
        foreach ([2=>470,3=>410] as $manid=>$posy) {
            $manual=$this->getManual($manid);
            $keyImageset["ManualID"]=$manid;
            $this->configureKeyImage($manual, $keyImageset);
            $manual->PositionX=350;
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
        $midi=isset($hwdata["NormalMIDINoteNumber"]) ? $hwdata["NormalMIDINoteNumber"] : 60;
        $rankid=$hwdata["RankID"] % 100;
        if ($rankid<5 && $midi>67) {return NULL;}
        if ($midi>96) {return NULL;}
        
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe && isset($hwdata["NormalMIDINoteNumber"])) {
            $key=$hwdata["NormalMIDINoteNumber"];
            if ($key>91)
                $pipe->PitchTuning=($key-91)*100; // Extended pipes
            if ($key==36 && in_array($hwdata["RankID"], [4,104,204]))
                $pipe->PitchTuning=35;
        }
      
        return $pipe;
    }
    
    /**
     * Run the import
     */
    public static function BudaHills(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new BudaHills(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("surround", $target, $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::BudaHills(
                    [1=>"Far", 2=>"Near", 3=>"Rear"],
                    "surround");
            /* self::BudaHills(
                    [1=>"Far"],
                    "wet");
            self::BudaHills(
                    [2=>"Near"],
                    "dry"); */
        }
    }   
}
BudaHills::BudaHills();