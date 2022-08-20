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
 * Import Grenzing Organ from Geneva to GrandOrgue
 * 
 * NB. The HW Model has an internal SWELL keyboard to handle the split coupler,
 *     which has taken some "adjusting"!
 * 
 * @author andrew
 */
class Geneva extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/Geneva/";
    const ODF="Grenzing-Geneva four-channels.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Grenzing Organ from Geneva\n"
            . "https://hauptwerk-augustine.info/Grenzing_Geneva.php\n"
            . "\n";
    const TARGET=self::ROOT . "Grenzing_Geneva_surround.1.0.organ";

    protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchDivisions=[
        3=>["Name"=>"POSITIVE"],
        4=>"DELETE"
    ];
    
    protected $patchEnclosures=[
         11=>["EnclosureID"=>"11", "Name"=>"Dry",   "GroupIDs"=>[101,201,301], "InstanceIDs"=>[1=>85132], "AmpMinimumLevel"=>0],
         12=>["EnclosureID"=>"12", "Name"=>"Wet",   "GroupIDs"=>[102,202,302], "InstanceIDs"=>[1=>85133], "AmpMinimumLevel"=>0],
    ];
    
    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,203]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]],
    ];
    
    protected $patchStops=[
        +1=>["ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      1006=>"DELETE", // Coupler
      1011=>"DELETE", // Coupler
      1016=>"DELETE", // Coupler
      1111=>"DELETE", // Coupler
      1116=>"DELETE", // Coupler
      1216=>"DELETE", // Coupler
      1710=>"DELETE", // Tremulant 
      1720=>"DELETE", // Tremulant 
      2117=>"DELETE", // Cornet C# 
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
      2691=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>101], // Rossignol dry
      2692=>["DivisionID"=>1, "Engaged"=>"N", "Ambient"=>TRUE, "GroupID"=>102, "SwitchID"=>10187], // Rossignol wet
    ];

    protected $patchRanks=[
        17=>"DELETE", // Cornet C# dry
       117=>"DELETE", // Cornet C# wet
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        94=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[+2]],
        95=>["Noise"=>"Ambient",    "GroupID"=>101, "StopIDs"=>[2691]],
        96=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+3]],
        97=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[+4]],
       195=>["Noise"=>"Ambient",    "GroupID"=>102, "StopIDs"=>[2692]],
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if (in_array($hwdata["KeyboardID"], [1,2,3]))
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        if (in_array($hwdata["ConditionSwitchID"],[10195,10201])) return NULL;
        if ($hwdata["DestDivisionID"]==4) $hwdata["DestDivisionID"]=3;
        return parent::createCoupler($hwdata);
    }

    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["DivisionID"]==4) $hwdata["DivisionID"]=3;
        return parent::createStop($hwdata);
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        $rankid=$hwdata["RankID"];
        $pos=[];
        if (!isset($hwdata["StopIDs"])) {
            $hwdata["StopIDs"]=[];
            foreach ($this->hwdata->rankStop($rankid) as $rankstop) {
                $stopid=$rankstop["StopID"];
                if ($stopid>2300) {
                    $stopid-=(2327-2219); // POS->SWELL
                    $pos[]=$stopid;
                }
                $hwdata["StopIDs"][]=$stopid;
                $division=$this->hwdata->stop($stopid)["DivisionID"];
                $hwdata["GroupID"]=($division*100) +1 +intval($rankid/100);
            }
        }
        $rank= \Import\Configure::createRank($hwdata);
        foreach($pos as $stopid) {
            $stop=$this->getStop($stopid);
            if ($stop) {
                $rankno=$stop->int2str($stop->NumberOfRanks);
                $stop->set("Rank${rankno}FirstAccessibleKeyNumber", 26);
            }
        }
        return $rank;
    }

    public function createSwitchNoise(string $type, array $switchdata): void {
        if (isset($switchdata["DivisionID"]) 
            && $switchdata["DivisionID"]==4) return;
        if (isset($switchdata["DestDivisionID"]) 
            && $switchdata["DestDivisionID"]==4) return;
        parent::createSwitchNoise($type, $switchdata);
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
                ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-"],
                ["OrganInstallationPackages/001764/Images/Pedals/"],
                $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    protected function isNoiseSample(array $hwdata): bool {
        if (in_array($hwdata["RankID"], [95,195])) // Rossignol
            return TRUE;
        if (in_array($hwdata["RankID"], [17,117])) // C# Cornet
            return FALSE;
        return parent::isNoiseSample($hwdata);
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $pipe=$this->pipePitchMidi($hwdata);
        $sample=$this->samplePitchMidi($hwdata);
        if ((($hwdata["RankID"] % 100)<=5) && $pipe>65) return NULL; // Pedal
        if (in_array($hwdata["RankID"], [17,117]))      return NULL; // C# Cornet
        if ($pipe>91) return NULL; // Cornet in particular
        return parent::processSample($hwdata, $isattack);
    }

    public function xxprocessNoise(array $hwdata, $isattack): ?\GOClasses\Noise {
        if (!in_array($hwdata["RankID"], [95,195])) {
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
        }
        return NULL;
    }
    
    /**
     * Run the import
     */
    public static function Geneva(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=58;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new Geneva(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("sur new", $target, $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            foreach($hwi->getStops() as $id=>$stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
                unset($stop->Rank004PipeCount);
                unset($stop->Rank005PipeCount);
                unset($stop->Rank006PipeCount);
                if ($id==2116) {
                    $stop->Rank001FirstAccessibleKeyNumber=25;
                    $stop->Rank002FirstAccessibleKeyNumber=25;
                }
            }

            $hwi->getManual(3)->Name="POSITIVE";
            
            // Patch Grt/Pos split couplers
            $int=$hwi->getCoupler(10197);
            $int->FirstMIDINoteNumber=36;
            $int->NumberOfKeys=25;
            $sup=$hwi->getCoupler(10199);
            $int->FirstMIDINoteNumber=36+25;
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::Geneva(
                    [1=>"Dry", 2=>"Wet"],
                    "surround");
        }
    }   
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
Geneva::Geneva();