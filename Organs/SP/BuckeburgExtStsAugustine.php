<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\SP;
require_once __DIR__ . "/SPOrgan.php";

/**
 * Model of the (new) Saints Augustine Organ, based on Buckeburg sammples
 * 
 * @author andrew`
 */
class BuckeburgExtStsAugustine extends \Import\Organ {
    const ROOT="/GrandOrgue/Organs/SP/Buckeburg/";
    const SOURCE="OrganDefinitions/Buckeburg, Janke Organ, Surround Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Saints Augustine (Buckeburg Model) %s.organ";

    const RANKS_DIRECT=1;
    const RANKS_SEMI_DRY=2;
    const RANKS_DIFFUSE=3;
    const RANKS_REAR=4;
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,   9=>self::RANKS_DIRECT,
        1=>self::RANKS_DIFFUSE,  7=>self::RANKS_DIFFUSE,
        4=>self::RANKS_REAR,     8=>self::RANKS_REAR
    ];
    
    public $positions=[];
    private $divisions=[
        0=>"Pedal",
        1=>"Great",
        2=>"Swell"
    ];
    
    private $enclosures=[
        2=>"Swell"
    ];

    private $manuals=[ 
        0=>"Pedal", 
        1=>"Great",
        2=>"Swell",
    ];

    private $tremulants=[ 
        1=>["TremulantID"=>1, "Name"=>"Great", "SwitchID"=>201, "Type"=>"Wave",
            "Position"=>[6, 1], "GroupIDs"=>[101,102,103,104]],
        2=>["TremulantID"=>2, "Name"=>"Swell", "SwitchID"=>203, "Type"=>"Wave",
            "Position"=>[7, 3], "GroupIDs"=>[201,202,203,204]],
    ];

    private $couplers=[
       301=>["Name"=>"Gt/Ped",  "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "Position"=>[11, 2]],
       302=>["Name"=>"Sw/Ped",  "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "Position"=>[11, 3]],
       303=>["Name"=>"Sw/Gt",   "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "Position"=>[6, 2]],
       304=>["Name"=>"Ped/Gt",  "SourceKeyboardID"=>1, "DestKeyboardID"=>0, "Position"=>[7, 2], "ActionTypeCode"=>2],
    ];
    
    private $stops=[
         7=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[ 9, 1], "Name"=>"Subbass 16'"], // Ped Principal 16'
         1=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[10, 1], "Name"=>"Lieblich Gedackt 16'"], // Ped Subbass 16
        10=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[11, 1], "Name"=>"Mixture III"], // Ped Mixtur 2 2/3x
         8=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[ 9, 2], "Name"=>"Octave 8'"], // Ped Octavbass 8
         2=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[10, 2], "Name"=>"Flute 8'"], // Ped Gemshorn 8
         9=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[ 9, 3], "Name"=>"Choral Bass 4'"], // Ped Octave 4
        14=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[10, 3], "Name"=>"Nachthorn 4'"], // HW Gemshorn 4
         3=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[ 9, 4], "Name"=>"Pausaune 16'"], // Ped Posaune 16
         5=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[10, 4], "Name"=>"Clairon 4'"], // Ped Trompete 4

        20=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 1, 1], "TremulantID"=>1, "Name"=>"Principal 8'"], // HW Principal 8
        21=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 2, 1], "TremulantID"=>1, "Name"=>"Octave 4'"], // HW Octave 4
        23=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 1], "TremulantID"=>1, "Name"=>"Super Octave 2'"], // HW Octave 2
        11=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 1],                   "Name"=>"Mixture III"], // HW Mixtur 1 1/3
        12=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 5, 1], "TremulantID"=>1, "Name"=>"Gambe 8'"], // HW Viola da gamba 8
        13=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 1, 2], "TremulantID"=>1, "Name"=>"Spitzflote 8'"], // HW Holzflote 8
        33=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 2, 2], "TremulantID"=>1, "Name"=>"Open Flute 4'"], // OW Flote 4
        35=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 2], "TremulantID"=>1, "Name"=>"Waldflote 2'"], // OW Waldflote 2
        28=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 2], "TremulantID"=>1, "Name"=>"Larigot 1-1/3"], // OW Quinta 1 1/3
        17=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[ 5, 2], "TremulantID"=>1, "Name"=>"Trompette 8'"], //HW Trompete 8

        25=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 1, 3], "TremulantID"=>2, "Name"=>"Viola Pomposa 8'"], // OW Principal 8
        26=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 2, 3], "TremulantID"=>2, "Name"=>"Prestant 4'"], // OW Octave 4
        47=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 3, 3], "TremulantID"=>2, "Name"=>"Sifflet 1'"], // BW Sifflote 1
        30=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 4, 3],                   "Name"=>"Mixture III'"], // OW Mixtur 1
        16=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 3], "TremulantID"=>2, "Name"=>"Contre Trompette 16'"], // HW Fagott 16
        48=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 3], "TremulantID"=>2, "Name"=>"Trompette 8'"], // BW Regal 8
        18=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 1, 4], "TremulantID"=>2, "Name"=>"Bourdon Doux 16'"], // HW Bordun 16
        32=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 2, 4], "TremulantID"=>2, "Name"=>"Rohrflote 8'"], // OW Rohrflote 8
        43=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 3, 4], "TremulantID"=>2, "Name"=>"Gedackt 4'"], // BW Holzflote 4
        34=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 4, 4], "TremulantID"=>2, "Name"=>"Nasard 2 2/3'"], // OW Nasat 2 2/3
        45=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 4], "TremulantID"=>2, "Name"=>"Blockflote 2'"], // BW Hohlflote 
        46=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 4], "TremulantID"=>2, "Name"=>"Tierce 1 3/5'"], // BW Terzflote 1 3/5
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
        $general["Identification_UniqueOrganID"]=2306; 
        $this->createOrgan($general);
        $general["Name"]="Saints Augustine (Buckeburg Model)";
        $general["PanelID"]=0;
        $panel=$this->createPanel($general);
        
        $panel->DispScreenSizeHoriz=intval(800*19/11);
        $panel->DispScreenSizeVert=800;
        $panel->DispDrawstopCols=4;
        $panel->DispDrawstopRows=11;

        /* $go=$panel->Label("Great");
        $go->FreeXPlacement=$go->FreeYPlacement=$go->DispSpanDrawstopColToRight="N";
        $go->DispAtTopOfDrawstopCol="Y";
        $go->DispDrawstopCol=2;
        
        $ped=$panel->Label("Pedal");
        $ped->FreeXPlacement=$ped->DispSpanDrawstopColToRight="N";
        $ped->DispYpos=400;
        $ped->DispDrawstopCol=2;

        $sw=$panel->Label("Swell");
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
        }
    }

    protected function buildRank(int $stopid, array $rankdata) : void {      
        $rankid=$rankdata["RankID"];
        if ($rankid % 10>4) return;
        
        $divid=$this->stops["$stopid"]["DivisionID"];
        $posid=$this->rankpositions[$rankid % 10];
        $wcg=$this->getWindchestGroup(($divid*100) + $posid);
        if ($wcg===NULL) return;
        
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
    }
    
    protected function buildRanks() : void {
        foreach($this->hwdata->ranks() as $rankdata) {
            $stopid=intval($rankdata["RankID"]/10);
            if (isset($this->stops[$stopid]))
                $this->buildRank($stopid, $rankdata);
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
        switch ($hwdata["RankID"] % 10) {
            case 9:
                $hwdata["RankID"]-=9;
                $hwdata["IsTremulant"]=1;
                break;
            case 8:
                $hwdata["RankID"]-=4;
                $hwdata["IsTremulant"]=1;
                break;
            case 7:
                $hwdata["RankID"]-=6;
                $hwdata["IsTremulant"]=1;
                break;
        }
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the build
     */
    public static function BuckeburgExtStsAugustine(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new BuckeburgExtStsAugustine(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->build();
            $hwi->getOrgan()->ChurchName=$hwi->getPanel(0)->Name="Saints Augustine (Buckeburg Model - $target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            /* self::BuckeburgExtStsAugustine(
                    [self::RANKS_DIRECT=>"Direct"],
                    "Direct"); */
            self::BuckeburgExtStsAugustine(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::BuckeburgExtStsAugustine(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            /* self::BuckeburgExtStsAugustine(
                    [
                        //self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "4ch"); */
        }
    }
}
BuckeburgExtStsAugustine::BuckeburgExtStsAugustine();