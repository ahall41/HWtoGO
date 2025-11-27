<?php 

/**
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 * @todo: Tuba, Tremulants 
 * 
 */

namespace Organs\CP;
require_once(__DIR__ . "/CPOrgan.php");

/**
 * Import Di Gennaro-Hart, 2007 of The Episcopal Church of the Redeemer, Bethesda to GrandOrgue
 * 
 * @author andrew
 */
class BethesdaRedeemer extends CPOrgan {
    
    const ROOT="/GrandOrgue/Organs/CP/BethesdaRedeemer/";
    const COMMENTS=
              "Di Gennaro-Hart, 2007, The Episcopal Church of the Redeemer, Bethesda MD\n"
            . "https://coralpipesorgan.wixsite.com/coral-pipes/bethesda\n"
            . "\n";

    protected $patchDisplayPages=[
        
        1=>[ // Console
            0=>["SetID"=>255]
           ],
        2=>[
            0=>["Name"=>"Landscape", "Group"=>"Left", "SetID"=>1], 
            1=>["Name"=>"Portrait", "Group"=>"Left", "SetID"=>2], 
           ],
        3=>[
            0=>["Name"=>"Landscape", "Group"=>"Right", "SetID"=>3], 
            1=>["Name"=>"Portrait", "Group"=>"Right", "SetID"=>4], 
           ],
        4=>[ // Simple
            0=>["SetID"=>5], 
           ],
        5=>[ // Pistons
            0=>["SetID"=>256]
           ],
        6=>[ // Blower
            0=>["SetID"=>6]
           ],
        7=>[ 
            0=>["Name"=>"Landscape", "Group"=>"Info", "SetID"=>257], 
            1=>["Name"=>"Portrait", "Group"=>"Info", "SetID"=>258], 
           ],
    ];
    
    protected $patchEnclosures=[
        210=>["GroupIDs"=>[2], "AmpMinimumLevel"=>30],
        220=>["GroupIDs"=>[3], "AmpMinimumLevel"=>30],
    ];
    
    protected $patchTremulants=[
        2218=>["TremulantID"=>2218, "ControllingSwitchID"=>2218, "Type"=>"Switched", "Name"=>"Swl Tremulant", "DivisionID"=>3],
        2121=>["TremulantID"=>2121, "ControllingSwitchID"=>2121, "Type"=>"Switched", "Name"=>"Grt Tremulant", "DivisionID"=>2],
    ];
    
    protected $patchStops=[
        2218=>["Ambient"=>TRUE, "DivisionID"=>1, "GroupID"=>1], // Swl Tremulant
        2121=>["Ambient"=>TRUE, "DivisionID"=>1, "GroupID"=>1], // Grt Tremulant
    ];
    
    // Inspection of Ranks object
    protected $patchRanks=[
        1=>["Noise"=>"Ambient", "GroupID"=>1, "StopIDs"=>[2121]], // Grt Tremulant
        2=>"DELETE", // North Blower
        3=>"DELETE", // Organ Current
        4=>"DELETE", // South Blower
        5=>["Noise"=>"Ambient", "GroupID"=>1, "StopIDs"=>[2218]], // Swl Tremulant
        6=>"DELETE", // Zymbelstern
    ];
    
    public function createManuals(array $keyboards): void {
        foreach([1,2,3] as $id) {
            $manual=parent::createManual($keyboards[$id]);
            unset($manual->PositionX);
            unset($manual->PositionY);
            $manual->Displayed="N";
            $manual->NumberOfAccessibleKeys=$manual->NumberOfLogicalKeys;
        }
        foreach([8,9,10] as $id) {
            $manual=parent::createManual($keyboards[$id]);
            unset($manual->PositionX);
            unset($manual->PositionY);
            $manual->Displayed="N";
        }
    }
    
    public function configureKeyboardKeys(array $keyboardKeys) : void {
        foreach($this->GetPanels() as $panel) {
            $panel->HasPedals="N";
        }
        $panel=\Import\Configure::createPanel(["PageID"=>0, "Name"=>"Keyboards"]);
        $panel->HasPedals="Y";
        foreach($this->getManuals() as $id=>$manual) {
            if ($id<5) {
                $panel->GUIElement($manual);
            }
        }
        return;
    }

    public function import() : void {
        /* Uncomment to determine image instances on each page ...
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            if (!isset($instance["ImageSetInstanceID"])) continue;
            switch ($instance["DisplayPageID"]) {
                case 2:
                    echo ($instanceID=$instance["ImageSetInstanceID"]), "\t",
                         isset($instance["AlternateScreenLayout1_ImageSetID"]) ? 1 : "", "\t",
                         isset($instance["AlternateScreenLayout2_ImageSetID"]) ? 2 : "", "\t",
                         $instance["Name"], ": ";
                    foreach ($this->hwdata->switches() as $switch) {
                        if (isset($switch["Disp_ImageSetInstanceID"])  && 
                               $switch["Disp_ImageSetInstanceID"]==$instanceID)
                            echo $switch["SwitchID"], " ",
                                 $switch["Name"], ", ";
                    }
                    echo "\n";
            }
        } 
        exit(); //*/
        parent::import();
        
        foreach($this->getStops() as $stopid=>$stop) {
            // echo $stopid, ": ", $stop->Name, "\n";
            switch ($stopid) {
                case 2014: // Ped: Chimes
                    $stop->Rank001FirstPipeNumber=1;
                    break;

                case 2111: // Seventeenth 1 3/5
                    unset($stop->Rank001FirstAccessibleKeyNumber);
                    unset($stop->Rank001PipeCount);
                    break;

                case 2103: // Grt: Violes Celestes II
                    $stop->Rank001FirstPipeNumber=8;
                    break;
                
                case 2220: // Swl: Larigot 1 1/3
                    unset($stop->Rank001PipeCount);
                    break;
            }
        }
        
        foreach ($this->getRanks() as $rankid=>$rank) {
            switch ($rankid) {
                case 17: // Grt: Seventeenth 1 3/5
                    $rankxv=$this->getRank(15);
                    for ($m=36; $m<93; $m++) {
                        $rank->Pipe($m, $rankxv->Pipe($m+4));
                    }
                    //echo $rank; exit();
            }
        }
    }
    
    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        $panel=$this->getPanel(0);
        $panel->GUIElement($enclosure);
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
    
    /**
     * Run the import
     */
    public static function BethesdaRedeemer(BethesdaRedeemer $hwi, string $target) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;

        $hwi->root=self::ROOT;
        $hwi->import();
        
        unset($hwi->getOrgan()->InfoFilename);
        echo $hwi->getOrgan()->ChurchName, "\n";
        
        $hwi->saveODF($target, self::COMMENTS);
    }   
}

class BethesdaRedeemerDemo extends BethesdaRedeemer {
    const ODF="Bethesda Redeemer Di Gennaro-Hart Organ (Demo).Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Bethesda Redeemer Di Gennaro-Hart Organ (Demo).1.0.organ";
    
    static function Demo() {
        self::BethesdaRedeemer(new BethesdaRedeemerDemo(self::SOURCE), self::TARGET);
    }

}

class BethesdaRedeemerFull extends BethesdaRedeemer {
    const ODF="Bethesda Redeemer Di Gennaro-Hart Organ.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Bethesda Redeemer Di Gennaro-Hart Organ.1.0.organ";

    static function Full () {
        self::BethesdaRedeemer(new BethesdaRedeemerFull(self::SOURCE), self::TARGET);
    }
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\CP\ErrorHandler");

BethesdaRedeemerFull::Full();
BethesdaRedeemerDemo::Demo();