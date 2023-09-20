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
 * Create extension of Friesach as implemented for HW by Les Deutsch
 * 
 * @author andrew
 */
class FriesachExt extends \Import\Organ {
    const ROOT="/GrandOrgue/Organs/PG/Friesach/";
    const SOURCE=self::ROOT . "OrganDefinitions/Friesach.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Friesach Extended 1.1.organ";
    const COMMENTS="\n"
            . "1.1 Extended pedals to 32\n"
            . "\n";
    const WIDTH=1500;
    const HEIGHT=800;

    private $divisions=[
        0=>"Pedal",
        1=>"Positif",
        2=>"Hauptwerk",
        3=>"Schwellwerk"
    ];
    
    private $enclosures=[
        1=>"Pos",
        3=>"Schw"
    ];

    private $manuals=[ 
        0=>"Pedal",
        1=>"Positif",
        2=>"Hauptwerk",
        3=>"Schwellwerk"
    ];

    private $tremulants=[ 
        1=>["TremulantID"=>3, "Name"=>"Pos", "SwitchID"=>201, "Type"=>"Synth",
            "Period"=>250, "StartRate"=>50, "StopRate"=>30, "AmpModDepth"=>6, 
            "DivisionID"=>1, "Position"=>[12, 1], "GroupIDs"=>[1]], 
        3=>["TremulantID"=>2, "Name"=>"Schw", "SwitchID"=>203, "Type"=>"Synth", 
            "Period"=>208, "StartRate"=>30, "StopRate"=>70, "AmpModDepth"=>8, 
            "DivisionID"=>3, "Position"=>[6, 1], "GroupIDs"=>[3]],
    ];

    private $couplers=[
        201=>["Name"=>"SW 4",       "SourceKeyboardID"=>2, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 4, 1]],
        202=>["Name"=>"SW 16",      "SourceKeyboardID"=>2, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 4, 2]],
        203=>["Name"=>"Unison Off", "SourceKeyboardID"=>2, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 5, 1]],

        211=>["Name"=>"PO 4",       "SourceKeyboardID"=>1, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>+12, "Position"=>[10, 1]],
        212=>["Name"=>"PO 16",      "SourceKeyboardID"=>1, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>-12, "Position"=>[10, 2]],
        213=>["Name"=>"Unison Off", "SourceKeyboardID"=>1, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>0,   "Position"=>[11, 1]],
        
        301=>["Name"=>"HW Ped 8", "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>0,   "Position"=>[1, 100]],
        302=>["Name"=>"HW Ped 4", "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>+12, "Position"=>[2, 100]],
        303=>["Name"=>"SW Ped 8", "SourceKeyboardID"=>0, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>0,   "Position"=>[3, 100]],
        304=>["Name"=>"SW Ped 4", "SourceKeyboardID"=>0, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>+12, "Position"=>[4, 100]],
        305=>["Name"=>"PO Ped 4", "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>0,   "Position"=>[5, 100]],
        306=>["Name"=>"PO Ped 4", "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>+12, "Position"=>[6, 100]],
        
        311=>["Name"=>"SW HW 16", "SourceKeyboardID"=>2, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>-12, "Position"=>[1, 101]],
        312=>["Name"=>"SW HW 8",  "SourceKeyboardID"=>2, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>0,   "Position"=>[2, 101]],
        313=>["Name"=>"SW HW 4",  "SourceKeyboardID"=>2, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>+12, "Position"=>[3, 101]],
        314=>["Name"=>"PO HW 16", "SourceKeyboardID"=>2, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>-12, "Position"=>[4, 101]],
        315=>["Name"=>"PO HW 8",  "SourceKeyboardID"=>2, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>0,   "Position"=>[5, 101]],
        316=>["Name"=>"PO HW 4",  "SourceKeyboardID"=>2, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>+12, "Position"=>[6, 101]],
        
        321=>["Name"=>"SW PO 16", "SourceKeyboardID"=>1, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>-12, "Position"=>[1, 102]],
        322=>["Name"=>"SW PO 8",  "SourceKeyboardID"=>1, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>0,   "Position"=>[2, 102]],
        323=>["Name"=>"SW PO 4",  "SourceKeyboardID"=>1, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>+12, "Position"=>[3, 102]],
        324=>["Name"=>"PO SW 16", "SourceKeyboardID"=>3, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>-12, "Position"=>[4, 102]],
        325=>["Name"=>"PO SW 8",  "SourceKeyboardID"=>3, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>0,   "Position"=>[5, 102]],
        326=>["Name"=>"PO SW 4",  "SourceKeyboardID"=>3, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>+12, "Position"=>[6, 102]],
        
    ];
    
    private $stops=[
        // Regular stops
         1=>["Rank"=> 1, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 5], "Name"=>"Unterstatz 32"],
         2=>["Rank"=> 2, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 5], "Name"=>"Contra- bass 16"],
         3=>["Rank"=> 3, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 4], "Name"=>"Subbass 16"],
         4=>["Rank"=> 4, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 4], "Name"=>"Octave- bass 8"],
         5=>["Rank"=> 5, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 3], "Name"=>"Gedackt 8"],
         6=>["Rank"=> 6, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 3], "Name"=>"Choral- bass 4"],
         7=>["Rank"=> 7, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 2], "Name"=>"Posaune 32", "Colour"=>"Dark Red"],
         8=>["Rank"=> 8, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 3, 2], "Name"=>"Posaune 16", "Colour"=>"Dark Red"],
         9=>["Rank"=> 9, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 1], "Name"=>"Trompete 8", "Colour"=>"Dark Red"],
        
        10=>["Rank"=>10, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 9, 5], "Name"=>"Praestant 16"],
        11=>["Rank"=>11, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 8, 5], "Name"=>"Principal 8"],
        12=>["Rank"=>12, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 7, 5], "Name"=>"Holzflöte 8"],
        13=>["Rank"=>13, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 9, 4], "Name"=>"Röhrflöte 8"],
        14=>["Rank"=>14, "ManualID"=>1, "DivisionID"=>1, "Position"=>[11, 5], "Name"=>"Gambe 8"],
        15=>["Rank"=>15, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 8, 4], "Name"=>"Octave 4"],
        16=>["Rank"=>16, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 9, 3], "Name"=>"Spitzflöte 4"],
        17=>["Rank"=>17, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 7, 3], "Name"=>"Quinte 2 2/3"],
        18=>["Rank"=>18, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 8, 3], "Name"=>"Octave 2"],
        19=>["Rank"=>19, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 8, 2], "Name"=>"Mixtur Major IV/V"],
        20=>["Rank"=>20, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 7, 2], "Name"=>"Mixtur Minor IV"],
        21=>["Rank"=>21, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 7, 1], "Name"=>"Trompete 16", "Colour"=>"Dark Red"],
        22=>["Rank"=>22, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 8, 1], "Name"=>"Trompete 8", "Colour"=>"Dark Red"],
        
        23=>["Rank"=>23, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 5, 7], "Name"=>"Bourdon 16"],
        24=>["Rank"=>24, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 5, 6], "Name"=>"Principal 8"],
        25=>["Rank"=>25, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 6, 5], "Name"=>"Nachthorn Gedackt 8"],
        26=>["Rank"=>26, "ManualID"=>1, "DivisionID"=>1, "Position"=>[10, 5], "Name"=>"Corno dolce 8"],
        27=>["Rank"=>27, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 4, 6], "Name"=>"Viola 8"],
        28=>["Rank"=>28, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 6, 6], "Name"=>"Vox Celeste 8'"],
        29=>["Rank"=>29, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 4, 5], "Name"=>"Geigen- Principal 4"],
        30=>["Rank"=>30, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 5, 5], "Name"=>"Querflöte 4"],
        31=>["Rank"=>31, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 6, 4], "Name"=>"Nazard 2 2/3"],
        32=>["Rank"=>32, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 5, 4], "Name"=>"Flageolett 2"],
        33=>["Rank"=>33, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 4, 4], "Name"=>"Tierce 1 3/5"],
        34=>["Rank"=>34, "ManualID"=>1, "DivisionID"=>1, "Position"=>[11, 3], "Name"=>"Larigot 1 1/3"],
        35=>["Rank"=>35, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 6, 3], "Name"=>"Plein Jeu IV/V"],
        36=>["Rank"=>36, "ManualID"=>1, "DivisionID"=>1, "Position"=>[12, 3], "Name"=>"Scharff IV"],
        37=>["Rank"=>37, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 5, 3], "Name"=>"Trompete harmonique 8", "Colour"=>"Dark Red"],
        38=>["Rank"=>38, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 5, 2], "Name"=>"Hautbois 8", "Colour"=>"Dark Red"],
        39=>["Rank"=>39, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 6, 2], "Name"=>"Clairon 4", "Colour"=>"Dark Red"],
        
        40=>["Rank"=>40, "ManualID"=>1, "DivisionID"=>1, "Position"=>[12, 5], "Name"=>"Jubalflöte 8"],
        41=>["Rank"=>41, "ManualID"=>1, "DivisionID"=>1, "Position"=>[11, 4], "Name"=>"Trichterflöte 4"],
        42=>["Rank"=>42, "ManualID"=>1, "DivisionID"=>1, "Position"=>[10, 3], "Name"=>"Cornet a Pavilion I/VIII", "FirstNote"=>20],
        43=>["Rank"=>43, "ManualID"=>1, "DivisionID"=>1, "Position"=>[12, 2], "Name"=>"Trompete en Chamade 8", "Colour"=>"Dark Red"],
        44=>["Rank"=>44, "ManualID"=>1, "DivisionID"=>1, "Position"=>[11, 2], "Name"=>"Englischhorn 8", "Colour"=>"Dark Red"],
        
        // Extensions
        51=>["ManualID"=>0, "Position"=>[ 2, 1], "Name"=>"(SW) Contra Hautbois 16", "Colour"=>"Dark Red"],
        52=>["ManualID"=>0, "Position"=>[ 2, 2], "Name"=>"Mixture III"],
        53=>["ManualID"=>0, "Position"=>[ 3, 1], "Name"=>"(PO) Englisch Horn 8", "Colour"=>"Dark Red"],
        54=>["ManualID"=>0, "Position"=>[ 3, 3], "Name"=>"(SW) Querflöte 4"],
        55=>["ManualID"=>0, "Position"=>[ 3, 4], "Name"=>"(SW) Bourdon 16"],
        56=>["ManualID"=>0, "Position"=>[ 3, 5], "Name"=>"(HW) Praestant 16"],
        
        57=>["ManualID"=>3, "Position"=>[ 4, 3], "Name"=>"Contra Hautbois 16", "Colour"=>"Dark Red"],
        
        58=>["ManualID"=>2, "Position"=>[ 9, 1], "Name"=>"Trumpet 4", "Colour"=>"Dark Red"],
        59=>["ManualID"=>2, "Position"=>[ 7, 4], "Name"=>"(SW) Principal 8"],
        60=>["ManualID"=>2, "Position"=>[ 9, 2], "Name"=>"Wald- Flote 2"],
        
        61=>["ManualID"=>1, "Position"=>[10, 6], "Name"=>"Gamba Celeste"],
        62=>["ManualID"=>1, "Position"=>[10, 4], "Name"=>"Octave 2"],
        63=>["ManualID"=>1, "Position"=>[12, 4], "Name"=>"Prinzipal 4"],
    ];
    
    private $clonedRanks=[
        51=>[["Rank"=>38, "PitchTuning"=>-1200, "FirstKey"=>36, "DivisionID"=>0]],
        52=>[["Rank"=>15, "PitchTuning"=>-700,  "FirstKey"=>43, "DivisionID"=>0],
             ["Rank"=>15, "PitchTuning"=>-1200, "FirstKey"=>48, "DivisionID"=>0],
             ["Rank"=>15, "PitchTuning"=>-700,  "FirstKey"=>67, "DivisionID"=>0]], // 1900/55 !
        53=>[["Rank"=>44, "PitchTuning"=>0,     "FirstKey"=>36, "DivisionID"=>0]],
        54=>[["Rank"=>30, "PitchTuning"=>0,     "FirstKey"=>36, "DivisionID"=>0]],
        55=>[["Rank"=>23, "PitchTuning"=>0,     "FirstKey"=>36, "DivisionID"=>0]],
        56=>[["Rank"=>10, "PitchTuning"=>0,     "FirstKey"=>36, "DivisionID"=>0]],
        
        57=>[["Rank"=>38, "PitchTuning"=>-1200, "FirstKey"=>36, "DivisionID"=>3]],
        
        58=>[["Rank"=>22, "PitchTuning"=>1200,  "FirstKey"=>36, "DivisionID"=>2]],
        59=>[["Rank"=>24, "PitchTuning"=>0,     "FirstKey"=>36, "DivisionID"=>2]],
        60=>[["Rank"=>16, "PitchTuning"=>1200,  "FirstKey"=>36, "DivisionID"=>2]],
        
        61=>[["Rank"=>14, "PitchTuning"=>1210,  "FirstKey"=>48, "DivisionID"=>1, "FirstNote"=>13, "Keys"=>48]],
        62=>[["Rank"=>29, "PitchTuning"=>1200,  "FirstKey"=>36, "DivisionID"=>1]],
        63=>[["Rank"=>29, "PitchTuning"=>0,     "FirstKey"=>36, "DivisionID"=>1]],
    ];
    
    protected \HWClasses\HWData $hwdata;
    
    public function build() : void {
        \GOClasses\Noise::$blankloop="Data - Friesach\Noises\BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
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
        $this->cloneRanks();
    }

    protected function buildOrgan() : void {
        $general=$this->hwdata->general();
        $general["Identification_UniqueOrganID"]=2213; 
        $organ=$this->createOrgan($general);
        $organ->ChurchName .= " (extended per Les Deutch)";
        unset($organ->InfoFilename);
        $general["Name"]="Friesach Extended (after Les Deutsch)";
        $general["PanelID"]=0;
        $panel=$this->createPanel($general);
        
        $panel->DispScreenSizeHoriz=self::WIDTH;
        $panel->DispScreenSizeVert=self::HEIGHT;
        $panel->DispDrawstopCols=12;
        $panel->DispDrawstopRows=8;
        $panel->DispExtraDrawstopRows=3;
        $panel->DispExtraDrawstopCols=6;
        $panel->DispDrawstopOuterColOffsetUp="Y";

        $ped=$panel->Label("PEDAL");
        $ped->FreeXPlacement=$ped->FreeYPlacement=$ped->DispSpanDrawstopColToRight="N";
        $ped->DispAtTopOfDrawstopCol="Y";
        $ped->DispDrawstopCol=2;

        $hw=$panel->Label("HAUPTWERK");
        $hw->FreeXPlacement=$hw->FreeYPlacement=$hw->DispSpanDrawstopColToRight="N";
        $hw->DispAtTopOfDrawstopCol="Y";
        $hw->DispDrawstopCol=8;
        
        $sw=$panel->Label("SCHWELLWERK");
        $sw->FreeXPlacement=$sw->FreeYPlacement=$sw->DispSpanDrawstopColToRight="N";
        $sw->DispAtTopOfDrawstopCol="Y";
        $sw->DispDrawstopCol=5;
        
        $po=$panel->Label("POSITIF");
        $po->FreeXPlacement=$po->FreeYPlacement=$po->DispSpanDrawstopColToRight="N";
        $po->DispAtTopOfDrawstopCol="Y";
        $po->DispDrawstopCol=11;
    }

    protected function buildWindchestGroups() {
        foreach($this->divisions as $divid=>$division) {
            $this->createWindchestGroup([
                "GroupID"=>$divid,
                "Name"=>"$division"]);
        }
    }

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        $panel=$this->getPanel(0);
        $panel->GUIElement($enclosure);
    }
    
    protected function buildEnclosures() {
        foreach ($this->enclosures as $divid=>$name) {
            $data=["EnclosureID"=>$divid, "Name"=>$name, "GroupIDs"=>[$divid]];
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
        $pe->DispDrawstopCol=$switchdata["Position"][0];
        $pe->DispDrawstopRow=$switchdata["Position"][1];
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
            
            if (isset($stopdata["Rank"])) {
                $rankid=$stopdata["Rank"];
                $rankdata=$this->hwdata->rank($rankid);
                $divid=$this->stops[$stopid]["DivisionID"];
                $wcg=$this->getWindchestGroup($divid);
                $rank=$this->newRank($rankid, $rankdata["Name"] );
                $rank->WindchestGroup($wcg);
                $stop->Rank($rank);

                $ranknum=$stop->int2str($stop->NumberOfRanks);
                if (isset($stopdata["FirstNote"])) {
                    $stop->set("Rank${ranknum}FirstAccessibleKeyNumber", $stopdata["FirstNote"]);
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
        $filename=str_replace("OrganInstallationPackages/002213", "Data - Friesach", $filename);
        static $files=[];
        if (sizeof($files)==0)
            $files=$this->treeWalk(getenv("HOME") . self::ROOT);
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }
    
    public function processSample(array $hwdata, bool $isattack) : ?\GOClasses\Pipe {
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe) {
            $hwdata["Pitch_ExactSamplePitch"]=
                $this->readSamplePitch(self::ROOT . $this->sampleFilename($hwdata));
            $pipe->PitchTuning=$this->sampleTuning($hwdata);
            //echo $this->samplePitchMidi($hwdata), "\t", $this->pipePitchMidi($hwdata), "\n", $pipe, "\n";
            if ((floatval($pipe->PitchTuning)>1200) || floatval($pipe->PitchTuning)<-1200) {
                echo $pipe, "\n";
                exit();
            }
        }
        return $pipe;
    }
    
    private function cloneRanks() : void {
        foreach($this->clonedRanks as $stopid=>$clonedRanks) {
            foreach ($clonedRanks as $id=>$rankdata) {
                $rankid=$rankdata["Rank"];
                $stop=$this->getStop($stopid);
                $rank=$this->newRank("$stopid.$rankid", $this->stops[$stopid]["Name"] . " ($id)");
                $rank->WindchestGroup($this->getWindchestGroup($rankdata["DivisionID"]));
                $stop->Rank($rank);
                if (isset($rankdata["FirstNote"])) {
                    $ranknum=$stop->int2str($stop->NumberOfRanks);
                    $stop->set("Rank${ranknum}FirstAccessibleKeyNumber", $rankdata["FirstNote"]);
                }
                
                $rank->PitchTuning=$rankdata["PitchTuning"];
                $pipes=$this->getRank($rankid)->Pipes();
                $manual=$this->getManual($this->stops[$stopid]["ManualID"]);
                $keys=isset($rankdata["Keys"]) ? $rankdata["Keys"] : $manual->NumberOfLogicalKeys;
                $fkey=$rankdata["FirstKey"];
                for ($i=0; $i<$keys; $i++) {
                    if (isset($pipes[$fkey+$i])) {
                        $rank->Pipe(36+$i, $pipes[$fkey+$i]);
                    }
                    else {
                        $pipe=$rank->Pipe(36+$i, $pipes[$fkey+$i-2]); 
                        $pipe->PitchTuning+=200;
                    }
                }
            }
        }
    }

    /**
     * Run the build
     */
    public static function FriesachExt() {
        $hwi=new FriesachExt(self::SOURCE);
        $hwi->build();
        echo $hwi->getOrgan()->ChurchName, "\n";
        $hwi->getPanel(0)->Name="Friesach Extended";
        $hwi->saveODF(self::TARGET, self::COMMENTS);
    }
}

FriesachExt::FriesachExt();
