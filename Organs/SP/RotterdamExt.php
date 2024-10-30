<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\SP;
require_once __DIR__ . "/SPExtended.php";

/**
 * Create bespoke organ based on Rotterdam Laurenskerk Hoofdorgel demo from SP
 * 
 * @author andrew
 */
class RotterdamExt extends SPExtended {
    const ROOT="/GrandOrgue/Organs/SP/Rotterdam/";
    const SOURCE="OrganDefinitions/Rotterdam - Laurenskerk, Hoofdorgel DEMO.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Rotterdam - Laurenskerk, Hoofdorgel (Extended Demo - %s) 0.3.organ";

    protected string $rootdir=self::ROOT;
    
    protected array $positions=[];
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,   9=>self::RANKS_DIRECT,
        1=>self::RANKS_DIRECT,
        2=>self::RANKS_DIRECT, 
        4=>self::RANKS_REAR,     8=>self::RANKS_REAR
    ];

    protected array $divisions=[
        0=>"Pedal", 
        1=>"Great",
        2=>"Positive",
        3=>"Solo"
    ];
    
    protected array $enclosures=[
        2=>"Rec",
        3=>"Solo"
    ];

    protected array $manuals=[ 
        0=>"Pedal", 
        1=>"Great",
        2=>"Positive",
        3=>"Solo"
    ];

    protected array $tremulants=[ 
        2=>["Name"=>"Pos",  "SwitchID"=>203, "Type"=>"Switched", "Position"=>[4, 6]],
        3=>["Name"=>"Solo", "SwitchID"=>204, "Type"=>"Synth",    "Position"=>[6, 3],
            "GroupIDs"=>[301,304]],
    ];

    protected array $couplers=[
        301=>["Name"=>"GO/Ped",   "SourceKeyboardID"=>0, "DestKeyboardID"=>1, "Position"=>[8, 4]],
        302=>["Name"=>"Pos/Ped",  "SourceKeyboardID"=>0, "DestKeyboardID"=>2, "Position"=>[9, 5]],
        303=>["Name"=>"Solo/Ped", "SourceKeyboardID"=>0, "DestKeyboardID"=>3, "Position"=>[8, 6]],
        311=>["Name"=>"Pos/GO",   "SourceKeyboardID"=>1, "DestKeyboardID"=>2, "Position"=>[4, 1]],
        312=>["Name"=>"Solo/GO",   "SourceKeyboardID"=>1, "DestKeyboardID"=>3, "Position"=>[4, 2]],
        321=>["Name"=>"Solo/Pos", "SourceKeyboardID"=>2, "DestKeyboardID"=>3, "Position"=>[4, 5]],
    ];
    
    protected array $stops=[
        18=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 1], "Name"=>"Octaaf 8"], 
        20=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 1], "Name"=>"Octaaf 4"], 
        21=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 1], "Name"=>"Octaaf 2"], 
        77=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 2], "Name"=>"Roerfluit 8"], 
        80=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 2], "Name"=>"Openfluit 4"], 
        78=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 2], "Name"=>"Viola di Gamba 8"], 
        82=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[1, 3], "Name"=>"Roerquint 2 2/3"], 
        84=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[2, 3], "Name"=>"Terts 1 3/5"], 
        24=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[3, 3], "Name"=>"Scherp"], 
        74=>["ManualID"=>1, "DivisionID"=>1, "Position"=>[4, 3], "Name"=>"Trompet 16", "Colour"=>"Dark Red"], 

        46=>["ManualID"=>3, "DivisionID"=>3, "Position"=>[6, 1], "Name"=>"(B) Clarin fuerte 4", "Colour"=>"Dark Red"], 
        50=>["ManualID"=>3, "DivisionID"=>3, "Position"=>[7, 2], "Name"=>"(D) Trompeta batalla 8", "FirstPipe"=>-26, "Colour"=>"Dark Red"], 

        63=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 4], "TremulantID"=>2, "Name"=>"Holpijp 8"], 
        85=>["ManualID"=>2, "DivisionID"=>3, "Position"=>[2, 4], "Name"=>"Blokfluit 4"],        
        66=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[3, 4], "TremulantID"=>2, "Name"=>"Woudfluit 2"], 
        27=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 5], "TremulantID"=>2, "Name"=>"Octaaf 4"], 
         3=>["ManualID"=>2, "DivisionID"=>3, "Position"=>[2, 5], "Name"=>"Octaaf 2"], 
        31=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[3, 5], "TremulantID"=>2, "Name"=>"Mixtuur"], 
        70=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[1, 6], "TremulantID"=>2, "Name"=>"Kromhoorn 8", "Colour"=>"Dark Red"], 
        15=>["ManualID"=>2, "DivisionID"=>2, "Position"=>[2, 6], "TremulantID"=>2, "Name"=>"Voix humaine 8", "Colour"=>"Dark Red"], 

        53=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 4], "Name"=>"Open Subbass 16"], 
        35=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 5], "Name"=>"Octaaf 8"], 
        40=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[8, 5], "Name"=>"Bazuin 16", "Colour"=>"Dark Red"], 
        59=>["ManualID"=>0, "DivisionID"=>0, "Position"=>[7, 6], "Name"=>"Dwarsfluit 1"],
    ];

    //protected \HWClasses\HWData $hwdata;
    
    public function build() : void {
        \GOClasses\Manual::$keys=58;
        parent::build();
    }

    protected function buildOrgan() : void {
        $general=$this->hwdata->general();
        $general["Identification_UniqueOrganID"]=891; 
        $this->createOrgan($general);
        $general["Name"]="Rotterdam Extended";
        $general["PanelID"]=0;
        $panel=$this->createPanel($general);
        
        $panel->DispScreenSizeHoriz=intval(800*19/11);
        $panel->DispScreenSizeVert=800;
        $panel->DispDrawstopCols=6;
        $panel->DispDrawstopRows=9;

        $go=$panel->Label("Great");
        $go->FreeXPlacement=$go->FreeYPlacement=$go->DispSpanDrawstopColToRight="N";
        $go->DispAtTopOfDrawstopCol="Y";
        $go->DispDrawstopCol=2;
        
        $ped=$panel->Label("Solo");
        $ped->FreeXPlacement=$ped->DispSpanDrawstopColToRight="N";
        $ped->DispYpos=400;
        $ped->DispDrawstopCol=2;

        $sw=$panel->Label("Positive");
        $sw->FreeXPlacement=$sw->FreeYPlacement=$sw->DispSpanDrawstopColToRight="N";
        $sw->DispAtTopOfDrawstopCol="Y";
        $sw->DispDrawstopCol=5;
        
        $po=$panel->Label("Pedal");
        $po->FreeXPlacement=$po->DispSpanDrawstopColToRight="N";
        $po->DispYpos=400;
        $po->DispDrawstopCol=5;
    }

    /**
     * Run the build
     */
    public static function RotterdamExt(array $positions=[], string $target="") {
        if (sizeof($positions)>0) {
            $hwi=new RotterdamExt(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->build();
            $hwi->getOrgan()->ChurchName=str_replace("Demo", "$target (Extended Demo)", $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getPanel(0)->Name="Rotterdam Dom Extended ($target)";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::RotterdamExt(
                    [self::RANKS_DIRECT=>"Front"],
                    "Front");
            self::RotterdamExt(
                    [self::RANKS_REAR=>"Rear"],
                    "Rear");
            self::RotterdamExt(
                    [
                        self::RANKS_DIRECT=>"Front", 
                        self::RANKS_REAR=>"Rear"
                    ],
                   "Surround");
        }
    }
}
RotterdamExt::RotterdamExt();