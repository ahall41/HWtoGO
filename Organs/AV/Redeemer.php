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
 * Import Aeolian-Skinner Organ (Op 1132) of the Redeemer church of New Haven to GrandOrgue
 * 
 * @todo: See StopRank for key/stop action sound effects?
 * 
 * @author andrew
 */
class Redeemer extends AVOrgan {

    const ROOT="/GrandOrgue/Organs/AV/New Haven/";
    const VERSION="1.3";
    const COMMENTS=
              "Aeolian-Skinner Organ (Op 1132) of the Redeemer church of New Haven\n"
            . "https://hauptwerk-augustine.info/Aeolian-Skinner.php\n"
            . "\n"
            . "1.1 Added chimes, AmpMinDepth=1\n"
            . "1.2 Added chimes to choir enclosure\n"
            . "    Added positive to near/far enclosures\n"
            . "1.3 Updated release cross fades for #1760 (release 3.1.14)\n"
            . "    Remove Virtual Keyboards panel\n"
            . "    Add internal coupler manuals\n"
            . "\n";

    // protected ?int $releaseCrossfadeLengthMs=200;
        
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
        2=>["SetID"=>2, "Name"=>"Stops"],
        3=>["SetID"=>3, "Name"=>"Left Jamb"],
        4=>["SetID"=>4, "Name"=>"Right Jamb"],
    ];
    
    public function import() : void {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        parent::import();
        foreach($this->getManuals() as $manual) unset($manual->DisplayKeys);
        foreach($this->getStops() as $id=>$stop) {
            unset($stop->Rank001PipeCount);
            unset($stop->Rank002PipeCount);
            unset($stop->Rank003PipeCount);
            unset($stop->Rank004PipeCount);
            unset($stop->Rank005PipeCount);
            unset($stop->Rank006PipeCount);
        }   
        unset($this->getOrgan()->InfoFilename);
        foreach($this->getManuals() as $manual) unset($manual->DisplayKeys);
    }
    
    public function createManuals(array $keyboards): void {
        foreach ([1,5,2,3,4] as $kbdid) {
            $this->createManual($keyboards[$kbdid]);            
        }
    }
    
    public function createManual(array $hwdata): ?\GOClasses\Manual {
        $manual=parent::createManual($hwdata);
        switch ($kbid=$hwdata["KeyboardID"]) {
            case 2:
            case 3:
            case 5:
                $cm=$this->newManual(-$kbid, $manual->Name . " (UOFix)");
                $cm->Displayed="N";
        }
        return $manual;
    }
    
    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        $panelelement=$this->getPanel(1)->GUIElement($enclosure);
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
                $panel=$this->getPanel($instancedata["DisplayPageID"], FALSE);
                if ($panel!==NULL) {
                    $panelelement=$panel->GUIElement($switch);
                    $this->configureImage($panelelement, ["SwitchID"=>$destid]);
                    foreach($this->hwdata->textInstance($instanceid) as $textInstance) {
                        $panelelement->DispLabelText=str_replace("\n", " ", $textInstance["Text"]);
                        if (!isset($panelelement->PositionX))
                            $panelelement->PositionX=0;
                        $style=$this->hwdata->textStyle($textInstance["TextStyleID"]);

                        switch($style["Name"]) {
                            case "CustCS_stops coup":
                            case "CustCS_coupler":
                                $panelelement->DispLabelColour="Dark Blue";
                                break;

                            case "CustCS_stops reed":
                                $panelelement->DispLabelColour="Dark Red";
                                break;

                            default:
                                $panelelement->DispLabelColour="Black";
                        }

                        $panelelement->DispLabelFontSize=10;
                        break; // Only the one?
                    }
                }
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
                        }
                    }
                }
            }
        }
    }

    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        $stopid=$hwdata["StopID"];
        if ($stopid<1000 || $stopid>2000) {
           return parent::createStop($hwdata);
        }
        else {return NULL;}
    }
    
    protected function treeWalk($root, $dir="", &$results=[]) {
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
                ["OrganInstallationPackages/002384/Images/Pedals/",
                 "OrganInstallationPackages/002384/Images/Keys/"],   
                $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    public function processNoise(array $hwdata, $isattack): ?\GOClasses\Noise {
        return parent::processNoiseV2($hwdata, $isattack);
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $rank=$hwdata["RankID"] % 100; // Quintbass 10 2/3
        if ($rank==4 && isset($hwdata["NormalMIDINoteNumber"]) && $hwdata["NormalMIDINoteNumber"]<43) {
            return NULL;
        }
        return parent::processSample($hwdata, $isattack);
    }
    
}

class Extended extends Redeemer {
    const ODF="Redemeer Aeolian-Skinner_surround extend.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Redemeer Aeolian-Skinner (extended %s) %s.organ";

    protected $patchEnclosures=[
        220=>[                     "Name"=>"SW",    "GroupIDs"=>[301,302], "InstanceID"=>15],
        230=>[                     "Name"=>"PO",    "GroupIDs"=>[401,402], "InstanceID"=>17],
        240=>[                     "Name"=>"CH",    "GroupIDs"=>[501,502,9999], "InstanceID"=>19],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[101,201,301,401,501], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[102,202,302,402,502], "InstanceID"=>85134, "AmpMinimumLevel"=>1],
    ];
    
    protected $patchTremulants=[
        1710=>[/* Gt */ "Type"=>"Synth", "Period"=>220, "AmpModDepth"=>10, "StartRate"=>30, "StopRate"=>50, "GroupIDs"=>[201,202]],
        1720=>[/* Sw */ "Type"=>"Synth", "Period"=>200, "AmpModDepth"=> 8, "StartRate"=>30, "StopRate"=>50, "GroupIDs"=>[301,302]],
        1730=>[/* PO */ "Type"=>"Synth", "Period"=>220, "AmpModDepth"=>10, "StartRate"=>30, "StopRate"=>50, "GroupIDs"=>[401,402]],
        1740=>[/* Ch */ "Type"=>"Synth", "Period"=>220, "AmpModDepth"=> 8, "StartRate"=>30, "StopRate"=>50, "GroupIDs"=>[501,502]]
    ];
 
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"DivisionKeyAction_04 On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +5=>["Name"=>"DivisionKeyAction_05 On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -1=>["Name"=>"DivisionKeyAction_01 Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StopID"=>-1, "DivisionID"=>1],
        -2=>["Name"=>"DivisionKeyAction_02 Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StopID"=>-2, "DivisionID"=>2],
        -3=>["Name"=>"DivisionKeyAction_03 Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StopID"=>-3, "DivisionID"=>3],
        -4=>["Name"=>"DivisionKeyAction_04 Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StopID"=>-4, "DivisionID"=>4],
        -5=>["Name"=>"DivisionKeyAction_05 Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "StopID"=>-5, "DivisionID"=>5],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700], // Blower
    ];
    
    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
        83=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
        84=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2]],
        85=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+3]],
        86=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-3]],
        87=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+4,+5]],
        88=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-4,-5]],
        96=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        97=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
    ];
    
    /**
     * Run the import
     */
    public static function Redeemer(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new Extended(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target, self::VERSION), self::COMMENTS);
        }
        else {
            self::Redeemer([1=>"Far", 2=>"Near"], "surround");
            //self::Redeemer([1=>"Far"], "wet");
            //self::Redeemer([2=>"Near"], "dry");
        }
    }   
}

class Chimes extends Extended {
    const TARGET=self::ROOT . "Redemeer Aeolian-Skinner (chimes %s) %s.organ";

    private function addStop(int $manualid, string $label, \GOClasses\Rank $rank, array $positions) : void {
        $stop=$this->newStop(10000-$manualid, $label);
        $stop->Displayed="N";
        $switch=$this->newSwitch(10000-$manualid, $label);
        $switch->Displayed="N";
        $stop->Switch($switch);
        $stop->Rank($rank);
        $this->getManual($manualid)->Stop($stop);
        
        foreach($positions as $panelid=>$attributes) {
            $panel=$this->getPanel($panelid);
            $element=$panel->GUIElement($switch);
            $element->ImageOn="OrganInstallationPackages/002384/images/Stops/stop_in.png";
            $element->ImageOff="OrganInstallationPackages/002384/images/Stops/stop_out.png";
            $element->PositionX=$attributes[0];
            $element->PositionY=$attributes[1];
            $element->MouseRectWidth=65;
            $element->MouseRectHeight=65;
            $element->MouseRadius=0;
            $element->DispLabelText=$attributes[2];
            $element->DispLabelColour="Dark Green";
            $element->DispLabelFontSize=10;
        }
    }
    
    public function addChimes() : void {
        $chimes=new \HWClasses\HWData(getenv("HOME") . self::ROOT . "/OrganDefinitions/GhentCarillon.Organ_Hauptwerk_xml");
        $wcg=$this->newWindchestGroup(9999, "Chimes");
        $wcg->Enclosure($this->getEnclosure(240));
        
        $rank=$this->newRank(9999, "Ghent Carillon by Al Morse");
        $rank->HarmonicNumber=8;
        $rank->WindchestGroup($wcg);
        
        $this->addStop(1, "Ped. Chimes", $rank, 
                [2=>[675,  510, "Ped Chimes"],
                 3=>[ 50,  150, "Chimes"]]);
        $this->addStop(2, "Gt. Chimes", $rank, 
                [2=>[600,  510, "Gt Chimes"],
                 4=>[210,  550, "Chimes"]]);
        
        $panel=$this->getPanel(2); // Stops
        $panel->getElement(2)->PositionY=590; // Sw. Tremulant
        $panel->getElement(89)->PositionY=590; // Blower
        
        foreach($chimes->attacks() as $attack) {
            $layer=$chimes->layer($attack["LayerID"]);
            $sample=$chimes->sample($attack["SampleID"]);
            $midikey=$chimes->pipe($layer["PipeID"])["NormalMIDINoteNumber"];
            $pipe=$rank->Pipe($midikey, TRUE);
            $pipe->Percussive="Y";
            $pipe->Attack="OrganInstallationPackages/001654/" . $sample["SampleFilename"];
        }
    }
    
    public static function Redeemer(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new Chimes(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->addChimes();
            //$hwi->addVirtualKeyboards(4, [1,2,3,4], [1,2,3,4]);
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target, self::VERSION), self::COMMENTS);
        }
        else {
            self::Redeemer([1=>"Far", 2=>"Near"], "surround");
            //self::Redeemer([1=>"Far"], "wet");
            //self::Redeemer([2=>"Near"], "dry");
        }
    }   
    
}

class Original extends Redeemer {
    const ODF="Redemeer Aeolian-Skinner_surround orig.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Redemeer Aeolian-Skinner (original %s) %s.organ";

    protected $patchEnclosures=[
        220=>[                     "Name"=>"SW",    "GroupIDs"=>[301,302], "InstanceID"=>15],
        240=>[                     "Name"=>"CH",    "GroupIDs"=>[501,502], "InstanceID"=>17],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[102,202,302,402,502], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[101,201,301,401,502], "InstanceID"=>85134, "AmpMinimumLevel"=>1],
    ];

    protected $patchTremulants=[
        1720=>[/* Sw */ "Type"=>"Synth", "Period"=>200, "AmpModDepth"=> 8, "StartRate"=>30, "StopRate"=>50, "GroupIDs"=>[301,302]],
        1740=>[/* Ch */ "Type"=>"Synth", "Period"=>220, "AmpModDepth"=> 8, "StartRate"=>30, "StopRate"=>50, "GroupIDs"=>[501,502]]
    ];
    
    protected $patchStops=[
        2453=>"DELETE"
    ];
    
    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        if ($hwdata["ConditionSwitchID"]==10279) {return NULL;} // Grt 4
        return parent::createCoupler($hwdata);
    }

    public function createSwitchNoises(array $tremulants, array $keyactions, array $stopsdata): void {
        return;
    }

    /**
     * Run the import
     */
    public static function Redeemer(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new Original(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            //$hwi->addVirtualKeyboards(3, [1,2,3], [1,2,3]);
            $hwi->getOrgan()->ChurchName.=" ($target)";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            $hwi->getManual(4)->Displayed="N";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target, self::VERSION), self::COMMENTS);
        }
        else {
            self::Redeemer([1=>"Far", 2=>"Near"], "surround");
            //self::Redeemer([1=>"Far"], "wet");
            //self::Redeemer([2=>"Near"], "dry");
        }
    }   
}


function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");

Extended::Redeemer();
Original::Redeemer();
Chimes::Redeemer();