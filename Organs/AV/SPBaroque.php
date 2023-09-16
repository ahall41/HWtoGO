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
 * Import Sonus Paradisi Great Baroque composite set to GrandOrgue
 * 
 * @author andrew
 */
class SPBaroque extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AVO/Baroque/";
    const ODF="SP Great Baroque.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Sonus Paradisi Great Baroque composite set\n"
            . "https://hauptwerk-augustine.info/SP Great Baroque.php\n"
            . "\n";
    const TARGET=self::ROOT . "SP Great Baroque.1.0.organ";

    protected int $releaseCrossfadeLengthMs=0;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>27],
        5=>"DELETE",
        6=>"DELETE",
        7=>"DELETE",
        8=>"DELETE",
    ];
    
    protected $patchEnclosures=[
        220=>["Name"=>"II",  "GroupIDs"=>[301], "X"=>1020],
        230=>["Name"=>"III", "GroupIDs"=>[401], "X"=>1070],
        240=>["Name"=>"IV",  "GroupIDs"=>[501], "X"=>1120],
    ];
    
    protected $patchTremulants=[
        1700=>["Type"=>"Synth", "GroupIDs"=>[101]],
        1710=>["Type"=>"Synth", "GroupIDs"=>[201]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401]],
        1740=>["Type"=>"Synth", "GroupIDs"=>[501]],
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"DivisionKeyAction_04 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +5=>["Name"=>"DivisionKeyAction_05 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    // Inspection of Ranks object
    /* @todo see stoprank */
    protected $patchRanks=[
         1=>["HN"=>2], //PED- 1 Contrabass 32
         2=>["HN"=>4], //PED- 2 Praestant 16
         3=>["HN"=>4], //PED- 3 Subbass 16
         4=>["HN"=>8], //PED- 4 Octavbass 8-1
         5=>["HN"=>8], //PED- 5 Montre 8
         6=>["HN"=>8], //PED- 6 Octavbass 8-2
         7=>["HN"=>8], //PED- 7 Super-octavbass 4
         8=>["HN"=>16], //PED- 8 Prestant 4
         9=>["HN"=>32], //PED- 9 Doublette 2
        10=>["HN"=>24], //PED- 10 Quinta
        11=>["HN"=>8], //PED- 11 Trompetbass 8
        12=>["HN"=>4], //GRT- 12 Gamba 16
        13=>["HN"=>8], //GRT- 13 Montre 8
        14=>["HN"=>8], //GRT- 14 Praestant 8
        15=>["HN"=>8], //GRT- 15 Roerfluit 8
        16=>["HN"=>16], //GRT- 16 Octaav 4
        17=>["HN"=>16], //GRT- 17 Holpyp 4
        18=>["HN"=>16], //GRT- 18 Speelfluit 4
        19=>["HN"=>32], //GRT- 19 Superoctaav 2
        20=>["HN"=>32], //GRT- 20 Doublette 2
        21=>["HN"=>32], //GRT- 21 Quarte
        22=>["HN"=>48], //GRT- 22 Sifflet 1 1/2
        23=>["HN"=>[[36, 54, 48],  [55, 66, 24],  [67, 78, 16], [79, 99, 12]]], //GRT- 23 Fourniture
        24=>["HN"=>[[36, 41, 128], [42, 47, 96], [48, 53, 64], [54, 59, 48], [60, 65, 32], [66, 71, 24], [72, 99, 16]]], //GRT- 24 Scherp
        25=>["HN"=>8], //GRT- 25 Dulciaan 8
        26=>["HN"=>8], //GRT- 26 Cornet
        27=>["HN"=>[[36, 60, 16],[61, 99, 8]]], //GRT- 27 Baixons-Clarins
        28=>["HN"=>8], //SWL- 28 Principal 8
        29=>["HN"=>8], //SWL- 29 Burdon 8
        30=>["HN"=>8], //SWL- 30 Gemshorn 8
        31=>["HN"=>8], //SWL- 31 Flaut-amabile 8
        32=>["HN"=>8], //SWL- 32 Quintadena 8
        33=>["HN"=>16], //SWL- 33 Octava 4
        34=>["HN"=>16], //SWL- 34 Prestant 4
        35=>["HN"=>16], //SWL- 35 Flaut-minor 4
        36=>["HN"=>32], //SWL- 36 Superoctava 2
        37=>["HN"=>32], //SWL- 37 Woudfluit 2
        38=>["HN"=>24], //SWL- 38 Quinta 3
        39=>["HN"=>64], //SWL- 39 Sedecima 1
        40=>["HN"=>[[36, 71, 48],  [72, 76, 32],  [77, 99, 24]]], //SWL- 40 Cimbel
        41=>["HN"=>[[36, 48, 192], [49, 60, 128], [61, 72, 64], [73, 99, 32]]], //SWL- 41 Simbalet 2
        42=>["HN"=>8], //SWL- 42 Vox-Humana
        43=>["HN"=>8], //SWL- 43 Trompette 8
        44=>["HN"=>8], //POS- 44 Principal 8
        45=>["HN"=>8], //POS- 45 Salicional 8
        46=>["HN"=>8], //POS- 46 Burdon 8
        47=>["HN"=>8], //POS- 47 Copula-maior 8
        48=>["HN"=>16], //POS- 48 Octava 4
        49=>["HN"=>16], //POS- 49 Copula-minor 4
        50=>["HN"=>16], //POS- 50 Quintadena 4
        51=>["HN"=>24], //POS- 51 Quinta 2 2/3
        52=>["HN"=>24], //POS- 52 Nasard
        53=>["HN"=>32], //POS- 53 Ssuper-octava 2
        54=>["HN"=>64], //POS- 54 Larigot 1
        55=>["HN"=>[[36, 59, 64],  [60, 71, 32], [72, 99, 16]]], //POS- 55 Mixtura 3x1
        56=>["HN"=>[[36, 59, 128], [60, 71, 64], [72, 99, 32]]], //POS- 56 Cimbale 2x12
        57=>["HN"=>8], //POS- 57 Regal 8
        58=>["HN"=>8], //POS- 58 Batalla 8
        59=>["HN"=>4], //CHR- 59 Bourdon 16
        60=>["HN"=>8], //CHR- 60 Montre 8
        61=>["HN"=>8], //CHR- 61 Bourdon 8
        62=>["HN"=>8], //CHR- 62 Flautathor 8
        63=>["HN"=>16], //CHR- 63 Prestant 4
        64=>["HN"=>16], //CHR- 64 Octavadecara 4
        65=>["HN"=>16], //CHR- 65 Fluste 4
        66=>["HN"=>32], //CHR- 66 Superoctava 2
        67=>["HN"=>32], //CHR- 67 Doublette 2
        68=>["HN"=>48], //CHR- 68 Tierce
        69=>["HN"=>32], //CHR- 69 Quarte
        70=>["HN"=>32], //CHR- 70 Quinzena
        71=>["HN"=>24], //CHR- 71 Nazard
        72=>["HN"=>24], //CHR- 72 Tolosana-Cornetilla
        73=>["HN"=>8], //CHR- 73 Cromorn8 Bed        
        91=>["Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[1,2,3,4,5]],
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
                    $panelelement->DispLabelText=str_replace("\n", " ", $textInstance["Text"]);
                    $panelelement->DispLabelColour="Black";

                    switch ($switchid) {
                        case 10484:
                            $panelelement->PositionX+=20;
                            break;
                        
                        case 10582:
                        case 10585:
                        case 10588:
                        case 10591:
                        case 10594:
                            $panelelement->DispLabelColour="Dark Green";
                            break;
                            
                        case 10486:
                        case 10489:
                        case 10492:
                        case 10495:
                        case 10498:
                        case 10501:
                        case 10504:
                        case 10507:
                        case 10510:
                        case 10513:
                        case 10516:
                        case 10519:
                        case 10522:
                        case 10525:
                        case 10528:
                        case 10531:
                        case 10534:
                        case 10537:
                        case 10540:
                        case 10543:
                        case 10546:
                        case 10549:
                        case 10552:
                        case 10555:
                        case 10558:
                        case 10561:
                        case 10564:
                        case 10567:
                        case 10570:
                        case 10573:
                        case 10576:
                        case 10579:
                            $panelelement->DispLabelColour="Dark Blue";
                            break;
                        
                        case 10295:
                        case 10337:
                        case 10343:
                        case 10388:
                        case 10391:
                        case 10433:
                        case 10436:
                        case 10481:
                            $panelelement->DispLabelColour="Dark Red";
                            break;
                    }
                    //echo $switchid, "\t", $panelelement->DispLabelText, "\n";
                    if (!isset($panelelement->PositionX)) $panelelement->PositionX=0;
                    if ($switchid==10484) $panelelement->PositionX+=20; // BLWR
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
            $panelelement->DisplayKeys=54;
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
                    $keyImageset["PositionX"]=455;
                    $keyImageset["PositionY"]=400;
                    $panelelement->DisplayKeys=30;
                    break;
                
                case 2:
                    $keyImageset["PositionX"]=460;
                    $keyImageset["PositionY"]=315;
                    break;
                    
                case 3:
                    $keyImageset["PositionX"]=460;
                    $keyImageset["PositionY"]=265;
                    break;

                case 4:
                    $keyImageset["PositionX"]=460;
                    $keyImageset["PositionY"]=216;
                    break;

                case 5:
                    $keyImageset["PositionX"]=460;
                    $keyImageset["PositionY"]=165;
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
        $panelid=1;
        $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->PositionX=$data["X"];
        $panelelement->PositionY=320;
        $panelelement->DispLabelText=$data["Name"];
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
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/CustomOrgan_VisualAppearanceCode01_ListItemLight.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/CustomOrgan_VisualAppearanceCode01_ListItemDark.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button06-Wood-Large-On.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button06-Wood-Large-Off.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/ExpressionPedalLargeStage",
                 "//"],
                ["OrganInstallationPackages/001630/Images/Pedals/",
                 "OrganInstallationPackages/001630/Images/Keys/",
                 "OrganInstallationPackages/001630/Images/Button04-grey-Large-On.bmp",
                 "OrganInstallationPackages/001630/Images/Button04-grey-Large-Off.bmp",
                 "OrganInstallationPackages/001630/Images/Button04-grey-Large-On.bmp",
                 "OrganInstallationPackages/001630/Images/Button04-grey-Large-Off.bmp",
                 "OrganInstallationPackages/001630/Images/expressionPedal/expres",
                 "/"], 
                $filename);  
        if (sizeof($files)==0)
            $files=$this->treeWalk(getenv("HOME") . self::ROOT);
        
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }

    // List sample pitches (for mixtures)
    private function listSample(array $hwdata) {
        return;
        switch ($rankid=$hwdata["RankID"]) {
            case 23: // Fourniture
            case 24: // Scherp
            case 26: // Cornet
            case 27: // Baixons-Clarins
            case 40: // Cimbel
            case 41: // Simbalet 2
            case 56: // Mixtura 3x1
            case 55: // Cimbale 2x12
            case 72: // Tolosana-Cornetilla
                $ppitch=$this->readSamplePitch(self::ROOT . ($file=$this->sampleFilename($hwdata)));
                $spitch=$this->midiToHz($midi=$this->sampleMidiKey($hwdata));
                $hn=8*$ppitch/$spitch;
                printf("%0d\t%0d\t%01.1f\t%s\n", $rankid, $midi, $hn, $file);
        }
 
    }    
    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe && !empty($pitchtuning=$pipe->PitchTuning)) {
            if ($pitchtuning<-1800 || $pitchtuning>1800) $pipe->Dummy();
        }
        if ($pipe && $pipe->ReleaseCount==0) $this->listSample($hwdata);
        return $pipe;
    }
    
    /**
     * Run the import
     */
    public static function SPBaroque(array $positions=[]) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new SPBaroque(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getRanks() as $rankid=>$rank) {
                if (isset($hwi->patchRanks[$rankid]["HN"])) {
                    $hns=$hwi->patchRanks[$rankid]["HN"];
                    if (!is_array($hns)) $hns=[[0, 99, $hns]];
                    $pipes=$rank->Pipes();
                    foreach ($hns as $hn) {
                        for ($midi=$hn[0]; $midi<=$hn[1]; $midi++) {
                            if (isset($pipes[$midi])) {
                                $pipe=$pipes[$midi];
                                if (!$pipe->IsDummy()) $pipe->HarmonicNumber=$hn[2];
                            }
                        }
                    }
                }
                $hwi->getRank(93)->Gain=9;
            }
            
            $hwi->saveODF(self::TARGET, self::COMMENTS);
        }
        
        else {
            self::SPBaroque(
                    [1=>"Far"]);
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
// set_error_handler("Organs\AV\ErrorHandler");
SPBaroque::SPBaroque();