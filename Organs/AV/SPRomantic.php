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
 * Import Sonus Paradisi Great Romantic composite set to GrandOrgue
 * 
 * @author andrew
 */
class SPRomantic extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV//Romantic/";
    const ODF="SP Great Romantic v.2.0.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Sonus Paradisi Great Romantic composite set\n"
            . "https://hauptwerk-augustine.info/SP_Romantic.php\n"
            . "2.0.1 Corrected harmonic number on mixtures"
            . "\n";
    const TARGET=self::ROOT . "SP Great Romantic.2.0.1.organ";

    protected int $releaseCrossfadeLengthMs=0;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>1],
    ];
    
    protected $patchEnclosures=[
        220=>["Name"=>"II",  "GroupIDs"=>[301], "X"=>690],
        230=>["Name"=>"III", "GroupIDs"=>[401], "X"=>740],
    ];
    
    protected $patchTremulants=[
        1700=>["Type"=>"Synth", "GroupIDs"=>[101]],
        1710=>["Type"=>"Synth", "GroupIDs"=>[201]],
        1720=>["Type"=>"Synth", "GroupIDs"=>[301]],
        1730=>["Type"=>"Synth", "GroupIDs"=>[401]],
    ];
    
    protected $patchStops=[
        +1=>["Name"=>"DivisionKeyAction_01 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +2=>["Name"=>"DivisionKeyAction_02 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +3=>["Name"=>"DivisionKeyAction_03 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        +4=>["Name"=>"DivisionKeyAction_04 On", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
      2010=>["DELETE"], // Basson Hautbois 81
      2130=>["DELETE"], // Basson Hautbois 41
      2132=>["DELETE"], // Voix Humana1
      2235=>["DELETE"], // Diapason81
      2242=>["DELETE"], // Floete octaviante 41
      2248=>["DELETE"], // Octavin 21
      2255=>["DELETE"], // Voix aeolin1
      2257=>["DELETE"], // Voix celeste1
      2374=>["DELETE"], // Basson Hautbois 81
      2377=>["DELETE"], // DoesVoix Celeste1
      2379=>["DELETE"], // DoesViolin Celeste1
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    // Inspection of Ranks object
    /* @todo see stoprank */
    protected $patchRanks=[
         1=>["HN"=>4], // "PED- 1 Contrebasse 32' * = First midi->24!
         2=>["HN"=>4], // "PED- 2 Subbas forte 16'
         3=>["HN"=>8], // "PED- 3 Principal Bass 16' *
         4=>["HN"=>8], // "PED- 4 Sub Bass 16' *
         5=>["HN"=>8], // "PED- 5 Praestant 8'
         6=>["HN"=>8], // "PED- 6 Grosse Flute 8'
         7=>["HN"=>8], // "PED- 7 Holpijp 8'
         8=>["HN"=>32], // "PED- 8 Gross Princ 4' *
         9=>["HN"=>[[24,35,64],[36,47,48], [48,55,32]]], // "PED- 9 Mixtur V *
        10=>["HN"=>8, "StopIDs"=>[2011], "GroupID"=>101], // "PED- 10 Basson Hautbois 81
        11=>["HN"=>8], // "PED- 11 Basson Hautbois 8'
        12=>["HN"=>32], // "PED- 12 Orchhestr. Oboe 4' *
        13=>["HN"=>8], // "PED- 13 Posaune 16' *
        14=>["HN"=>4], // "PED- 14 Basuin16'
        15=>["HN"=>8], // "GRT- 15 Oktava 16' *
        16=>["HN"=>8], // "GRT- 16 Montre 8'
        17=>["HN"=>16], // "GRT- 17 Gross principal 8' *
        18=>["HN"=>8], // "GRT- 18 Gambe 8'
        19=>["HN"=>8], // "GRT- 19 Bourdon 8'
        20=>["HN"=>16], // "GRT- 20 Jubal floete 8' *
        21=>["HN"=>8], // "GRT- 21 Flute Harmonique 8'
        22=>["HN"=>32], // "GRT- 22 Praestant 4' *
        23=>["HN"=>16], // "GRT- 23 Holpijp 4'
        24=>["HN"=>32], // "GRT- 24 Oktava 2'
        25=>["HN"=>32], // "GRT- 25 Roerfluit 2'
        26=>["HN"=>64], // "GRT- 26 Oktava 1'
        27=>["HN"=>[[24,35,64], [36,47,48], [48,59,32], [60,99,16]]], // "GRT- 27 Mixtur V *
        28=>["HN"=>4], // "GRT- 28 Bombarde 16
        29=>["HN"=>8], // "GRT- 29 Trompette 8'
        30=>["HN"=>16, "StopIDs"=>[2131], "GroupID"=>201], // "GRT- 30 Basson Hautbois 41
        31=>["HN"=>16], // "GRT- 31 Basson Hautbois 4'
        32=>["HN"=>16, "StopIDs"=>[2133], "GroupID"=>201], // "GRT- 32 Voix Humana1 *
        33=>["HN"=>16], // "GRT- 33 Voix Humana *
        34=>["HN"=>8], // "SWL- 34 Praestant 8'
        35=>["HN"=>8, "StopIDs"=>[2236], "GroupID"=>301], // "SWL- 35 Diapason81
        36=>["HN"=>8], // "SWL- 36 Diapason 8'
        37=>["HN"=>16], // "SWL- 37 Floeten Principal 8' *
        38=>["HN"=>16], // "SWL- 38 Viola di Gamba 8' *
        39=>["HN"=>16], // "SWL- 39 Cello 8' *
        40=>["HN"=>16], // "SWL- 40 Doppel Gedeckt 8' *
        41=>["HN"=>16], // "SWL- 41 Harmonique 8' *
        42=>["HN"=>16, "StopIDs"=>[2243], "GroupID"=>301], // "SWL- 42 Floete octaviante 41
        43=>["HN"=>16], // "SWL- 43 Flute octaviante 4'
        44=>["HN"=>16], // "SWL- 44 Flute Harm. 4'
        45=>["HN"=>32], // "SWL- 45 Orchest. Floete 4' *
        46=>["HN"=>24], // "SWL- 46 Nasard1
        47=>["HN"=>24], // "SWL- 47 Nasard
        48=>["HN"=>32, "StopIDs"=>[2249], "GroupID"=>301], // "SWL- 48 Octavin 21
        49=>["HN"=>32], // "SWL- 49 Octavin 2'
        50=>["HN"=>64], // "SWL- 50 Piccolo [2'] *
        51=>["HN"=>16], // "SWL- 51  Orchestr. Oboe *
        52=>["HN"=>8], // "SWL- 52 Vox Humana 8'
        53=>["HN"=>16], // "SWL- 53 Carillon
        54=>["HN"=>8], // "SWL- 54 Trompette 8'
        55=>["HN"=>8, "StopIDs"=>[2256], "GroupID"=>301], // "SWL- 55 Voix aeolin1
        56=>["HN"=>8], // "SWL- 56 Voix eoline
        57=>["HN"=>8, "StopIDs"=>[2258], "GroupID"=>301], // "SWL- 57 Voix celeste1
        58=>["HN"=>8], // "SWL- 58 Voix celeste
        59=>["HN"=>4], // "POS- 59 Bourdon 16'
        60=>["HN"=>8], // "POS- 60 Oktava 8'
        61=>["HN"=>16], // "POS- 61 Gemshorn 8 *
        62=>["HN"=>8], // "POS- 62 Cor de Nuit 8'
        63=>["HN"=>8], // "POS- 63 Salicional 8'
        64=>["HN"=>16], // "POS- 64 Does Viola di Gamba 8' *
        65=>["HN"=>8], // "POS- 65 Holpijp 8'
        66=>["HN"=>16], // "POS- 66 Oktava 4'
        67=>["HN"=>16], // "POS- 67 Does Jubal floete 4'
        68=>["HN"=>16], // "POS- 68 Roerfluit 4'
        69=>["HN"=>24], // "POS- 69 Quinta 2 2/3'
        70=>["HN"=>32], // "POS- 70 Oktava 2'
        71=>["HN"=>32], // "POS- 71 Flute 2'
        72=>["HN"=>64], // "POS- 72  Piccolo 1'
        73=>["HN"=>[[24,35,64], [36,47,48], [48,59,32], [60,99,16]]], // "POS- 73  Mixtur Echo *
        74=>["HN"=>8, "StopIDs"=>[2375], "GroupID"=>401], // "POS- 74 Basson Hautbois 81
        75=>["HN"=>8], // "POS- 75 Basson Hautbois 8'
        76=>["HN"=>16], // "POS- 76 Trompette 4'
        77=>["HN"=>16, "StopIDs"=>[2378], "GroupID"=>401], // "POS- 77 DoesVoix Celeste1 *
        78=>["HN"=>16], // "POS- 78 Voix Celeste *
        79=>["HN"=>8, "StopIDs"=>[2380], "GroupID"=>401], // "POS- 79 DoesViolin Celeste1
        80=>["HN"=>8], // "POS- 80 Violin Celeste
        91=>["Noise"=>"Ambient", "GroupID"=>700, "StopIDs"=>[2690]],
        92=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
        93=>["Noise"=>"KeyOn",   "GroupID"=>700, "StopIDs"=>[1,2,3,4]],
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
                //error_log(print_r($instancedata, 1));
                $panel=$this->getPanel($instancedata["DisplayPageID"]);
                foreach($this->hwdata->textInstance($instanceid) as $textInstance) {
                    $panelelement=$panel->GUIElement($switch);
                    $this->configureImage($panelelement, ["SwitchID"=>$destid]);
                    $panelelement->DispLabelText=str_replace("\n", " ", $textInstance["Text"]);
                    switch ($textInstance["TextStyleID"]) {
                        
                        case 23:
                            $panelelement->DispLabelColour="Dark Blue";
                            break;
                        
                        default:
                            // echo $switchid, "\t", $textInstance["TextStyleID"], "\t", $panelelement->DispLabelText, "\n";
                            $panelelement->DispLabelColour="Black";
                            
                    }

                    switch ($switchid) {
                        case 10141:
                        case 10143:
                        case 10145:
                        case 10147:
                        case 10175:
                        case 10177:
                        case 10181:
                        case 10221:
                        case 10227:
                        case 10269:
                        case 10271:
                            $panelelement->DispLabelColour="Dark Red";
                    }

                    if (!isset($panelelement->PositionX)) $panelelement->PositionX=0;
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
            $keyImageset=$keyImageSets[$keyboardid==1 ? 2 : 1];
            $keyImageset["ManualID"]=$keyboardid;
            switch ($keyboardid) {
                case 1:
                    $keyImageset["PositionX"]=455;
                    $keyImageset["PositionY"]=400;
                    break;
                
                case 2:
                    $keyImageset["PositionX"]=460;
                    $keyImageset["PositionY"]=300;
                    break;
                    
                case 3:
                    $keyImageset["PositionX"]=460;
                    $keyImageset["PositionY"]=250;
                    break;

                case 4:
                    $keyImageset["PositionX"]=460;
                    $keyImageset["PositionY"]=200;
                    break;
            }
            //$keyImageset["PositionX"]=$keyboard["KeyGen_DispKeyboardLeftXPos"];
            //$keyImageset["PositionY"]=$keyboard["KeyGen_DispKeyboardTopYPos"];
            $this->configureKeyImage($panelelement, $keyImageset);
            $manual->Displayed="N";
            unset($manual->DisplayKeys);
        }
    }

    public function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        $panelid=1;
        $panelelement=$this->getPanel($panelid)->GUIElement($enclosure);
        $this->configureEnclosureImage($panelelement, $data);
        $panelelement->PositionX=$data["X"];
        $panelelement->PositionY=595;
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
                ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/ExpressionPedalLargeStage",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/CustomOrgan_VisualAppearanceCode01_ListItemLight.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/CustomOrgan_VisualAppearanceCode01_ListItemDark.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button06-Wood-Large-On.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button06-Wood-Large-Off.bmp",
                 //"OrganInstallationPackages/000001/HauptwerkStandardImages/ExpressionPedalLargeStage",
                 //"001704/pipe/ped/",
                 //"001307/pipe/ped/",
                 "//"],
                ["OrganInstallationPackages/001631/Images/Pedals/",
                 "OrganInstallationPackages/001631/Images/Keys/",
                 "OrganInstallationPackages/001631/Images/expressionPedal/expres",
                 "OrganInstallationPackages/001631/Images/Button04-grey-Large-On.bmp",
                 "OrganInstallationPackages/001631/Images/Button04-grey-Large-Off.bmp",
                 "OrganInstallationPackages/001631/Images/Button04-grey-Large-On.bmp",
                 "OrganInstallationPackages/001631/Images/Button04-grey-Large-Off.bmp",
                 //"OrganInstallationPackages/001631/Images/expressionPedal/expres",
                 //"001704/pipe/pedDiff/",
                 //"001307/pipe/ped/",
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
            //case 9:  // Mixtur V
            case 27: // Mixture V
            //case 53: // Carillon
            //case 73: // Mixtur Echo
                $ppitch=$this->readSamplePitch(self::ROOT . ($file=$this->sampleFilename($hwdata)));
                $spitch=$this->midiToHz($midi=$this->sampleMidiKey($hwdata));
                $hn=8*$ppitch/$spitch;
                printf("%0d\t%0d\t%01.1f\t%s\n", $rankid, $midi, $hn, $file);
        }
 
    }
 
    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $midi=isset($hwdata["NormalMIDINoteNumber"]) ? $hwdata["NormalMIDINoteNumber"] : 60;
        $rankid=$hwdata["RankID"];
        switch ($rankid) {
            case 1:
            case 3:
            case 4:
            case 8:
            case 9:
            case 13:
                if ($midi>55) return NULL;
                break;
                
            default:
                if ($rankid<15 && $midi>67) return NULL;
                break;
        }
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe && $pipe->ReleaseCount==0) $this->listSample($hwdata);
        if ($pipe && !empty($pitchtuning=$pipe->PitchTuning) && $pitchtuning<-1800) $pipe->Dummy();
        
        return $pipe;
    }

    /**
     * Run the import
     */
    public static function SPRomantic(array $positions=[]) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new SPRomantic(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getStops() as $stop) {
                for($n=1; $n<=$stop->NumberOfRanks; $n++) {
                    $s=$stop->int2str($n);
                    $stop->unset("Rank{$s}PipeCount");
                    $stop->unset("Rank{$s}FirstAccessibleKeyNumber");
                }
            }
            
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
                                // echo $pipe, "\n\n";
                            }
                        }
                    }
                }
                $hwi->getRank(93)->Gain=9;
            }
            $hwi->saveODF(self::TARGET, self::COMMENTS);
        }
        
        else {
            self::SPRomantic([1=>""]);
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
// set_error_handler("Organs\AV\ErrorHandler");
SPRomantic::SPRomantic();