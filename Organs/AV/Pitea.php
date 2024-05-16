<?php 

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\AV;
require_once(__DIR__ . "/AVOrgan.php");

/**
 * Import Pitea composite set to GrandOrgue
 * 
 * @author andrew
 */
class Pitea extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV/Swedish/";
    const ODF="Pitea.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Pitea composite set\n"
            . "https://hauptwerk-augustine.info/Large_Dutch.php\n"
            . "\n"
            . "1.1 Added virtual keyboards\n"
            . "\n";
    const TARGET=self::ROOT . "Pitea.1.1.organ";

    // protected int $releaseCrossfadeLengthMs=0;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>27],
        5=>"DELETE",
        7=>"DELETE",
    ];
    
    protected $patchDivisions=[
        7=>"DELETE"
    ];
    
    protected $patchEnclosures=[
        220=>["Name"=>"Sw.",  "GroupIDs"=>[301], "X"=>600],
        230=>["Name"=>"Pos.", "GroupIDs"=>[401], "X"=>650],
    ];
    
    protected $patchTremulants=[
        1700=>["Type"=>"Synth", "GroupIDs"=>[101]],
        1710=>["Type"=>"Synth", "GroupIDs"=>[201]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401]],
    ];
    
    protected $patchStops=[
         1=>"DELETE",
         2=>"DELETE",
         3=>"DELETE",
         4=>"DELETE",
      2690=>"DELETE"
    ];

    // Inspection of Ranks object
    /* @todo see stoprank */
    protected $patchRanks=[
         1=>["HN"=> 2, "PitchTuning"=>-1200], // PED- 1 Borduna 32
         2=>["HN"=> 4, "PitchTuning"=>-1200], // PED- 2 Principal 16
         3=>["HN"=> 4], // PED- 3 Subbas 16
         4=>["HN"=> 8], // PED- 4 Principal 8
         5=>["HN"=> 8], // PED- 5 Oktava 8
         6=>["HN"=> 8], // PED- 6 Gedakt 8
         7=>["HN"=>16], // PED- 7 Oktava 4
         8=>["HN"=>16], // PED- 8 Koppelflojt 4
         9=>["HN"=>[[36,47,24],[48,59,16],[60,99,12]], "PitchTuning"=>-1200], // PED- 9 Mixturbass
        10=>["HN"=>32], // PED- 10 Piccolo 2
        11=>["HN"=>16], // PED- 11 Clarion 4
        12=>["HN"=> 8], // PED- 12 Tr Harm 8
        13=>["HN"=> 4], // PED- 13 Basun 16
        14=>["HN"=> 4], // GRT- 14 Ged Pommer 16
        15=>["HN"=> 8], // GRT- 15 Principal 8
        16=>["HN"=> 8], // GRT- 16 Dubbelflojt 8
        17=>["HN"=>16], // GRT- 17 Oktava 4
        18=>["HN"=>32], // GRT- 18 Oktava 2
        19=>["HN"=>[[36,47,48],[48,59,32],[60,71,24],[72,83,16],[84,99,12]]], // GRT- 19 Mixtur
        20=>["HN"=> 8], // GRT- 20 Cornet V
        21=>["HN"=> 8], // GRT- 21 Trumpet 8
        22=>["HN"=>16], // GRT- 22 Oboe 4
        23=>["HN"=> 4], // SWL- 23 Borduna 16
        24=>["HN"=> 8], // SWL- 24 Borduna 8
        25=>["HN"=> 8], // SWL- 25 Principal 8
        26=>["HN"=> 8], // SWL- 26 Gamba 8
        27=>["HN"=> 8], // SWL- 27 Fl Harm 8
        28=>["HN"=>16], // SWL- 28 Fl Oct 4
        29=>["HN"=>24, "PitchTuning"=>700], // SWL- 29 Nasard
        30=>["HN"=>32], // SWL- 30 Piccolo 2
        31=>["HN"=>[[36,55,24],[56,67,16],[68,79,12],[80,99,8]]], // SWL- 31 Mixtur V
        32=>["HN"=> 8], // SWL- 32 Voix Celeste 8
        33=>["HN"=> 8], // SWL- 33 Oboe 8
        34=>["HN"=> 8], // SWL- 34 Tr Harm 8
        35=>["HN"=>16], // SWL- 35 Clarion 4
        36=>["HN"=> 4, "PitchTuning"=>-1200], // POS- 36 Dubbelfloj 16
        37=>["HN"=> 8], // POS- 37 Gedakt 8
        38=>["HN"=> 8], // POS- 38 Fldamore 8
        39=>["HN"=>16], // POS- 39 Principal 4
        40=>["HN"=>16], // POS- 40 Koppelflojt 4
        41=>["HN"=>24, "PitchTuning"=>700], // POS- 41 Nasard
        42=>["HN"=>24], // POS- 42 Kvinta 2 2/3
        43=>["HN"=>32], // POS- 43 Valdflojt 2
        44=>["HN"=>40], // POS- 44 Ters 1 3/5
        45=>["HN"=> 8], // POS- 45 Cromorne 8
        46=>["HN"=> 8], // POS- 46 Trumpet 8 echo
        47=>["HN"=> 8], // POS- 47 Scharf
        91=>"DELETE",
        92=>"DELETE",
        93=>"DELETE",
    ];
    
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        if ($hwdata["KeyboardID"]<7)
            return parent::createManual($hwdata);
        else
            return NULL;
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (isset($hwdata["Noise"]) && $hwdata["Noise"]=="Ambient")
            return NULL;
        else
            return parent::createRank($hwdata, $keynoise);
    }

    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        $switchid=$data["SwitchID"];
        //error_log("switch=$switchid");
        $slinkid=$this->hwdata->switchLink($switchid)["D"][0]["SourceSwitchID"];
        foreach($this->hwdata->switchLink($slinkid)["S"] as $link) {
            $switchdata=$this->hwdata->switch($destid=$link["DestSwitchID"]);
            if (isset($switchdata["Disp_ImageSetInstanceID"])) {
                $instancedata=$this->hwdata->imageSetInstance($instanceid=$switchdata["Disp_ImageSetInstanceID"]);
                if ($instancedata["DisplayPageID"]!=1) continue;
                //error_log(print_r($instancedata, 1));
                $panel=$this->getPanel($instancedata["DisplayPageID"]);
                foreach($this->hwdata->textInstance($instanceid) as $textInstance) {
                    $panelelement=$panel->GUIElement($switch);
                    $this->configureImage($panelelement, ["SwitchID"=>$destid]);
                    $panelelement->DispLabelText=
                        str_replace(
                           ["TREM-",
                            "lfloj",
                            "",
                            "",
                            "",
                           ], 
                           ["Trem ",
                            "l- floj",
                            "",
                            "",
                            "",
                           ],
                            $textInstance["Text"]);

                    $panelelement->DispLabelColour="Black";

                    switch ($switchid) {
                        case 10447: // TREM-Ped
                        case 10450: // TREM-Gr
                        case 10453: // TREM-Sw
                        case 10456: // TREM-Pos
                            $panelelement->DispLabelColour="Dark Green";
                            break;
                        
                        case 10378: // Ped. 16
                        case 10381: // Ped. 4
                        case 10384: // Grt. 16
                        case 10387: // Grt. 4
                        case 10390: // Grt. Ped.16
                        case 10393: // Grt. Ped.8
                        case 10396: // Grt. Ped.4
                        case 10399: // Sw. 16
                        case 10402: // Sw. 4
                        case 10405: // Sw. Ped.16
                        case 10408: // Sw. Ped.8
                        case 10411: // Sw. Ped.4
                        case 10414: // Sw. Grt.16
                        case 10417: // Sw. Grt.8
                        case 10420: // Sw. Grt.4
                        case 10423: // Pos. Grt.8
                        case 10426: // Pos. Ped.8
                        case 10429: // Pos. Swell
                        case 10432: // Pos. Pos.4
                        case 10435: // Swell Pos
                        case 10438: // Sw. Pos.4
                        case 10441: // Ped. Bass
                        case 10444: // Grt. Sw
                            $panelelement->DispLabelColour="Dark Blue";
                            break;
                        
                        case 10265: // Clarion 4
                        case 10268: // Tr Harm 8
                        case 10271: // Basun 16
                        case 10295: // Trumpet 8
                        case 10298: // Oboe 4
                        case 10331: // Oboe 8
                        case 10334: // Tr Harm 8
                        case 10337: // Clarion 4
                        case 10367: // Cromorne 8
                        case 10370: // Trumpet 8 echo
                            $panelelement->DispLabelColour="Dark Red";
                            break;
                            
                        //default:
                        //   echo $switchid, "\t", $panelelement->DispLabelText, "\n";
                    }
                    
                    if (!isset($panelelement->PositionX)) {$panelelement->PositionX=0;}
                    $panelelement->DispLabelFontSize=7;
                    unset($panelelement->MouseRectHeight);
                    unset($panelelement->MouseRectWidth);
                    break; // Only the one?
                }
            }
        }
    }

    public function configureKeyImages(array $keyImageSets, array $keyboards) : void {
        foreach($keyboards as $keyboardid=>$keyboard) {
            if ($keyboardid>5) continue;
            $panel=$this->getPanel(1);
            $manual=$this->getManual($keyboardid);
            $panelelement=$panel->GUIElement($manual);
            //$panelelement->DisplayKeys=54;
            $keyImageset=$keyImageSets[$keyboardid==1 ? 2 : 1];
            $keyImageset["ManualID"]=$keyboardid;
            $adjuster=$keyboardid==1 ? 3 : 1;
            $keyImageset["HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural"]-=$adjuster;
            $keyImageset["HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF"]-=$adjuster;
            $keyImageset["HorizSpacingPixels_LeftOfDASharpFromLeftOfDA"]-=$adjuster;
            $keyImageset["HorizSpacingPixels_LeftOfGSharpFromLeftOfG"]-=$adjuster;
            $keyImageset["HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp"]-=$adjuster;
            $keyImageset["HorizSpacingPixels_LeftOfEBFromLeftOfDASharp"]-=$adjuster;
            $keyImageset["HorizSpacingPixels_LeftOfAFromLeftOfGSharp"]-=$adjuster;
            switch ($keyboardid) {
                case 1:
                    $keyImageset["PositionX"]=385;
                    $keyImageset["PositionY"]=595;
                    $panelelement->DisplayKeys=32;
                    break;
                
                case 2:
                    $keyImageset["PositionX"]=380;
                    $keyImageset["PositionY"]=420;
                    break;
                    
                case 3:
                    $keyImageset["PositionX"]=380;
                    $keyImageset["PositionY"]=360;
                    break;

                case 4:
                    $keyImageset["PositionX"]=380;
                    $keyImageset["PositionY"]=300;
                    break;
            }
            $this->configureKeyImage($panelelement, $keyImageset);
            $manual->Displayed="N";
            unset($manual->DisplayKeys);
            unset($manual->PositionX);
            unset($manual->PositionY);
        }
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        $panel=$this->getPanel(1);
        //$panel->DispScreenSizeHoriz=1214; // Fix to match image
        //$panel->DispScreenSizeVert=678;
       
        $panelelement=$panel->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->PositionX=$data["X"];
        $panelelement->PositionY=500;
        $panelelement->DispLabelText=$data["Name"];
        $panelelement->EnclosureStyle=3;
        unset($panelelement->BitmapCount);
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
        $filename=str_replace(
                ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet004-",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button06-Wood-Large-On.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button06-Wood-Large-Off.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/ExpressionPedalLargeStage",
                 "//"],
                ["OrganInstallationPackages/080090/Images/Pedals/",
                 "OrganInstallationPackages/080090/Images/Keys/",
                 "Images/On.png",
                 "Images/Off.png",
                 "OrganInstallationPackages/080090/Images/expressionPedal/expres",
                 "/"], 
                $filename);  
        if (sizeof($files)==0)
            $files=$this->treeWalk(getenv("HOME") . self::ROOT);
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        if (!isset($hwdata["NormalMIDINoteNumber"])) {$hwdata["NormalMIDINoteNumber"]=60;}
        $midi=$hwdata["NormalMIDINoteNumber"];
        $rankid=$hwdata["RankID"];
        if ($rankid==20 && $midi<55) {return NULL;} // Cornet
        if ($rankid<14 && $midi>67) {return NULL;} // Pedals
        if ($midi>96) {return NULL;}

        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe && !empty($pitchtuning=$pipe->PitchTuning)) {
            if ($pitchtuning<-1800 || $pitchtuning>1800) $pipe->Dummy();
        }
        return $pipe;
    }
    
    public function label(int $x, int $y, string $text) : void {
        $panel=$this->getPanel(1);
        $panelelement=$panel->Label($text);
        $panelelement->PositionX=$x;
        $panelelement->PositionY=$y;
    }
    
    /**
     * Run the import
     */
    public static function Pitea(array $positions=[]) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new Pitea(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            
            unset($hwi->getOrgan()->InfoFilename);
            echo ($hwi->getOrgan()->ChurchName="Pitea Composite"), "\n";
            
            foreach($hwi->getStops() as $stopid=>$stop) {
                unset($stop->Rank001FirstAccessibleKeyNumber);
                unset($stop->Rank001FirstPipeNumber);
            }
            $hwi->getStop(2120)->Rank001FirstAccessibleKeyNumber=20; // Cornet

            foreach($hwi->getRanks() as $rankid=>$rank) {
                if (isset($hwi->patchRanks[$rankid]["HN"])) {
                    $hns=$hwi->patchRanks[$rankid]["HN"];
                    if (!is_array($hns)) {$hns=[[0, 99, $hns]];}
                    $pipes=$rank->Pipes();
                    foreach ($hns as $hn) {
                        for ($midi=$hn[0]; $midi<=$hn[1]; $midi++) {
                            if (isset($pipes[$midi])) {
                                $pipe=$pipes[$midi];
                                if (!$pipe->IsDummy()) { 
                                    $pipe->HarmonicNumber=$hn[2];
                                }
                            }
                        }
                    }
                }
                if (isset($hwi->patchRanks[$rankid]["PitchTuning"])) {
                    $rank->PitchTuning=$hwi->patchRanks[$rankid]["PitchTuning"];
                }
            }
            

            $hwi->label(175,  30, "GREAT");
            $hwi->label(900,  30, "POSITIVE");
            $hwi->label(175, 290, "PEDAL");
            $hwi->label(900, 290, "SWELL");
            
            $hwi->addVirtualKeyboards(3,[1,2,3],[1,2,3]);
            
            $hwi->saveODF(self::TARGET, self::COMMENTS);
        }
        
        else {
            self::Pitea([1=>""]);
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
// set_error_handler("Organs\AV\ErrorHandler");
Pitea::Pitea();