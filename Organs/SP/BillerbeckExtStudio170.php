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
 * Create bespoke organ (for Studio 170) based on Billerbeck Dom demo from SP
 * 
 * @author andrew
 */
class BillerbeckExtStudio170 extends \Import\Organ {
    const ROOT="/GrandOrgue/Organs/SP/Billerbeck/";
    const SOURCE="OrganDefinitions/Billerbeck, Fleiter Surr.Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Billerbeck, Fleiter Surr (Extended Demo - %s) 0.7.organ";

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
        2=>["TremulantID"=>2, "Name"=>"Rec", "SwitchID"=>203, "Type"=>"Wave", 
            "DivisionID"=>2, "Position"=>[4, 6], "GroupIDs"=>[201,202,203,204]],
        3=>["TremulantID"=>3, "Name"=>"Pos", "SwitchID"=>204, "Type"=>"Wave",
            "DivisionID"=>3, "Position"=>[8, 6], "GroupIDs"=>[301,302,303,304]], 
    ];

    private $couplers=[
        301=>["Name"=>"GO/Ped",  "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "Position"=>[9, 1]], // "DestinationKeyshift"=>0
        302=>["Name"=>"Rec/Ped", "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "Position"=>[8, 3]],
        303=>["Name"=>"Pos/Ped", "SourceKeyboardID"=>0, "DestKeyboardID"=>3, "Position"=>[9, 3]],
        311=>["Name"=>"Rec/GO",  "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "Position"=>[4, 2]],
        312=>["Name"=>"Pos/GO",  "SourceKeyboardID"=>1, "DestKeyboardID"=>3, "Position"=>[4, 3]],
        321=>["Name"=>"Pos/Rec", "SourceKeyboardID"=>2, "DestKeyboardID"=>3, "Position"=>[5, 5]],
    ];
    
    private $stops=[
         2=>["Rank"=> 2, "ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 1], "Name"=>"Principal 16"], // Ex GO
         4=>["Rank"=> 4, "ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 1], "Name"=>"Principal major 8"],
         9=>["Rank"=> 9, "ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 1], "Name"=>"Octave major 4"],
        13=>["Rank"=>13, "ManualID"=>1, "DivisionID"=>1, "Position"=>[4, 1], "Name"=>"Octave 2"],
         8=>["Rank"=> 8, "ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 2], "Name"=>"Gedackt 8"],
        78=>["Rank"=>78, "ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 2], "Name"=>"Holzflote 4"],
         7=>["Rank"=> 7, "ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 2], "Name"=>"Viola da Gamba 8"],
        15=>["Rank"=>15, "ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 3], "Name"=>"Mixture major"],
        16=>["Rank"=>16, "ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 3], "Name"=>"Mixture minor"],
        43=>["Rank"=>43, "ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 3], "Name"=>"Trompeta real 8", "Colour"=>"Dark Red"], // Ex Chamade
       
        27=>["Rank"=>27, "ManualID"=>0, "DivisionID"=>0, "Position"=>[6, 1], "Name"=>"Principalbass 16"],
       104=>["Rank"=> 4, "ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 1], "Name"=>"Principal 8"],
        38=>["Rank"=>38, "ManualID"=>0, "DivisionID"=>0, "Position"=>[8, 1], "Name"=>"Posaune 16", "Colour"=>"Dark Red"],
        26=>["Rank"=>26, "ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 2], "Name"=>"Bourdon 32"],
       126=>["Rank"=>26, "ManualID"=>0, "DivisionID"=>0, "Position"=>[8, 2], "Name"=>"Soubasse 16", "FirstKey"=>13], // #26 +12
        32=>["Rank"=>32, "ManualID"=>0, "DivisionID"=>0, "Position"=>[9, 2], "Name"=>"Flute 8"],
       178=>["Rank"=>78, "ManualID"=>0, "DivisionID"=>0, "Position"=>[6, 3], "Name"=>"Holzflote 4"],
       143=>["Rank"=>43, "ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 3], "Name"=>"Trompeta 8", "Colour"=>"Dark Red"], // #43
       
        51=>["Rank"=>51, "ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 4], "TremulantID"=>2, "Name"=>"Geigenprincipal 8"], // Ex Rec
        77=>["Rank"=>77, "ManualID"=>2, "DivisionID"=>2, "Position"=>[2, 4], "TremulantID"=>2, "Name"=>"Praestant 4"],
        60=>["Rank"=>60, "ManualID"=>2, "DivisionID"=>2, "Position"=>[3, 4], "TremulantID"=>2, "Name"=>"Octavin 2"],
        64=>["Rank"=>64, "ManualID"=>2, "DivisionID"=>2, "Position"=>[4, 4], "TremulantID"=>3, "Name"=>"Trompette harmonique 8", "Colour"=>"Dark Red"],
        55=>["Rank"=>55, "ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 5], "TremulantID"=>2, "Name"=>"Bourdon 8"],
        53=>["Rank"=>53, "ManualID"=>2, "DivisionID"=>2, "Position"=>[2, 5], "TremulantID"=>2, "Name"=>"Viola 8"],
        54=>["Rank"=>54, "ManualID"=>2, "DivisionID"=>2, "Position"=>[3, 5], "TremulantID"=>2, "Name"=>"Vox coelestis 8", "FirstNote"=>6],
        57=>["Rank"=>57, "ManualID"=>2, "DivisionID"=>2, "Position"=>[4, 5], "TremulantID"=>2, "Name"=>"Flute octaviante 4"],
        59=>["Rank"=>59, "ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 6], "TremulantID"=>2, "Name"=>"Nazard harmonique 2 2/3"],
        81=>["Rank"=>81, "ManualID"=>2, "DivisionID"=>2, "Position"=>[2, 6], "TremulantID"=>2, "Name"=>"Sesquialtera"],
        82=>["Rank"=>82, "ManualID"=>2, "DivisionID"=>2, "Position"=>[3, 6], "TremulantID"=>2, "Name"=>"Mixtur 4f."],

        46=>["Rank"=>46, "ManualID"=>3, "DivisionID"=>3, "Position"=>[7, 4], "Name"=>"Flute harmonique 8"],
        74=>["Rank"=>74, "ManualID"=>3, "DivisionID"=>3, "Position"=>[8, 4], "TremulantID"=>3, "Name"=>"Rohrflote 8"], // Ex Pos
        58=>["Rank"=>58, "ManualID"=>3, "DivisionID"=>3, "Position"=>[7, 5], "TremulantID"=>3, "Name"=>"Fugara 4"],
        79=>["Rank"=>79, "ManualID"=>3, "DivisionID"=>3, "Position"=>[8, 5], "TremulantID"=>3, "Name"=>"Waldflote 2"],
        84=>["Rank"=>84, "ManualID"=>3, "DivisionID"=>3, "Position"=>[7, 6], "TremulantID"=>3, "Name"=>"Cromorne 8", "Colour"=>"Dark Red"],
    ];
    
        protected \HWClasses\HWData $hwdata;
    
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
        $this->processSamples($hwd->attacks(), TRUE);
        $this->processSamples($hwd->releases(), FALSE);
        //$this->addVirtualKeyboards(2, [1,2,3], [1,2]);
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
            $stop=$this->getStop($stopid);
            $baseid=$stopdata["Rank"];
            $tremmed=isset($stopdata["TremulantID"]);
            foreach($this->rankpositions as $offset=>$groupid) {
                if ($offset>4) continue;
                $rankdata=$this->hwdata->rank($baseid*10+$offset, FALSE);
                if ($rankdata) {
                    $divid=$this->stops[$stopid]["DivisionID"];
                    $wcg=$this->getWindchestGroup(($divid*100) + $groupid);
                    if ($wcg) {
                        $rank=$this->newRank(($stopid*10)+$offset, $rankdata["Name"] . " (" . $stopdata["Name"] . ")");
                        $rank->WindchestGroup($wcg);
                        $stop->Rank($rank);
                        if (isset($stopdata["PitchTuning"])) {
                            $rank->PitchTuning=$stopdata["PitchTuning"];
                        }
                        $ranknum=$stop->int2str($stop->NumberOfRanks);
                        if (isset($stopdata["FirstKey"])) {
                            $stop->set("Rank${ranknum}FirstPipeNumber", $stopdata["FirstKey"]);
                        }
                        if (isset($stopdata["FirstNote"])) {
                            $stop->set("Rank${ranknum}FirstAccessibleKeyNumber", $stopdata["FirstNote"]);
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
        switch (($rankid=$hwdata["RankID"]) % 10) {
            case 9:
                $hwdata["RankID"]-=9;
                $tremulant=TRUE;
                break;
            case 8:
                $hwdata["RankID"]-=4;
                $tremulant=TRUE;
                break;
            case 7:
                $hwdata["RankID"]-=6;
                $tremulant=TRUE;
                break;
            case 6:
                $hwdata["RankID"]-=4;
                $tremulant=TRUE;
                break;
            default:
                $tremulant=FALSE;
        }
        if ($tremulant) $hwdata["IsTremulant"]=1;

        foreach([0,1,2] as $offset) {
            $stopid=intval($hwdata["RankID"]/10);
            if (isset($this->stops[$stopid])) {
                if (isset($this->stops[$stopid]["TremulantID"]) || !$tremulant) {
                    $pipe=parent::processSample($hwdata, $isattack);
                    if ($pipe) unset($pipe->PitchTuning);
                }
            }
            $hwdata["RankID"]+=1000;
            $hwdata["PipeID"]+=100000;
        }
        return NULL;
    }

    /**
     * Run the build
     */
    public static function BillerbeckExtStudio170(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new BillerbeckExtStudio170(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->build();
            $hwi->getOrgan()->ChurchName=str_replace("8ch. Demo", "$target (Extended Demo)", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getPanel(0)->Name="Billerbeck Dom Extended ($target)";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::BillerbeckExtStudio170(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct");
            self::BillerbeckExtStudio170(
                    [self::RANKS_SEMI_DRY=>"Semi-dry"],
                    "Semi-Dry");
            self::BillerbeckExtStudio170(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::BillerbeckExtStudio170(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::BillerbeckExtStudio170(
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
BillerbeckExtStudio170::BillerbeckExtStudio170();