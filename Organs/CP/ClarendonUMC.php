<?php 

/**
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 * @todo: Crescendo, Unison Off, Blower
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
        6=>"DELETE", // Orig Center Jamb
        9=>"DELETE", /* // Pistons
            0=>["SetID"=>1013]
           ], */
        10=>"DELETE", /*[ // Blower
            0=>["SetID"=>1014]
           ], */
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
        1720=>"DELETE",
        1730=>"DELETE",
        1740=>"DELETE",
        2299=>["TremulantID"=>2299, "ControllingSwitchID"=>10151, "Type"=>"Switched", "Name"=>"Pos Tremulant", "DivisionID"=>3]
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

    public function import() : void {
        parent::import();
        $trem=$this->createTremulant(
                ["TremulantID"=>1730, 
                 "Name"=>"Tremolo: Swell", 
                 "Type"=>"Synth",
                 "ControllingSwitchID"=>10310,
                 "GroupIDs"=>[4]]);
        
        foreach($this->getStops() as $stopid=>$stop) {
            // echo $stopid, ": ", $stop->Name, "\n";
            switch (abs($stopid)) {
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
        
        foreach ($this->getRanks() as $rankid=>$rank) {
            switch ($rankid) {
                case 41: // Pos: Principal 4
                case 42: // Pos: Principal 4 trem
                    // Move pipes up by 3 places
                    for ($pipeid=96; $pipeid>38; $pipeid--) {
                        $rank->Pipe($pipeid+3, $rank->Pipe($pipeid));
                        $rank->Pipe($pipeid+3)->PitchTuning-=900;
                    }
                    
                    // Now fix the bottom 3
                    switch ($rankid) {
                        case 41: 
                            $rank->Pipe(36)->Attack000="OrganInstallationPackages/002904/pos_principal_2/036-c.wav";
                            $rank->Pipe(36)->Release001="OrganInstallationPackages/002904/pos_principal_2/shortrel/036-c.wav";
                            $rank->Pipe(36)->Release002="OrganInstallationPackages/002904/pos_principal_2/longrel/036-c.wav";
                            $rank->Pipe(36)->PitchTuning=0;

                            $rank->Pipe(37)->Attack000="OrganInstallationPackages/002904/pos_principal_2/037-c#.wav";
                            $rank->Pipe(36)->Release001="OrganInstallationPackages/002904/pos_principal_2/shortrel/037-c#.wav";
                            $rank->Pipe(36)->Release002="OrganInstallationPackages/002904/pos_principal_2/longrel/037-c#.wav";
                            $rank->Pipe(37)->PitchTuning=0;

                            $rank->Pipe(38)->Attack000="OrganInstallationPackages/002904/pos_principal_2/038-d.wav";
                            $rank->Pipe(36)->Release001="OrganInstallationPackages/002904/pos_principal_2/shortrel/038-d.wav";
                            $rank->Pipe(36)->Release002="OrganInstallationPackages/002904/pos_principal_2/longrel/038-d.wav";
                            $rank->Pipe(38)->PitchTuning=0;
                            break;

                        case 42:
                            $rank->Pipe(36)->Attack000="OrganInstallationPackages/002904/pos_principal_2/trem/036-c.wav";
                            $rank->Pipe(36)->Release001="OrganInstallationPackages/002904/pos_principal_2/trem/shortrel/036-c.wav";
                            $rank->Pipe(36)->Release002="OrganInstallationPackages/002904/pos_principal_2/trem/longrel/036-c.wav";
                            $rank->Pipe(36)->PitchTuning=0;

                            $rank->Pipe(37)->Attack000="OrganInstallationPackages/002904/pos_principal_2/trem/036-c.wav";
                            $rank->Pipe(36)->Release001="OrganInstallationPackages/002904/pos_principal_2/trem/shortrel/036-c.wav";
                            $rank->Pipe(36)->Release002="OrganInstallationPackages/002904/pos_principal_2/trem/longrel/036-c.wav";
                            $rank->Pipe(37)->PitchTuning=100;

                            $rank->Pipe(38)->Attack000="OrganInstallationPackages/002904/pos_principal_2/trem/036-c.wav";
                            $rank->Pipe(36)->Release001="OrganInstallationPackages/002904/pos_principal_2/trem/shortrel/036-c.wav";
                            $rank->Pipe(36)->Release002="OrganInstallationPackages/002904/pos_principal_2/trem/longrel/036-c.wav";
                            $rank->Pipe(38)->PitchTuning=200;
                            break;
                    }
                    
                    /* foreach ([36, 38, 39, 96] as $pipeid) {
                        printf("Pipe %d\n%s\n", $pipeid, $rank->Pipe($pipeid));
                    } */
            }
        }
        
        foreach ($this->getSwitches() as $switchid=>$switch) {
            // printf("%d %s\n", $switchid, $switch->Name);
            switch($switchid) {
                case 10244: // Ant: Antiphonal Trumpet 8
                    $switch->DefaultToEngaged="Y";
                    $switch->GCState=1;
                    break;
            }
            $switch->Name=str_replace("Coupler Coupler", "Coupler", $switch->Name);
            $switch->Name=str_replace("Stop Stop", "Stop", $switch->Name);
            $switch->Name=str_replace("Tremulant Tremulant", "Tremulant", $switch->Name);
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