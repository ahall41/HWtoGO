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
 * Create bespoke organ based on Monaster Dom demo from Orges a Tuyaux
 * 
 * @author andrew
 */
class Monaster extends \Import\Organ {
    const ROOT="/GrandOrgue/Organs/Monaster/";
    const SOURCE="OrganDefinitions/MONASTER (001512).Organ_Hauptwerk_xml";
    const PACKAGE="OrganInstallationPackages/001512/";
    const TARGET=self::ROOT . "MONASTER (001512) 1.0.organ";

    private $divisions=[
        0=>"Pedale",
        1=>"Hauptwerk",
        2=>"Positiv",
        3=>"Oberwerk",
        4=>"Schwellwerk",
        5=>"Solo"
    ];
    
    private $enclosures=[
        2=>"Pos",
        4=>"Schw"
    ];

    private $manuals=[ 
        0=>"Pedale", 
        1=>"Hauptwerk",
        2=>"Positiv",
        3=>"Oberwerk",
        4=>"Schwellwerk",
        5=>"Solo"
    ];

    private $tremulants=[ 
        4=>["TremulantID"=>4, "Name"=>"IV",  "SwitchID"=>1740, "Type"=>"Switched", "Panel"=>0, "Position"=>[3, 4]],
    ];

    private $couplers=[
        1006=>["Name"=>"I/P",   "SourceKeyboardID"=>0, "DestKeyboardID"=>1],
        1011=>["Name"=>"II/P",  "SourceKeyboardID"=>0, "DestKeyboardID"=>2],
        1016=>["Name"=>"III/P", "SourceKeyboardID"=>0, "DestKeyboardID"=>3],
        1021=>["Name"=>"IV/P",  "SourceKeyboardID"=>0, "DestKeyboardID"=>4],

        1111=>["Name"=>"II/I",   "SourceKeyboardID"=>1, "DestKeyboardID"=>2],
        1116=>["Name"=>"III/I",  "SourceKeyboardID"=>1, "DestKeyboardID"=>3],
        1121=>["Name"=>"IV/I",   "SourceKeyboardID"=>1, "DestKeyboardID"=>4],
        1127=>["Name"=>"V/I+",   "SourceKeyboardID"=>1, "DestKeyboardID"=>5, "MIDINoteNumberIncrement"=>12],
        1326=>["Name"=>"V/I",    "SourceKeyboardID"=>1, "DestKeyboardID"=>5],

        1216=>["Name"=>"III/II", "SourceKeyboardID"=>2, "DestKeyboardID"=>3],
        1221=>["Name"=>"IV/II",  "SourceKeyboardID"=>2, "DestKeyboardID"=>4],
        1226=>["Name"=>"V/II",   "SourceKeyboardID"=>2, "DestKeyboardID"=>5],
        
        1321=>["Name"=>"IV/III", "SourceKeyboardID"=>3, "DestKeyboardID"=>4],
        1326=>["Name"=>"V/III",  "SourceKeyboardID"=>3, "DestKeyboardID"=>5],
        
        1426=>["Name"=>"V/IV",   "SourceKeyboardID"=>4, "DestKeyboardID"=>5],
    ];
    
    private $stops=[
        2001=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2001, "Name"=>"1 Basson 16", "Colour"=>"Dark Red"],
        2002=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2002, "Name"=>"2 Bombarde 16", "Colour"=>"Dark Red"],
        2003=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2003, "Name"=>"3 Trompette 8", "Colour"=>"Dark Red"],
        2004=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2004, "Name"=>"4 Clairon 4", "Colour"=>"Dark Red"],
        2005=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2009, "Name"=>"5 Contrebasse 32", "Label"=>"Principal- Untersatz 32"],
        2006=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2010, "Name"=>"6 Principal 16", "Label"=>"Principalbass 16"],
        2007=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2011, "Name"=>"7 Soubasse 16"],
        2008=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2012, "Name"=>"8 Basse 8"],
        2009=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2013, "Name"=>"9 Bourdon 8"],
        2010=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2014, "Name"=>"10 Prestant 4"],
        2011=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2015, "Name"=>"11 Flute Con. 2"],
        2012=>["ManualID"=>0, "DivisionID"=>0, "SwitchID"=>2016, "Name"=>"12 Quinte 2-2/3"],

        2113=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2117, "Name"=>"13 Flute Con. 2"],
        //2114=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2118, "Name"=>"14 Fourniture V"],
        2115=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2119, "Name"=>"15 Fourniture IV"],
        2116=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2120, "Name"=>"16 Trompette 16", "Colour"=>"Dark Red"],
        2117=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2121, "Name"=>"17 Trompette 8", "Colour"=>"Dark Red"],
        2118=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2122, "Name"=>"18 Clairon 4", "Colour"=>"Dark Red"],
        2119=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2125, "Name"=>"19 Bourdon 16"],
        2120=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2126, "Name"=>"20 Principal 8"],
        2121=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2127, "Name"=>"21 Cor de Chamois 8"],
        2122=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2128, "Name"=>"22 Bourdon 8"],
        2123=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2129, "Name"=>"23 Flute Cheminee 8"],
        2124=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2130, "Name"=>"24 Prestant 4"],
        2125=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2131, "Name"=>"25 Flute Harm. 4"],
        2126=>["ManualID"=>1, "DivisionID"=>1, "SwitchID"=>2132, "Name"=>"26 Quinte 2-2/3"],

        2227=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2233, "Name"=>"27 Tierce 1-3/5"],
        2228=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2234, "Name"=>"28 Mixture IV"],
        2229=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2235, "Name"=>"29 Trompette 8", "Colour"=>"Dark Red"],
        2230=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2236, "Name"=>"30 Cromorne 8", "Colour"=>"Dark Red"],
        2231=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2241, "Name"=>"31 Bourdon 16"],
        2232=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2242, "Name"=>"32 Principal 8"],
        2233=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2243, "Name"=>"33 Flute Cheminee 8"],
        2234=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2244, "Name"=>"34 Gambe 8"],
        2235=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2245, "Name"=>"35 Flute Trav. 4"],
        2236=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2246, "Name"=>"36 Prestant 4"],
        2237=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2247, "Name"=>"37 Quinte 2-2/3"],
        2238=>["ManualID"=>2, "DivisionID"=>2, "SwitchID"=>2248, "Name"=>"38 Doublette 2"],

        2339=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2349, "Name"=>"39 Piccolo 1"],
        2340=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2350, "Name"=>"40 Cymbale III"],
        2341=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2351, "Name"=>"41 Voix Humaine 8", "Colour"=>"Dark Red"],
        2342=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2352, "Name"=>"42 Regale 8", "Colour"=>"Dark Red"],
        2343=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2357, "Name"=>"43 Salicional 8"],
        2344=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2358, "Name"=>"44 Bourdon 8"],
        2345=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2359, "Name"=>"45 Prestant 4"],
        2346=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2360, "Name"=>"46 Flute 4"],
        2347=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2361, "Name"=>"47 Nasard 2-2/3"],
        2348=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2362, "Name"=>"48 Flute Harm. 4"],
        2349=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2363, "Name"=>"49 Tierce 1-3/5"],
        2350=>["ManualID"=>3, "DivisionID"=>3, "SwitchID"=>2364, "Name"=>"50 Larigot 1-1/3"],

        2451=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2465, "Name"=>"51 Flute fuseau 4"],
        2452=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2466, "Name"=>"52 Flute Con. 2"],
        2453=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2467, "Name"=>"53 Cornet III"],
        2454=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2468, "Name"=>"54 Cymbale III"],
        2455=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2469, "Name"=>"55 Basson 16", "Colour"=>"Dark Red"],
        2456=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2470, "Name"=>"56 Trompette Harm. 8", "Colour"=>"Dark Red"],
        2457=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2471, "Name"=>"57 Clairon 4", "Colour"=>"Dark Red"],
        2458=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2473, "Name"=>"58 Bourdon 16"],
        2459=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2474, "Name"=>"59 Montre 8"],
        2460=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2475, "Name"=>"60 Flute Cheminee 8"],
        2461=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2476, "Name"=>"61 Flute Douce 8"],
        2462=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2477, "Name"=>"62 Salicional 8"],
        2463=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2478, "Name"=>"63 Undamaris 8"],
        2464=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2479, "Name"=>"64 Quintaton 8"],
        2465=>["ManualID"=>4, "DivisionID"=>4, "SwitchID"=>2480, "Name"=>"65 Prestant 4"],

        2566=>["ManualID"=>5, "DivisionID"=>5, "SwitchID"=>2589, "Name"=>"66 Bourdon 16"],
        2567=>["ManualID"=>5, "DivisionID"=>5, "SwitchID"=>2590, "Name"=>"67 Viole de Gambe 8"],
        2568=>["ManualID"=>5, "DivisionID"=>5, "SwitchID"=>2591, "Name"=>"68 Regale 8", "Colour"=>"Dark Red"],
        2569=>["ManualID"=>5, "DivisionID"=>5, "SwitchID"=>2592, "Name"=>"69 Hautbois 8", "Colour"=>"Dark Red"],
        2570=>["ManualID"=>5, "DivisionID"=>5, "SwitchID"=>2593, "Name"=>"70 Tuba 8", "Colour"=>"Dark Red"],
        2571=>["ManualID"=>5, "DivisionID"=>5, "SwitchID"=>2594, "Name"=>"71 Flute Trav. 4"],
        2572=>["ManualID"=>5, "DivisionID"=>5, "SwitchID"=>2595, "Name"=>"72 Doublette 2"],
        2573=>["ManualID"=>5, "DivisionID"=>5, "SwitchID"=>2596, "Position"=>[11, 8], "Panel"=>1, "Name"=>"73 Bourdon 8"],
    ];

    protected \HWClasses\HWData $hwdata;
    
    public function __construct($xmlfile) {
        if (!file_exists($xmlfile))
            $xmlfile=getenv("HOME") . $xmlfile;
        $this->hwdata = new \HWClasses\HWData($xmlfile);
    }
    
    public function build() : void {
        \GOClasses\Manual::$pedals=32;
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
        $this->addVirtualKeyboards(5, [1,2,3,4,5], [1,2,3,4,5]);
    }

    protected function buildOrgan() : void {
        $general=$this->hwdata->general();
        unset($general["OrganInfo_InfoFilename"]);
        $this->createOrgan($general);
        $general["Name"]="Monaster";
        $general["PanelID"]=1;
        $panel=$this->createPanel($general);
        
        $pi=$panel->Image(self::PACKAGE . "images/basilica.png");
        $pi->Width=$panel->DispScreenSizeHoriz=intval(1024);
        $pi->Height=$panel->DispScreenSizeVert=680;
        
        $left=$this->createPanel(["PanelID"=>2, "Name"=>"Left Jamb"]);
        $pil=$left->Image(self::PACKAGE . "images/Background-001.png");
        $pil->Width=$left->DispScreenSizeHoriz=intval(900);
        $pil->Height=$left->DispScreenSizeVert=600;
        $ped=$left->Label("PED.");
        $ped->Image=self::PACKAGE . "images/Label-001-36x18.png";
        $ped->FreeXPlacement=$ped->FreeYPlacement="Y";
        $ped->DispLabelColour="White";
        $ped->DispLabelFontSize="10";
        $ped->DispXpos=160;
        $ped->DispYpos=10;
        $man1=$left->Label("MAN.1");
        $man1->Image=self::PACKAGE . "images/Label-001-36x18.png";
        $man1->FreeXPlacement=$man1->FreeYPlacement="Y";
        $man1->DispLabelColour="White";
        $man1->DispLabelFontSize="10";
        $man1->DispXpos=430;
        $man1->DispYpos=10;
        $man2=$left->Label("MAN.2");
        $man2->Image=self::PACKAGE . "images/Label-001-36x18.png";
        $man2->FreeXPlacement=$man2->FreeYPlacement="Y";
        $man2->DispLabelColour="White";
        $man2->DispLabelFontSize="10";
        $man2->DispXpos=685;
        $man2->DispYpos=10;

        $right=$this->createPanel(["PanelID"=>3, "Name"=>"Right Jamb"]);
        $pir=$right->Image(self::PACKAGE . "images/Background-001.png");
        $pir->Width=$right->DispScreenSizeHoriz=intval(900);
        $pir->Height=$right->DispScreenSizeVert=600;
        $man3=$right->Label("MAN.3");
        $man3->Image=self::PACKAGE . "images/Label-001-36x18.png";
        $man3->FreeXPlacement=$man3->FreeYPlacement="Y";
        $man3->DispLabelColour="White";
        $man3->DispLabelFontSize="10";
        $man3->DispXpos=150;
        $man3->DispYpos=10;
        $man4=$right->Label("MAN.4");
        $man4->Image=self::PACKAGE . "images/Label-001-36x18.png";
        $man4->FreeXPlacement=$man4->FreeYPlacement="Y";
        $man4->DispLabelColour="White";
        $man4->DispLabelFontSize="10";
        $man4->DispXpos=420;
        $man4->DispYpos=10;
        $man5=$right->Label("MAN.5");
        $man5->Image=self::PACKAGE . "images/Label-001-36x18.png";
        $man5->DispLabelColour="White";
        $man5->DispLabelFontSize="10";
        $man5->FreeXPlacement=$man5->FreeYPlacement="Y";
        $man5->DispXpos=630;
        $man5->DispYpos=10;
    }

    protected function buildWindchestGroups() {
        foreach($this->divisions as $divid=>$division)
            $this->createWindchestGroup(["GroupID"=>$divid, "Name"=>$division]);
    }

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        $panel=$this->getPanel(1);
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
        foreach($this->hwdata->switchLink($switchdata["SwitchID"]) as $links) {
            foreach($links as $link) {
                $lswitch=$this->hwdata->Switch($link["SourceSwitchID"]);
                if (isset($lswitch["Disp_ImageSetInstanceID"])) {
                    $instance=$this->hwdata->imageSetInstance($lswitch["Disp_ImageSetInstanceID"]);
                    $panelid=$instance["DisplayPageID"];
                    if (in_array($panelid, [2,3])) {
                        $panel=$this->getPanel($panelid);
                        $pe=$panel->GUIElement($switch);
                        $pe->PositionX=$instance["LeftXPosPixels"];
                        $pe->PositionY=$instance["TopYPosPixels"];
                        if (isset($switchdata["Label"]))
                            $pe->DispLabelText=$switchdata["Label"];
                        else {
                            foreach($this->hwdata->textInstances() as $textInstance) {
                                if (isset($textInstance["AttachedToImageSetInstanceID"]) 
                                        && $textInstance["AttachedToImageSetInstanceID"]==$lswitch["Disp_ImageSetInstanceID"]) {
                                    $pe->DispLabelText=$textInstance["Text"];
                                    break;
                                }
                            }
                            if (!isset($pe->DispLabelText)) {
                                $names=explode(" ", $switchdata["Name"], 2);
                                if (sizeof($names)==2)
                                    $pe->DispLabelText=$names[1];
                                else
                                    $pe->DispLabelText=$names[0];
                            }
                        }
                        $pe->DispLabelColour=isset($switchdata["Colour"]) ? $switchdata["Colour"] : "Black";
                        $pe->DispLabelFontSize=9;
                        $pe->ImageOn=self::PACKAGE . "images/roundknob84on.bmp";
                        $pe->ImageOff=self::PACKAGE . "images/roundknob84off.bmp";
                        $pe->MaskOn=self::PACKAGE . "images/roundknob84mask.bmp";
                        $pe->MaskOff=self::PACKAGE . "images/roundknob84mask.bmp";
                    }
                }
            }
        }
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
            $stopdata["StopID"]=$stopid;
            $manualid=$stopdata["DivisionID"]=$stopdata["ManualID"];
            $switch=$this->createStop($stopdata);
        }
    }

    protected function buildRank(int $stopid, array $rankdata) : ?\GOClasses\Rank {
        $rankid=$rankdata["RankID"];
        $divid=$this->stops["$stopid"]["DivisionID"];
        $wcg=$this->getWindchestGroup($divid);
        $rank=$this->newRank($rankid, $rankdata["Name"]);
        $rank->WindchestGroup($wcg);
        $stop=$this->getStop($stopid);
        $stop->Rank($rank);
        return $rank;
    }
    
    protected function buildRanks() : void {
        foreach ($this->stops as $stopid=>$stopdata) {
            $rankid=$stopid % 100;
            $rank=$this->buildRank($stopid, $this->hwdata->rank($rankid));
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

    public function processSample(array $hwdata, bool $isattack) : ?\GOClasses\Pipe {
        if (isset($hwdata["LoopCrossfadeLengthInSrcSampleMs"]) 
                && $hwdata["LoopCrossfadeLengthInSrcSampleMs"]>120)
                $hwdata["LoopCrossfadeLengthInSrcSampleMs"]=120;
        unset($hwdata["Pitch_ExactSamplePitch"]);
        if (!isset($hwdata["NormalMIDINoteNumber"])) {
            $hwdata["NormalMIDINoteNumber"]=60;
        }
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the build
     */
    public static function Monaster(array $positions=[], string $target="") {
        $hwi=new Monaster(self::ROOT . self::SOURCE);
        $hwi->positions=$positions;
        $hwi->build();
        $hwi->saveODF(self::TARGET);
    }
}
Monaster::Monaster();