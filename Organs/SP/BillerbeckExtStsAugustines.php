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
 * Create bespoke organ (for Sts Augustine) based on Billerbeck Dom demo from SP
 * 
 * @author andrew
 */
class BillerbeckExtStsAugustine extends \Import\Organ {
    const ROOT="/GrandOrgue/Organs/Billerbeck/";
    const SOURCE="OrganDefinitions/Billerbeck, Fleiter Surr.Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Saints Augustine (Billerbeck Model) %s.organ";

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
    ];
    
    private $enclosures=[
        2=>"Sw",
    ];

    private $manuals=[ 
        0=>"Pedal", 
        1=>"Great",
        2=>"Swell",
    ];

    private $tremulants=[ 
        2=>["Name"=>"Swell", "SwitchID"=>203, "Type"=>"Switched", "Position"=>[7, 3]],
    ];
    private $couplers=[
       301=>["Name"=>"Gt/Ped",  "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "Position"=>[11, 2]],
       302=>["Name"=>"Sw/Ped",  "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "Position"=>[11, 3]],
       303=>["Name"=>"Sw/Gt",   "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "Position"=>[6, 2]],
       304=>["Name"=>"Ped/Gt",  "SourceKeyboardID"=>1, "DestKeyboardID"=>0, "Position"=>[7, 2], "ActionTypeCode"=>2],
    ];
    
    private $stops=[
        27=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[ 9, 1], "Name"=>"Subbass 16'"], // P. Principalbass 16
        26=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[10, 1], "Name"=>"Lieblich Gedackt 16'", "FirstKey"=>13], // P. Soubasse 16
        15=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[11, 1], "Name"=>"Mixture III"], // 2. Mixture major
       104=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[ 9, 2], "Name"=>"Octave 8'", "Rank"=>4], // 2. Principal major 8
        32=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[10, 2], "Name"=>"Flute 8'"], // P. Flute 8
        58=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[ 9, 3], "Name"=>"Choral Bass 4'"], // 3. Fugara 4
       178=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[10, 3], "Name"=>"Nachthorn 4'", "Rank"=>78], // 1. Holzflote 4
        38=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[ 9, 4], "Name"=>"Pausaune 16'"], // P. Posaune 16
       143=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[10, 4], "Name"=>"Clairon 4'", "Rank"=>43], // 4. Trompeta real 8

         4=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 1, 1], /* "TremulantID"=>1, */ "Name"=>"Principal 8'"], // 2. Principal major 8
         9=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 2, 1], /* "TremulantID"=>1, */ "Name"=>"Octave 4'"], // 2. Octave major 4
        13=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 1], /* "TremulantID"=>1, */ "Name"=>"Super Octave 2'"], // 2. Octave 2
        16=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 1],                   "Name"=>"Mixture III"], // 2. Mixture minor
         7=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 5, 1], /* "TremulantID"=>1, */ "Name"=>"Gambe 8'"], // 2. Viola da Gamba 8
         8=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 1, 2], /* "TremulantID"=>1, */ "Name"=>"Spitzflote 8'"], // 2. Gedackt 8
        78=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 2, 2], /* "TremulantID"=>1, */ "Name"=>"Open Flute 4'"], // 1. Holzflote 4
        79=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 2], /* "TremulantID"=>1, */ "Name"=>"Waldflote 2'"], // 1. Waldflote 2
        //28=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 2], /* "TremulantID"=>1, */ "Name"=>"Larigot 1-1/3"], // OW Quinta 1 1/3
        64=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 5, 2], /* "TremulantID"=>1, */ "Name"=>"Trompette 8'"], // 3. Trompette harmonique 8

        51=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 1, 3], "TremulantID"=>2, "Name"=>"Viola Pomposa 8'"], // 3. Geigenprincipal 8
        77=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 2, 3], "TremulantID"=>2, "Name"=>"Prestant 4'"], // 1. Praestant 4
        //47=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 3, 3], "TremulantID"=>2, "Name"=>"Sifflet 1'"], // BW Sifflote 1
        82=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 4, 3],                   "Name"=>"Mixture III'"], // 1. Mixtur 4f.
        //16=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 3], "TremulantID"=>2, "Name"=>"Contre Trompette 16'"], // HW Fagott 16
        43=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 3], "TremulantID"=>2, "Name"=>"Trompette 8'"], // 4. Trompeta real 8
         2=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 1, 4], "TremulantID"=>2, "Name"=>"Bourdon Doux 16'"], // 2. Principal 16
        46=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 2, 4], "TremulantID"=>2, "Name"=>"Rohrflote 8'"], // 4. Flute harmonique 8
        57=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 3, 4], "TremulantID"=>2, "Name"=>"Gedackt 4'"], // 3. Flute octaviante 4
        59=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 4, 4], "TremulantID"=>2, "Name"=>"Nasard 2 2/3'"], // 3. Nazard harmonique 2 2/3
        79=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 4], "TremulantID"=>2, "Name"=>"Blockflote 2'"], // 1. Waldflote  
        //46=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 4], "TremulantID"=>2, "Name"=>"Tierce 1 3/5'"], // BW Terzflote 1 3/5
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
        $panel->DispDrawstopCols=4;
        $panel->DispDrawstopRows=11;

        /* $go=$panel->Label("GrandOrgue");
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
        $sw->DispDrawstopCol=5; */
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
        $tremmed=(($rankid % 10)>4);
        $divid=$this->stops["$stopid"]["DivisionID"];
        $posid=$this->rankpositions[$rankid % 10];
        $wcg=$this->getWindchestGroup(($divid*100) + $posid);
        if ($wcg===NULL) return NULL;
        $rank=NULL;
        foreach ($this->stops as $id=>$stopdata) {
            if ($stopid==$id || (isset($stopdata["Rank"]) && $stopdata["Rank"]==$stopid)) {
                if (empty($stopdata["TremulantID"]) && $tremmed) continue;
                $stop=$this->getStop($tremmed ? -$id : +$id);
                if ($stop==NULL) continue;
                if (empty($rank)) {
                    $rank=$this->newRank($rankid, $rankdata["Name"]);
                    $rank->WindchestGroup($wcg);
                }
                $stop->Rank($rank);
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
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe) unset($pipe->PitchTuning);
        return $pipe;
    }

    /**
     * Run the build
     */
    public static function BillerbeckExtStsAugustine(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new BillerbeckExtStsAugustine(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->build();
            $hwi->getOrgan()->ChurchName=$hwi->getPanel(0)->Name="Saints Augustine (Billerbeck Model - $target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getPanel(0)->Name="Saints Augustine (Billerbeck Model - $target)";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::BillerbeckExtStsAugustine(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::BillerbeckExtStsAugustine(
                    [self::RANKS_SEMI_DRY=>"Semi-dry"],
                    "Semi-Dry");
            self::BillerbeckExtStsAugustine(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            /* self::BillerbeckExtStsAugustine(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::BillerbeckExtStsAugustine(
                    [
                        self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_SEMI_DRY=>"Semi-dry",
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "8ch"); */
        }
    }
}
BillerbeckExtStsAugustine::BillerbeckExtStsAugustine();