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
 * Model of the Old Brampton Organ, based on Buckeburg sammples
 * 
 * @author andrew`
 */
class BuckeburgExtOB extends \Import\Organ {
    const ROOT="/GrandOrgue/Organs/Buckeburg/";
    const SOURCE="OrganDefinitions/Buckeburg, Janke Organ, Surround Demo.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Old Brampton (Buckeburg Model) %s.organ";

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
       1=>["TremulantID"=>1, "Name"=>"Tremulant", "SwitchID"=>202, "Type"=>"Wave", 
           "Position"=>[4, 4], "GroupIDs"=>[101,102,103,104,201,202,203,204]],
    ];

    private $couplers=[
       301=>["Name"=>"Gt Ped",    "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "Position"=>[6, 4]],
       302=>["Name"=>"Sw Ped",    "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "Position"=>[7, 4]],
       303=>["Name"=>"Sw Sup",    "SourceKeyboardID"=>2, "DestKeyboardID"=>2, "Position"=>[2, 4], "MIDINoteNumberIncrement"=>12],
       304=>["Name"=>"Sw Sub",    "SourceKeyboardID"=>2, "DestKeyboardID"=>2, "Position"=>[3, 4], "MIDINoteNumberIncrement"=>-12],
       305=>["Name"=>"Sw Gt",     "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "Position"=>[3, 2]],
       306=>["Name"=>"Sw Sup Gt", "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "Position"=>[4, 2], "MIDINoteNumberIncrement"=>12],
    ];
    
    private $stops=[
        20=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 1], "TremulantID"=>1, "Name"=>"Diapason 8'"],
        21=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 1], "TremulantID"=>1, "Name"=>"Principal 4'"],
        23=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 1], "TremulantID"=>1, "Name"=>"Octave 2'"],
        13=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 2], "TremulantID"=>1, "Name"=>"St Diapason 8'"],
        12=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 2], "TremulantID"=>1, "Name"=>"Dulciana 8'"],

         1=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[6, 1], "Name"=>"Bourdon 16'"],
        42=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 1], "Name"=>"Bass 8'"],

        32=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 3], "TremulantID"=>1, "Name"=>"Vox Angelica 8'"],
        25=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[2, 3], "TremulantID"=>1, "Name"=>"Diapason 8'"],
        26=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[3, 3], "TremulantID"=>1, "Name"=>"Gemshorn 4'"], 
        30=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[4, 3],                   "Name"=>"Mixture II"],
        36=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 4], "TremulantID"=>1, "Name"=>"Oboe 8'"],
    ];

    protected \HWClasses\HWData $hwdata;
    
    public function __construct($xmlfile) {
        if (!file_exists($xmlfile))
            $xmlfile=getenv("HOME") . $xmlfile;
        $this->hwdata = new \HWClasses\HWData($xmlfile);
    }
    
    public function build() : void {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
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
    }

    protected function buildOrgan() : void {
        $general=$this->hwdata->general();
        $general["Identification_UniqueOrganID"]=2306; 
        $this->createOrgan($general);
        $general["Name"]="Old Brampton (Buckeburg Model)";
        $general["PanelID"]=0;
        $panel=$this->createPanel($general);
        
        $panel->DispScreenSizeHoriz=intval(600*19/11);
        $panel->DispScreenSizeVert=600;
        $panel->DispDrawstopCols=4;
        $panel->DispDrawstopRows=8;

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
            $stop=$this->getStop($stopid);
            $baseid=isset($stopdata["Rank"]) ? $stopdata["Rank"] : $stopdata["StopID"];
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
        if (isset($hwdata["LoopCrossfadeLengthInSrcSampleMs"]) 
                && $hwdata["LoopCrossfadeLengthInSrcSampleMs"]>120)
                $hwdata["LoopCrossfadeLengthInSrcSampleMs"]=120;
        unset($hwdata["ReleaseCrossfadeLengthMs"]);
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
            default:
                $tremulant=FALSE;
        }
        if ($tremulant) $hwdata["IsTremulant"]=1;
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the build
     */
    public static function BuckeburgExtOB(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new BuckeburgExtOB(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->build();
            $hwi->getOrgan()->ChurchName=$hwi->getPanel(0)->Name="Old Brampton (Buckeburg Model - $target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            //self::BuckeburgExtOB(
            //        [self::RANKS_DIRECT=>"Direct"],
            //        "Direct");
            self::BuckeburgExtOB(
                    [self::RANKS_DIFFUSE=>"Diffuse"],
                     "Diffuse");
            self::BuckeburgExtOB(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::BuckeburgExtOB(
                    [
                        //self::RANKS_DIRECT=>"Direct", 
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "4ch");
        }
    }
}
BuckeburgExtOB::BuckeburgExtOB();