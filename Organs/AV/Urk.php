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
 * Import Dam & Zonen Organ from Urk - Kerkje aan de Zee (Netherlands) to GrandOrgue
 * 
 * @author andrew
 */
class Urk extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Urk/";
    const ODF="Urk - Kerk aan de Zee-surround.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Dam & Zonen Organ from Urk - Kerkje aan de Zee (Netherlands) (" . self::ODF . ")\n"
            . "https://hauptwerk-augustine.info/Urk.php\n"
            . "\n"
            . "1.1 Corrected volume controls\n"
            . "\n";
    const TARGET=self::ROOT . "Urk - Kerk aan de Zee %s.1.1.organ";
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchDivisions=[
        3=>"DELETE"
    ];
    
    protected $patchKeyActions=[
        9=>"DELETE"
    ];
    
    protected $patchEnclosures=[
        11=>["EnclosureID"=>"11", "Name"=>"Near",  "GroupIDs"=>[101,201], "InstanceID"=>85133, "AmpMinimumLevel"=>1],
        12=>["EnclosureID"=>"12", "Name"=>"Far",   "GroupIDs"=>[102,202], "InstanceID"=>85132, "AmpMinimumLevel"=>1],
        17=>["EnclosureID"=>"17", "Name"=>"Noises","GroupIDs"=>[700], "InstanceID"=>85135, "AmpMinimumLevel"=>1],
        
    ];

    protected $patchRanks=[
        91=>["Noise"=>"Ambient",    "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",     "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"StopOff",    "GroupID"=>700, "StopIDs"=>[]],
        94=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+2]],
        95=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-2]],
        98=>["Noise"=>"KeyOn",      "GroupID"=>700, "StopIDs"=>[+1]],
        99=>["Noise"=>"KeyOff",     "GroupID"=>700, "StopIDs"=>[-1]],
    ];
    
    protected $patchStops=[
      +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      +3=>"DELETE",
      -1=>["StopID"=>-1, "DivisionID"=>1, "Name"=>"DivisionKeyAction_01 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      -2=>["StopID"=>-2, "DivisionID"=>2, "Name"=>"DivisionKeyAction_02 Off",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    1111=>"DELETE",
    2212=>["DivisionID"=>2], // Bourdon16 Desc
    2218=>["DivisionID"=>2], // Cornet Desc
    2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    protected $patchTremulants=[
        1710=>["Type"=>"Synth", "GroupIDs"=>[201,202,301,302]]
    ];
    
    protected $patchKeyImageSets=[
        1=>["ManualID"=>2, "PositionX"=>471, "PositionY"=>455],
        2=>["ManualID"=>1, "PositionX"=>540, "PositionY"=>610],
    ];

    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<3)
            return parent::createManual($hwdata);
        else
            return NULL;
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        unset($data["SwitchID"]);
        $panelelement=$this->getPanel(1)->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->DispLabelText="";
    }

    protected function correctFileName(string $filename): string {
        return str_replace(
                ["/images/", "/keyboard", "/Stops/", "/Stop noise/", "/Pedal/off/", "/Blower/"], 
                ["/Images/", "/Keyboard", "/stops/", "/Stop Noise/", "/Pedal/Off/", "/blower/"], $filename);
    }

    /**
     * Run the import
     */
    public static function Urk(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new Urk(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("surround", "($target)", $hwi->getOrgan()->ChurchName);
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
            self::Urk(
                    [1=>"Far", 2=>"Near"], "Surround");
            self::Urk(
                    [1=>"Far"], "Wet");
            self::Urk(
                    [2=>"Near"], "Dry");
        }
    }   
}
Urk::Urk();