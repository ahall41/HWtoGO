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
 * Import Rieger Organ of the Name of Mary Church from Novi Sad (Serbia) to GrandOrgue
 * 
 * @author andrew
 */
class NoviSad extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/NoviSad/";
    const ODF="Novi Sad surround_demo.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Rieger Organ of the Name of Mary Church from Novi Sad (Serbia) (" . self::ODF . ")\n"
            . "https://hauptwerk-augustine.info/Novi_Sad.php\n"
            . "\n";
    const TARGET=self::ROOT . "Novi Sad %s_demo.1.0.organ";

    //protected int $releaseCrossfadeLengthMs=-1;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1, "Name"=>"Original"],
        2=>["SetID"=>2, "Name"=>"Extended"],
    ];
    
    protected $patchEnclosures=[
        220=>[                     "Name"=>"Swell", "GroupIDs"=>[301,302,303], "InstanceID"=>23],
         11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[102,202,302], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
         12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[101,201,301], "InstanceID"=>85133, "AmpMinimumLevel"=>1],
         13=>["EnclosureID"=>"13", "Name"=>"Rear",  "GroupIDs"=>[103,203,303], "InstanceID"=>85134, "AmpMinimumLevel"=>1],
         17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700],         "InstanceID"=>85135, "AmpMinimumLevel"=>1],
    ];
    
    protected $patchTremulants=[
        1720=>["Type"=>"Synth", "GroupIDs"=>[301,302,303]]
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
    
    protected $patchStopRanks=[
          2=>["MIDINoteNumOfFirstMappedDivisionInputNode"=>NULL],
         35=>["MIDINoteNumOfFirstMappedDivisionInputNode"=>NULL],
         68=>["MIDINoteNumOfFirstMappedDivisionInputNode"=>NULL],
         32=>["MIDINoteNumIncrementFromDivisionToRank"=>NULL, 
              "MIDINoteNumOfFirstMappedDivisionInputNode"=>48],
         65=>["MIDINoteNumIncrementFromDivisionToRank"=>NULL, 
              "MIDINoteNumOfFirstMappedDivisionInputNode"=>48],
         98=>["MIDINoteNumIncrementFromDivisionToRank"=>NULL, 
              "MIDINoteNumOfFirstMappedDivisionInputNode"=>48]
    ];

    protected $patchKeyImageSets=[
        1=>["ManualID"=>2, "PositionX"=>504, "PositionY"=>426],
        2=>["ManualID"=>3, "PositionX"=>514, "PositionY"=>342],
        3=>["ManualID"=>1, "PositionX"=>484, "PositionY"=>630],
    ];

    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        foreach([1,2] as $panelid) {
            $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
            $this->configureEnclosureImage($panelelement, $data);
            $data["InstanceID"]++;
            $panelelement->DispLabelText="";
            if ($data["EnclosureID"]<100) break;
        }
    }

    protected function correctFileName(string $filename): string {
        return str_replace(
                ["/images/", "/keyboard/", "/SwitchNoise/", "/Stopnoise/"], 
                ["/Images/", "/Keyboard/", "/SwitchNoise/", "/stopnoise/"], $filename);
    }

    /**
     * Run the import
     */
    public static function NoviSad(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new NoviSad(self::SOURCE);
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
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::NoviSad(
                    [1=>"Far", 2=>"Near", 3=>"Rear"],
                    "surround");
        }
    }   
}
NoviSad::NoviSad();