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
 * Create extension of Friesach as implemented for HW by Al Morse
 * 
 * @author andrew
 */
class FriesachAM extends \Import\Organ {
    const ROOT="/GrandOrgue/Organs/PG/Friesach/";
    const SOURCE=self::ROOT . "OrganDefinitions/FriesachExtendV2.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Friesach Extended (Al Morse) 1.1.organ";
    const COMMENTS="\n"
            . "1.1 Cross fades corrected for GO 3.14\n"
            . "\n";
    const WIDTH=1900;
    const HEIGHT=1000;
    const FONTSIZE=10;

    private $divisions=[
        0=>"Pedal",
        1=>"Great",
        2=>"Swell",
        3=>"Choir",
        4=>"Solo",
    ];
    
    private $enclosures=[
        2=>"Swell",
        3=>"Choir",
        4=>"Solo"
    ];

    private $manuals=[ 
        0=>"Pedal",
        1=>"Great",
        2=>"Swell",
        3=>"Choir",
        4=>"Solo"
    ];

    private $tremulants=[ 
        2=>["TremulantID"=>2, "Name"=>"Swell", "SwitchID"=>201, "Type"=>"Synth",
            "Period"=>250, "StartRate"=>50, "StopRate"=>30, "AmpModDepth"=>6, 
            "DivisionID"=>2, "Position"=>[ 5,11], "GroupIDs"=>[2]], 
        3=>["TremulantID"=>3, "Name"=>"Choir", "SwitchID"=>202, "Type"=>"Synth", 
            "Period"=>208, "StartRate"=>30, "StopRate"=>70, "AmpModDepth"=>8, 
            "DivisionID"=>3, "Position"=>[ 8, 9], "GroupIDs"=>[3]],
        4=>["TremulantID"=>4, "Name"=>"Solo", "SwitchID"=>203, "Type"=>"Synth", 
            "Period"=>208, "StartRate"=>30, "StopRate"=>70, "AmpModDepth"=>8, 
            "DivisionID"=>4, "Position"=>[ 9, 7], "GroupIDs"=>[4]],
    ];

    private $couplers=[
        201=>["Name"=>"GT 4",       "SourceKeyboardID"=>1, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 4, 8]],
        202=>["Name"=>"GT 16",      "SourceKeyboardID"=>1, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 4, 9]],
        203=>["Name"=>"Unison Off", "SourceKeyboardID"=>1, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 3, 9]],

        221=>["Name"=>"SW 4",       "SourceKeyboardID"=>2, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 6, 9]],
        222=>["Name"=>"SW 16",      "SourceKeyboardID"=>2, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 6,10]],
        223=>["Name"=>"Unison Off", "SourceKeyboardID"=>2, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 5,10]],

        211=>["Name"=>"CH 4",       "SourceKeyboardID"=>3, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 7, 8]],
        212=>["Name"=>"CH 16",      "SourceKeyboardID"=>3, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 7, 9]],
        213=>["Name"=>"Unison Off", "SourceKeyboardID"=>3, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 8, 8]],
        
        301=>["Name"=>"GT Ped 8", "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 1, 100]],
        302=>["Name"=>"GT Ped 4", "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 2, 100]],
        303=>["Name"=>"SW Ped 8", "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 4, 100]],
        304=>["Name"=>"SW Ped 4", "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 5, 100]],
        305=>["Name"=>"CH Ped 4", "SourceKeyboardID"=>0, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 7, 100]],
        306=>["Name"=>"CH Ped 4", "SourceKeyboardID"=>0, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 8, 100]],
        307=>["Name"=>"SO Ped 8", "SourceKeyboardID"=>0, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>0,   "Position"=>[10, 100]],
        308=>["Name"=>"SO Ped 4", "SourceKeyboardID"=>0, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>+12, "Position"=>[11, 100]],
        
        311=>["Name"=>"SW GT 16", "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 1, 101]],
        312=>["Name"=>"SW GT 8",  "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 2, 101]],
        313=>["Name"=>"SW GT 4",  "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 3, 101]],
        314=>["Name"=>"CH GT 16", "SourceKeyboardID"=>1, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 5, 101]],
        315=>["Name"=>"CH GT 8",  "SourceKeyboardID"=>1, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 6, 101]],
        316=>["Name"=>"CH GT 4",  "SourceKeyboardID"=>1, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 7, 101]],
        317=>["Name"=>"SO GT 16", "SourceKeyboardID"=>1, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 9, 101]],
        318=>["Name"=>"SO GT 8",  "SourceKeyboardID"=>1, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>0,   "Position"=>[10, 101]],
        319=>["Name"=>"SO GT 4",  "SourceKeyboardID"=>1, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>+12, "Position"=>[11, 101]],
        
        321=>["Name"=>"CH SW 16", "SourceKeyboardID"=>2, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 1, 102]],
        322=>["Name"=>"CH SW 8",  "SourceKeyboardID"=>2, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 2, 102]],
        323=>["Name"=>"CH SW 4",  "SourceKeyboardID"=>2, "DestKeyboardID"=>3, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 3, 102]],
        324=>["Name"=>"SO SW 16", "SourceKeyboardID"=>2, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 5, 102]],
        325=>["Name"=>"SO SW 8",  "SourceKeyboardID"=>2, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>0,   "Position"=>[ 6, 102]],
        326=>["Name"=>"SO SW 4",  "SourceKeyboardID"=>2, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>+12, "Position"=>[ 7, 102]],

        331=>["Name"=>"SO CH 16", "SourceKeyboardID"=>3, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>-12, "Position"=>[ 9, 102]],
        332=>["Name"=>"SO CH 8",  "SourceKeyboardID"=>3, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>0,   "Position"=>[10, 102]],
        333=>["Name"=>"SO CH 4",  "SourceKeyboardID"=>3, "DestKeyboardID"=>4, "MIDINoteNumberIncrement"=>+12, "Position"=>[11, 102]],
        
    ];
    
    private $stops=[
      2101=>["Rank"=>101, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 1], "Name"=>"Prestant 16"],
      2102=>["Rank"=>102, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 2], "Name"=>"Principal 8"],
      2103=>["Rank"=>103, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 3], "Name"=>"RohrFlote 8"],
      2104=>["Rank"=>104, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 4], "Name"=>"HolzFlote 8"],
      2105=>["Rank"=>105, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 5], "Name"=>"Gamba 8"],
      2106=>["Rank"=>106, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 6], "Name"=>"Octave 4"],
      2107=>["Rank"=>107, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 7], "Name"=>"SpitzFlote 4"],
      2108=>["Rank"=>108, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 3, 8], "Name"=>"Quinte 2.2/3"],
      2109=>["Rank"=>109, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 1], "Name"=>"WaldFlote 2*"],
      2110=>["Rank"=>110, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 2], "Name"=>"Octave 2"],
      2111=>["Rank"=>111, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 3], "Name"=>"Mixtur Minor"],
      2112=>["Rank"=>112, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 4], "Name"=>"Mixtur Major"],
      2113=>["Rank"=>113, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 5], "Name"=>"Trompete 16", "Colour"=>"Dark Red"], 
      2114=>["Rank"=>114, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 6], "Name"=>"Trompete 8", "Colour"=>"Dark Red"], 
      2115=>["Rank"=>114, "ManualID"=>1, "DivisionID"=>1, "Position"=>[ 4, 7], "Name"=>"Trompete 4*", "Colour"=>"Dark Red", "FirstNote"=>13], 

      2201=>["Rank"=>201, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 1], "Name"=>"Bordun 16"],
      2202=>["Rank"=>202, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 2], "Name"=>"Viola 8"],
      2203=>["Rank"=>203, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 3], "Name"=>"Principal 8"],
      2204=>["Rank"=>204, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 4], "Name"=>"Nach Gedackt 8"],
      2205=>["Rank"=>205, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 5], "Name"=>"Vox Celeste 8"],
      2206=>["Rank"=>206, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 6], "Name"=>"Corno Dolce 8"],
      2207=>["Rank"=>207, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 7], "Name"=>"Quer Flote 4"],
      2208=>["Rank"=>208, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 8], "Name"=>"Nazard 2.2/3"],
      2209=>["Rank"=>209, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 5, 9], "Name"=>"Geigen Principal 4"],
      2210=>["Rank"=>210, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 1], "Name"=>"Tierce"],
      2211=>["Rank"=>211, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 2], "Name"=>"Larigot 1.1/3"],
      2212=>["Rank"=>212, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 3], "Name"=>"Flag- eolett 2"],
      2213=>["Rank"=>213, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 4], "Name"=>"Plein Jeu"], 
      2214=>["Rank"=>214, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 5], "Name"=>"Hautbois 8", "Colour"=>"Dark Red"], 
      2215=>["Rank"=>215, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 6], "Name"=>"Scharff"], 
      2216=>["Rank"=>216, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 7], "Name"=>"Tromp. Harmonic 8", "Colour"=>"Dark Red"], 
      2218=>["Rank"=>218, "ManualID"=>2, "DivisionID"=>2, "Position"=>[ 6, 8], "Name"=>"Clarion 4", "Colour"=>"Dark Red"], 
        
      2301=>["Rank"=>301, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 7, 1], "Name"=>"Principal 8*"],
      2302=>["Rank"=>302, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 7, 2], "Name"=>"Gamba 8*"],
      2303=>["Rank"=>303, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 7, 3], "Name"=>"Flute 8*"],
      2304=>["Rank"=>304, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 7, 4], "Name"=>"Flute Celeste 8*"],
      2305=>["Rank"=>305, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 7, 5], "Name"=>"Gamba Celeste 8*"],
      2306=>["Rank"=>306, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 7, 6], "Name"=>"Hohl Flote 8*"],
      2307=>["Rank"=>307, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 7, 7], "Name"=>"Principal 4*"],
      2308=>["Rank"=>308, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 8, 1], "Name"=>"Quint 2.2/3*"],
      2309=>["Rank"=>309, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 8, 2], "Name"=>"Flute4*"],
      2310=>["Rank"=>310, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 8, 3], "Name"=>"Tierce*"],
      2311=>["Rank"=>311, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 8, 4], "Name"=>"Quint 1.1/3*"],
      2312=>["Rank"=>312, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 8, 5], "Name"=>"Octave 2*"],
      2313=>["Rank"=>312, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 8, 6], "Name"=>"Sifflote 1*", "FirstNote"=>13],
      2314=>["Rank"=>314, "ManualID"=>3, "DivisionID"=>3, "Position"=>[ 8, 7], "Name"=>"Tromba 8*", "Colour"=>"Dark Red"], 

      2401=>["Rank"=>401, "ManualID"=>4, "DivisionID"=>4, "Position"=>[ 9, 1], "Name"=>"Jubal Flote 8"],
      2402=>["Rank"=>402, "ManualID"=>4, "DivisionID"=>4, "Position"=>[ 9, 2], "Name"=>"Tichter Flote 4"],
      2403=>["Rank"=>403, "ManualID"=>4, "DivisionID"=>4, "Position"=>[ 9, 3], "Name"=>"Cornet a Pavilon", "FirstKey"=>20], 
      2404=>["Rank"=>404, "ManualID"=>4, "DivisionID"=>4, "Position"=>[ 9, 4], "Name"=>"English Horn 8", "Colour"=>"Dark Red"],
      2405=>["Rank"=>405, "ManualID"=>4, "DivisionID"=>4, "Position"=>[ 9, 5], "Name"=>"Chamade 8", "Colour"=>"Dark Red"],
      2406=>["Rank"=>406, "ManualID"=>4, "DivisionID"=>4, "Position"=>[ 9, 6], "Name"=>"Carillon*"],

      2001=>["Rank"=>  1, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 1], "Name"=>"Unterstatz 32"],
      2002=>["Rank"=>  2, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 2], "Name"=>"Contra bass 16"],
      2003=>["Rank"=>101, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 3], "Name"=>"Principal 16*"],
      2004=>["Rank"=>  4, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 4], "Name"=>"Subbass 16"],
      2005=>["Rank"=>201, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 5], "Name"=>"Bourdon 16*"],
      2006=>["Rank"=>  6, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 6], "Name"=>"Octave Bass 8"],
      2007=>["Rank"=>201, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 7], "Name"=>"Bourdon 8*", "FirstNote"=>13], 
      2008=>["Rank"=>207, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 1, 8], "Name"=>"Quer Flote 4*"],
      2009=>["Rank"=>  9, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 1], "Name"=>"Gedackt 8"],
      2010=>["Rank"=> 10, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 2], "Name"=>"Choral Bass 4"],
      2011=>["Rank"=> 11, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 3], "Name"=>"Posaune 32", "Colour"=>"Dark Red"],
      2012=>["Rank"=> 12, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 4], "Name"=>"Mixture III*"],
      2013=>["Rank"=> 13, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 5], "Name"=>"Posaune 16", "Colour"=>"Dark Red"],
      2014=>["Rank"=> 14, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 6], "Name"=>"Trompete 8", "Colour"=>"Dark Red"],
      2015=>["Rank"=> 15, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 7], "Name"=>"Fagott 16*", "Colour"=>"Dark Red"],
      2016=>["Rank"=> 15, "ManualID"=>0, "DivisionID"=>0, "Position"=>[ 2, 8], "Name"=>"Fagott 8*", "Colour"=>"Dark Red", "FirstNote"=>13],
    ];
    
    private $clonedRanks=[
   ];
    
    protected \HWClasses\HWData $hwdata;
    
    public function build() : void {
        \GOClasses\Noise::$blankloop="Data - Friesah/Noises/BlankLoop.wav";
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
        foreach([403,404] as $rankid) {
            $rank=$this->getRank($rankid);
            for ($midi=93; $midi<98; $midi++) {
                $pipe=$rank->Pipe($midi,$rank->Pipe(92));
                $pipe->PitchTuning=100*($midi-92);
            }
        }
        $this->cloneRanks();
    }

    protected function buildOrgan() : void {
        $general=$this->hwdata->general();
        $general["Identification_UniqueOrganID"]=2213; 
        $organ=$this->createOrgan($general);
        unset($organ->InfoFilename);
        $general["Name"]=$organ->ChurchName="Friesach Extended (after Al Morse)";
        $general["PanelID"]=0;
        $panel=$this->createPanel($general);
        
        $panel->DispScreenSizeHoriz=self::WIDTH;
        $panel->DispScreenSizeVert=self::HEIGHT;
        $panel->DispDrawstopCols=9;
        $panel->DispDrawstopRows=11;
        $panel->DispExtraDrawstopRows=3;
        $panel->DispExtraDrawstopCols=11;
        $panel->DispDrawstopOuterColOffsetUp="Y";

        $ped=$panel->Label("PEDAL");
        $ped->FreeXPlacement=$ped->FreeYPlacement=$ped->DispSpanDrawstopColToRight="N";
        $ped->DispAtTopOfDrawstopCol="Y";
        $ped->DispDrawstopCol=1;
        $ped->DispSpanDrawstopColToRight="Y";

        $gt=$panel->Label("GREAT");
        $gt->FreeXPlacement=$gt->FreeYPlacement=$gt->DispSpanDrawstopColToRight="N";
        $gt->DispAtTopOfDrawstopCol="Y";
        $gt->DispDrawstopCol=3;
        $gt->DispSpanDrawstopColToRight="Y";
        
        $sw=$panel->Label("SWELL");
        $sw->FreeXPlacement=$sw->FreeYPlacement=$sw->DispSpanDrawstopColToRight="N";
        $sw->DispAtTopOfDrawstopCol="Y";
        $sw->DispDrawstopCol=5;
        $sw->DispSpanDrawstopColToRight="Y";
        
        $ch=$panel->Label("CHOIR");
        $ch->FreeXPlacement=$ch->FreeYPlacement=$ch->DispSpanDrawstopColToRight="N";
        $ch->DispAtTopOfDrawstopCol="Y";
        $ch->DispDrawstopCol=7;
        $ch->DispSpanDrawstopColToRight="Y";

        $so=$panel->Label("SOLO");
        $so->FreeXPlacement=$so->FreeYPlacement=$so->DispSpanDrawstopColToRight="N";
        $so->DispAtTopOfDrawstopCol="Y";
        $so->DispDrawstopCol=9;
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
        $pe->DispLabelFontSize=self::FONTSIZE;

    }

    protected function buildTremulants() : void {
        foreach($this->tremulants as $id=>$tremdata) {
            $tremdata["Colour"]="Dark Green";
            $tremdata["Label"]="Tremulant";
            $this->createTremulant($tremdata);
        }
    }

    protected function buildCouplers() : void {
        foreach($this->couplers as $id=>$cplrdata) {
            $cplrdata["Colour"]="Dark Blue";
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
                $rank=$this->getRank($rankid);
                if (!$rank) {
                    $rank=$this->newRank($rankid, $rankdata["Name"]);
                    $divid=$this->stops[$stopid]["DivisionID"];
                    $wcg=$this->getWindchestGroup($divid);
                    $rank->WindchestGroup($wcg);
                }
                $stop->Rank($rank);

                $ranknum=$stop->int2str($stop->NumberOfRanks);
                if (isset($stopdata["FirstNote"])) {
                    $stop->set("Rank${ranknum}FirstPipeNumber", $stopdata["FirstNote"]);
                }
                if (isset($stopdata["FirstKey"])) {
                    $stop->set("Rank${ranknum}FirstAccessibleKeyNumber", $stopdata["FirstKey"]);
                }
            }
        }
        $stop12=$this->getStop(2012);
        $stop12->Rank($this->getRank(12));
        $stop12->set("Rank002FirstPipeNumber", 13);
        $stop12->Rank($this->getRank(108));
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
        $filename=str_replace("//", "/", $filename);
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
            /* $hwdata["Pitch_ExactSamplePitch"]=
                $this->readSamplePitch(self::ROOT . $this->sampleFilename($hwdata));
            $pipe->PitchTuning=$this->sampleTuning($hwdata); */
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
    public static function FriesachAM() {
        $hwi=new FriesachAM(self::SOURCE);
        $hwi->build();
        echo $hwi->getOrgan()->ChurchName, "\n";
        $hwi->getPanel(0)->Name="Friesach Extended";
        $hwi->saveODF(self::TARGET, self::COMMENTS);
    }
}

FriesachAM::FriesachAM();