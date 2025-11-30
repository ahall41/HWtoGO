<?php 

/**
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 * @todo: Crescendo, Tremulants, Swl/Pos Unison Off 
 * 
 */

namespace Organs\CP;
require_once(__DIR__ . "/CPOrgan.php");

/**
 * Import Schlicker, 1967 of Clarendon United Methodist Church, Arlington to GrandOrgue
 * 
 * @author andrew
 */
class ClarendonUMC extends CPOrgan {
    
    const ROOT="/GrandOrgue/Organs/CP/ClarendonUMC/";
    const COMMENTS=
              "Schlicker, 1967, Clarendon United Methodist Church, Arlington, MD\n"
            . "https://coralpipesorgan.wixsite.com/coral-pipes/clarendon-umc\n"
            . "\n";

    protected $patchDisplayPages=[
        
        1=>[ // Console
            0=>["SetID"=>1000]
           ],
        2=>[ // Center
            0=>["SetID"=>1001]
           ],
        3=>[
            0=>["Name"=>"Landscape", "Group"=>"Left", "SetID"=>1002], 
            1=>["Name"=>"Portrait", "Group"=>"Left", "SetID"=>1003], 
           ],
        4=>[
            0=>["Name"=>"Landscape", "Group"=>"Right", "SetID"=>1004], 
            1=>["Name"=>"Portrait", "Group"=>"Right", "SetID"=>1005], 
           ],
        5=>[
            0=>["Name"=>"Landscape", "Group"=>"Simple", "SetID"=>1006], 
            1=>["Name"=>"Portrait", "Group"=>"Simple", "SetID"=>1007], 
           ],
        6=>"DELETE",
        9=>[ // Pistons
            0=>["SetID"=>1013]
           ],
        10=>[ // Blower
            0=>["SetID"=>1014]
           ],
        15=>[ 
            0=>["Name"=>"Landscape", "Group"=>"Info", "SetID"=>1019], 
            1=>["Name"=>"Portrait", "Group"=>"Info", "SetID"=>1020], 
           ],
    ];
    
    protected $patchEnclosures=[
        220=>["GroupIDs"=>[4], "AmpMinimumLevel"=>30],
    ];
    
    protected $patchTremulants=[
        1710=>"DELETE",
        1720=>["Type"=>"Synth", "GroupIDs"=>[3], "Period"=>250, "AmpModDepth"=>18],
        1730=>["Type"=>"Synth", "GroupIDs"=>[4], "Period"=>220, "AmpModDepth"=>15],
        1740=>"DELETE"
    ];
    
    protected $patchStops=[
      2402=>["Ambient"=>TRUE, "DivisionID"=>1, "GroupID"=>5]
    ];

    // Inspection of Ranks object
    protected $patchRanks=[
    ];
    
    public function createManuals(array $keyboards): void {
        foreach([1,2,3,4] as $id) {
            $manual=parent::createManual($keyboards[$id]);
            unset($manual->PositionX);
            unset($manual->PositionY);
            $manual->Displayed="N";
            $manual->NumberOfAccessibleKeys=$manual->NumberOfLogicalKeys;
        }
        $manual=parent::createManual($keyboards[5]);
        unset($manual->PositionX);
        unset($manual->PositionY);
        $manual->Displayed="N";
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

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        $condSwitchID=isset($data["ConditionSwitchID"]) ? $data["ConditionSwitchID"] : 0;
        switch($condSwitchID) {
            case 10298: // Antiphonal 
            case 10301:
            case 10304:
            case 10307:
                return;
        }
        parent::configurePanelSwitchImages($switch, $data);
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
                case 2110: // Grt: Blockfloete 2
                    $stop->Rank001FirstPipeNumber=1;
                    break;
                
                case 2112: // Grt: Trumpet 8
                    $stop->Rank001FirstPipeNumber=13;
                    break;
                
                case 2114: // Grt: Chimes
                    unset($stop->Rank001PipeCount);
                    $stop->Rank001FirstAccessibleKeyNumber=22;
                    break;
                    
                case 2305: // Swl: Viole Celeste 8 (TC)
                case 2307: // Swl: Dolce Celeste 8 (TC)
                case 2311: // Swl: Sesquialtera II (TC)
                    unset($stop->Rank001PipeCount);
                    $stop->FirstAccessiblePipeLogicalKeyNumber=13;
                    break;
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
    public static function ClarendonUMC(ClarendonUMC $hwi, string $target) {
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

class ClarendonUMCDemo extends ClarendonUMC {
    const ODF="Clarendon United Methodist Church (Demo).Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Clarendon United Methodist Church (Demo).1.0.organ";
    
    // protected $usedstops=[1,2,3,4,-1,-2,-3,-4,2002,2004,2005,2103,2105,2105,2201,2205,2209,2302,2303,2601,1720];

    static function Demo() {
        self::ClarendonUMC(new ClarendonUMCDemo(self::SOURCE), self::TARGET);
    }
}

class ClarendonUMCFull extends ClarendonUMC {
    const ODF="Clarendon United Methodist Church.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const TARGET=self::ROOT . "Clarendon United Methodist.1.0.organ";

    static function Full () {
        self::ClarendonUMC(new ClarendonUMCFull(self::SOURCE), self::TARGET);
    }
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\CP\ErrorHandler");

ClarendonUMCFull::Full();
ClarendonUMCDemo::Demo();