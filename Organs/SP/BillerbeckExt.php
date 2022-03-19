<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\SP;
require_once __DIR__ . "/../../Import/Organ.php";

/**
 * Create bespoke organ based on Billerbeck Dom demo from SP
 * 
 * @author andrew
 */
class BillerbeckExt extends \Import\Organ {
    const ROOT="/GrandOrgue/Organs/Billerbeck/";
    const SOURCE="OrganDefinitions/Billerbeck, Fleiter Surr.Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Billerbeck, Fleiter Surr (Extended Demo - %s) 0.3.organ";

    const RANKS_DIRECT=1;
    const RANKS_SEMI_DRY=2;
    const RANKS_DIFFUSE=3;
    const RANKS_REAR=4;
    
    public $positions=[];
    protected array $rankpositions=[
        0=>self::RANKS_SEMI_DRY, 9=>self::RANKS_SEMI_DRY,
        1=>self::RANKS_DIRECT,   7=>self::RANKS_DIRECT,
        2=>self::RANKS_DIFFUSE,  6=>self::RANKS_DIFFUSE,
        4=>self::RANKS_REAR,     8=>self::RANKS_REAR
    ];

    private $divisions=[
        0=>"Pedale",
        1=>"Grand Orgue",
        2=>"Recit",
        3=>"Positif"
    ];
    
    private $enclosures=[
        2=>"Rec",
        3=>"Pos"
    ];

    private $manuals=[ 
        0=>"Pedale", 
        1=>"Grand Orgue",
        2=>"Recit",
        3=>"Positif"
    ];

    private $tremulants=[ 
        2=>["Name"=>"Rec", "SwitchID"=>203, "Type"=>"Switched", "Position"=>[4, 6]],
        3=>["Name"=>"Pos", "SwitchID"=>204, "Type"=>"Switched", "Position"=>[8, 6]],
    ];

    private $couplers=[
        301=>["Name"=>"GO/Ped",  "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "Position"=>[9, 1]],
        302=>["Name"=>"Rec/Ped", "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "Position"=>[8, 3]],
        303=>["Name"=>"Pos/Ped", "SourceKeyboardID"=>0, "DestKeyboardID"=>3, "Position"=>[9, 3]],
        311=>["Name"=>"Rec/GO",  "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "Position"=>[4, 2]],
        312=>["Name"=>"Pos/GO",  "SourceKeyboardID"=>1, "DestKeyboardID"=>3, "Position"=>[4, 3]],
        321=>["Name"=>"Pos/Rec", "SourceKeyboardID"=>2, "DestKeyboardID"=>3, "Position"=>[5, 5]],
    ];
    
    private $stops=[
         2=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 1], "Name"=>"Principal 16"], // Ex GO
         4=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 1], "Name"=>"Principal major 8"],
         9=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 1], "Name"=>"Octave major 4"],
        13=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[4, 1], "Name"=>"Octave 2"],
         8=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 2], "Name"=>"Gedackt 8"],
        78=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 2], "Name"=>"Holzflote 4"],
         7=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 2], "Name"=>"Viola da Gamba 8"],
        15=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 3], "Name"=>"Mixture major"],
        16=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 3], "Name"=>"Mixture minor"],
        43=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 3], "Name"=>"Trompeta real 8", "Colour"=>"Dark Red"], // Ex Chamade
       
        27=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[6, 1], "Name"=>"Principalbass 16"],
       104=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 1], "Name"=>"Principal 8", "Rank"=>4], // #4
        38=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[8, 1], "Name"=>"Posaune 16", "Colour"=>"Dark Red"],
        26=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 2], "Name"=>"Bourdon 32"],
       126=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[8, 2], "Name"=>"Soubasse 16", "Rank"=>26, "FirstKey"=>13], // #26 +12
        32=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[9, 2], "Name"=>"Flute 8"],
       178=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[6, 3], "Name"=>"Holzflote 4", "Rank"=>78],
       143=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 3], "Name"=>"Trompeta 8", "Rank"=>43, "Colour"=>"Dark Red"], // #43
       
        51=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 4], "TremulantID"=>2, "Name"=>"Geigenprincipal 8"], // Ex Rec
        77=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[2, 4], "TremulantID"=>2, "Name"=>"Praestant 4"],
        60=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[3, 4], "TremulantID"=>2, "Name"=>"Octavin 2"],
        64=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[4, 4], "TremulantID"=>3, "Name"=>"Trompette harmonique 8", "Colour"=>"Dark Red"],
        55=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 5], "TremulantID"=>2, "Name"=>"Bourdon 8"],
        53=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[2, 5], "TremulantID"=>2, "Name"=>"Viola 8"],
        54=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[3, 5], "TremulantID"=>2, "Name"=>"Vox coelestis 8", "FirstNote"=>6],
        57=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[4, 5], "TremulantID"=>2, "Name"=>"Flute octaviante 4"],
        59=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 6], "TremulantID"=>2, "Name"=>"Nazard harmonique 2 2/3"],
        81=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[2, 6], "TremulantID"=>2, "Name"=>"Sesquialtera"],
        82=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[3, 6], "TremulantID"=>2, "Name"=>"Mixtur 4f."],

        46=>["ManualID"=>3, "DivisionID"=>3, "Position"=>[7, 4], "Name"=>"Flute harmonique 8"],
        74=>["ManualID"=>3, "DivisionID"=>3, "Position"=>[8, 4], "TremulantID"=>3, "Name"=>"Rohrflote 8"], // Ex Pos
        58=>["ManualID"=>3, "DivisionID"=>3, "Position"=>[7, 5], "TremulantID"=>3, "Name"=>"Fugara 4"],
        79=>["ManualID"=>3, "DivisionID"=>3, "Position"=>[8, 5], "TremulantID"=>3, "Name"=>"Waldflote 2"],
        84=>["ManualID"=>3, "DivisionID"=>3, "Position"=>[7, 6], "TremulantID"=>3, "Name"=>"Cromorne 8", "Colour"=>"Dark Red"],
    ];

    protected \HWClasses\HWData $hwdata;
    
    public function __construct($xmlfile) {
        if (!file_exists($xmlfile))
            $xmlfile=getenv("HOME") . $xmlfile;
        $this->hwdata = new \HWClasses\HWData($xmlfile);
    }
    
    public function build() : void {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        $hwd=$this->hwdata;
        $this->buildOrgan();
        $this->buildWindchestGroups();
        $this->buildEnclosures();
        $this->buildManuals();
        $this->buildTremulants();
        $this->buildCouplers();
        $this->buildStops();
        $this->buildRanks();
        $this->processSamples($hwd->attacks(), TRUE);
        $this->processSamples($hwd->releases(), FALSE);
    }

    protected function buildOrgan() : void {
        $general=$this->hwdata->general();
        $general["Identification_UniqueOrganID"]=2283; 
        $this->createOrgan($general);
        $general["Name"]="Billerbeck Extended";
        $general["PanelID"]=0;
        $panel=$this->createPanel($general);
        
        $panel->DispScreenSizeHoriz=intval(800*19/11);
        $panel->DispScreenSizeVert=800;
        $panel->DispDrawstopCols=6;
        $panel->DispDrawstopRows=10;

        $go=$panel->Label("GrandOrgue");
        $go->FreeXPlacement=$go->FreeYPlacement=$go->DispSpanDrawstopColToRight="N";
        $go->DispAtTopOfDrawstopCol="Y";
        $go->DispDrawstopCol=2;
        
        $ped=$panel->Label("Pedale");
        $ped->FreeXPlacement=$ped->DispSpanDrawstopColToRight="N";
        $ped->DispYpos=400;
        $ped->DispDrawstopCol=2;

        $sw=$panel->Label("Recit");
        $sw->FreeXPlacement=$sw->FreeYPlacement=$sw->DispSpanDrawstopColToRight="N";
        $sw->DispAtTopOfDrawstopCol="Y";
        $sw->DispDrawstopCol=5;
        
        $po=$panel->Label("Positif");
        $po->FreeXPlacement=$po->DispSpanDrawstopColToRight="N";
        $po->DispYpos=400;
        $po->DispDrawstopCol=5;
    }

    protected function buildWindchestGroups() {
        foreach($this->divisions as $divid=>$division) {
            foreach ($this->positions as $posid=>$position) {
                $this->createWindchestGroup([
                    "GroupID"=>$divid*100+$posid,
                    "Name"=>"$division $position"]);
            }
        }
    }

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        $panel=$this->getPanel(0);
        $panel->GUIElement($enclosure);
    }
    
    protected function buildEnclosures() {
        foreach ($this->enclosures as $divid=>$name) {
            $data=["EnclosureID"=>$divid, "Name"=>$name, "GroupIDs"=>[]];
            foreach ($this->positions as $posid=>$position)
                $data["GroupIDs"][]=$divid*100+$posid;
            $this->createEnclosure($data);
        }
    }
    protected function buildManuals() : void {
        foreach($this->manuals as $id=>$name)
            $this->createManual([
                "KeyboardID"=>$id,
                "Name"=>$name]);
    }

    protected function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $switchdata): void {
        $panel=$this->getPanel(0);
        $pe=$panel->GUIElement($switch);
        $pe->DispDrawstopRow=$switchdata["Position"][0];
        $pe->DispDrawstopCol=$switchdata["Position"][1];
        $pe->DispLabelText  =isset($switchdata["Label"])  ? $switchdata["Label"]  : $switchdata["Name"];
        $pe->DispLabelColour=isset($switchdata["Colour"]) ? $switchdata["Colour"] : "Black";
    }

    protected function buildTremulants() : void {
        foreach($this->tremulants as $id=>$tremdata) {
            $tremdata["Colour"]="Dark Blue";
            $tremdata["Label"]="Tremulant";
            $this->createTremulant($tremdata);
        }
    }

    protected function buildCouplers() : void {
        foreach($this->couplers as $id=>$cplrdata) {
            $cplrdata["Colour"]="Dark Green";
            $cplrdata["SwitchID"]=$cplrdata["CouplerID"]=$id;
            $this->createCoupler($cplrdata);
        }
    }

    
    protected function stopInUse(array $hwdata): bool {
        return TRUE;
    }

    protected function buildStops() : void {
        foreach($this->stops as $stopid=>$stopdata) {
            $stopdata["StopID"]=$stopdata["SwitchID"]=$stopid;
            $manualid=$stopdata["DivisionID"]=$stopdata["ManualID"];
            $switch=$this->createStop($stopdata);
            if (isset($stopdata["TremulantID"])) {
                $tswitchid=$this->tremulants[$stopdata["TremulantID"]]["SwitchID"];
                $on=$this->getSwitch($tswitchid);
                $off=$this->getSwitch(-$tswitchid);

                $this->getStop($stopid)->switch($off);
                $stop=$this->newStop(-$stopid, $this->stops[$stopid]["Name"] . " (tremulant)");
                $this->getManual($manualid)->Stop($stop);
                $stop->switch($switch);
                $stop->switch($on);
            }
        }
    }

    protected function buildRank(int $stopid, array $rankdata) : ?\GOClasses\Rank {
        $rankid=$rankdata["RankID"];
        $divid=$this->stops["$stopid"]["DivisionID"];
        $posid=$this->rankpositions[$rankid % 10];
        $wcg=$this->getWindchestGroup(($divid*100) + $posid);
        if ($wcg===NULL) return NULL;
        $rank=$this->newRank($rankid, $rankdata["Name"]);
        $rank->WindchestGroup($wcg);
        foreach ($this->stops as $id=>$stopdata) {
            if ($stopid==$id || (isset($stopdata["Rank"]) && $stopdata["Rank"]==$stopid)) {
                if (($rankid % 10)<5)
                    $stop=$this->getStop($id);
                else
                    $stop=$this->getStop(-$id);
                if ($stop!==NULL)  $stop->Rank($rank);
                if (isset($stopdata["FirstKey"])) {
                    $ranknum=$stop->int2str($stop->NumberOfRanks);
                    $stop->set("Rank${ranknum}FirstPipeNumber", $stopdata["FirstKey"]);
                }
                if (isset($stopdata["FirstNote"])) {
                    $ranknum=$stop->int2str($stop->NumberOfRanks);
                    $stop->set("Rank${ranknum}FirstAccessibleKeyNumber", $stopdata["FirstNote"]);
                }
            }
        }
        return $rank;
    }
    
    protected function buildRanks() : void {
        foreach($this->hwdata->ranks() as $rankdata) {
            $stopid=intval($rankid=($rankdata["RankID"]/10));
            if (isset($this->stops[$stopid]))
                $rank=$this->buildRank($stopid, $rankdata);
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
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    protected function sampleMidiKey(array $hwdata) : int {
        $key=12+$hwdata["PipeID"] % 100;
        return $key;
    }
   
    public function processSample(array $hwdata, bool $isattack) : ?\GOClasses\Pipe {
        if (isset($hwdata["LoopCrossfadeLengthInSrcSampleMs"]) 
                && $hwdata["LoopCrossfadeLengthInSrcSampleMs"]>120)
                $hwdata["LoopCrossfadeLengthInSrcSampleMs"]=120;
        unset($hwdata["ReleaseCrossfadeLengthMs"]);
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the build
     */
    public static function BillerbeckExt(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new BillerbeckExt(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->build();
            $hwi->getOrgan()->ChurchName=str_replace("8ch Demo", "$target (Extended Demo)", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getPanel(0)->Name="Billerbeck Dom Extended ($target)";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::BillerbeckExt(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::BillerbeckExt(
                    [self::RANKS_SEMI_DRY=>"Semi-dry"],
                    "Semi-Dry");
            self::BillerbeckExt(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::BillerbeckExt(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::BillerbeckExt(
                    [
                        self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_SEMI_DRY=>"Semi-dry",
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "8ch");
        }
    }
}
BillerbeckExt::BillerbeckExt();