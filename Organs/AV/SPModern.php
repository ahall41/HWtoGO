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
 * Import Sonus Paradisi Great Modern composite set to GrandOrgue
 * 
 * @author andrew
 */
class SPModern extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AVO/Modern/";
    const ODF="SP Modern demo composit.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Sonus Paradisi Great Modern composite set\n"
            . "https://hauptwerk-augustine.info/SP_Modern.php\n"
            . "\n";
    const TARGET=self::ROOT . "SP Modern demo composite.1.0.organ";

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
      2233=>["ControllingSwitchID"=>10191], // Gamba L/R
      2235=>["ControllingSwitchID"=>10195], // Octava L/R
      2237=>["ControllingSwitchID"=>10199], // Open-Fluit L/R
      2239=>["ControllingSwitchID"=>10203], // Roerquint L/R
      2241=>["ControllingSwitchID"=>10207], // Terz L/R
      2243=>["ControllingSwitchID"=>10211], // Octaaf L/R
      2690=>["DivisionID"=>1, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>700] // Blower
    ];

    // Inspection of Ranks object
    /* @todo see stoprank */
    protected $patchRanks=[
        1=>["HN"=>4], //PED- 1 Subbas 16f
        2=>["HN"=>4], //PED- 2 Subbas 16
        3=>["HN"=>8], //PED- 3 Octavbas 8
        4=>["HN"=>8], //PED- 4 Octava 8
        5=>["HN"=>8], //PED- 5 Gedackt 8
        6=>["HN"=>16], //PED- 6 Prestant 4
        7=>["HN"=>32], //PED- 7 Octaf 2
        8=>["HN"=>64], //PED- 8 Dwarfluit 1
        9=>["HN"=>24], //PED- 9 Quinte
        10=>["HN"=>[[36,47,64],[48,76,32],[77,83,24],[78,99,16]]], //PED- 10 Scherp
        11=>["HN"=>[[36,41,48],[42,53,32],[54,65,24],[66,77,16],[78,99,8]]], //PED- 11 Mixtur
        12=>["HN"=>4], //PED- 12 Trumpet 16
        13=>["HN"=>4], //PED- 13 Trumpet 8
        14=>["HN"=>8], //PED- 14 Krumhorn 8
        15=>["HN"=>8], //PED- 15 Krumhorn 4 
        16=>["HN"=>8], //PED- 16 Batalla 2 [starts at 60]
        17=>["HN"=>8], //GRT- 17 Principal 16
        18=>["HN"=>8], //GRT- 18 Octaf 8
        19=>["HN"=>8], //GRT- 19 Holpijp 8
        20=>["HN"=>16], //GRT- 20 Octaf 4
        21=>["HN"=>16], //GRT- 21 Block-Fluit 4
        22=>["HN"=>24], //GRT- 22 Quinte
        23=>["HN"=>32], //GRT- 23 Octaf 2
        24=>["HN"=>32], //GRT- 24 Flute 2
        25=>["HN"=>[[36,41,48],[42,53,32],[54,65,24],[66,77,16],[78,99,8]]], //GRT- 25 Mixtur
        26=>["HN"=>4], //GRT- 26 Trumpet 16
        27=>["HN"=>8], //GRT- 27 Krumhorn 8
        28=>["HN"=>16], //GRT- 28 Fuerte 4
        29=>["HN"=>8], //SWL- 29 Holpijp 16
        30=>["HN"=>8], //SWL- 30 Montre
        31=>["HN"=>8], //SWL- 31 Roerfluit 8
        32=>["HN"=>8], //SWL- 32 Gamba 8L
        33=>["HN"=>8], //SWL- 33 Gamba 8R
        34=>["HN"=>16], //SWL- 34 Octava 4L
        35=>["HN"=>16], //SWL- 35 Octava 4R
        36=>["HN"=>16], //SWL- 36 Open-Fluit 4L
        37=>["HN"=>16], //SWL- 37 Open-Fluit 4R
        38=>["HN"=>24], //SWL- 38 Roerquint 2 2/3L
        39=>["HN"=>24], //SWL- 39 Roerquint 2 2/3R
        40=>["HN"=>48], //SWL- 40 Terz 1 3/5L
        41=>["HN"=>48], //SWL- 41 Terz 1 3/5R
        42=>["HN"=>32], //SWL- 42 Octaaf 2L
        43=>["HN"=>32], //SWL- 43 Octaaf 2R
        44=>["HN"=>32], //SWL- 44 Waldflaut 2
        45=>["HN"=>[[36,47,64],[48,76,32],[77,83,24],[78,99,16]]], //SWL- 45 Scherp
        46=>["HN"=>8], //SWL- 46 Batala 8
        47=>["HN"=>8], //POS- 47 Principal 8
        48=>["HN"=>8], //POS- 48 Gedackt 8
        49=>["HN"=>8], //POS- 49 Burdon 8
        50=>["HN"=>16], //POS- 50 Octav 4
        51=>["HN"=>16], //POS- 51 Salicional 4
        52=>["HN"=>16], //POS- 52 Flute 4
        53=>["HN"=>32], //POS- 53 Octav 2
        54=>["HN"=>32], //POS- 54 Wald-Floet 2
        55=>["HN"=>48], //POS- 55 Quinta 1 1/2
        56=>["HN"=>[[36,47,48],[48,59,32],[60,71,24],[72,83,16],[84,99,8]]], //POS- 56 Mixtur 4x
        57=>["HN"=>8], //POS- 57 Vox-Humana 8
        58=>["HN"=>8], //POS- 58 Krumhorn 4
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
        static $switchpositions=[ // [row,col]
            10185=>[0,0],
            10187=>[0,1],
            10189=>[0,2],
            10191=>[0,3],
            10195=>[0,4],
            10199=>[0,5],
            10203=>[1,0],
            10207=>[1,1],
            10211=>[1,2],
            10215=>[1,3],
            10217=>[1,4],
            10219=>[1,5],
        ];
        if (isset($data["StopID"])) { 
            if (array_key_exists($data["StopID"]+1, $this->patchStops)) {return;}
        };
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
                    $panelelement->DispLabelText=
                        str_replace(
                           ["-Trem",
                            " 8L",
                            "3/5L",
                            " 4L",
                            "2/3L",
                            " 2L",
                           ], 
                           [" Trem",
                            " 8",
                            "3/5",
                            " 4",
                            "2/3",
                            " 2",
                           ],
                            $textInstance["Text"]);
                    
                    $panelelement->DispLabelColour="Black";
                    switch ($switchid) {
                        case 10297: // Pedal-Trem.
                        case 10299: // Great-Trem.
                        case 10301: // Swell-Trem.
                        case 10303: // Positiv-Trem.
                            $panelelement->DispLabelColour="Dark Green";
                            break;

                        case 10247: // Ped. 16
                        case 10249: // Ped. 4
                        case 10251: // Grt. 16
                        case 10253: // Grt. 4
                        case 10255: // Grt. Ped.16
                        case 10257: // Grt. Ped.8
                        case 10259: // Grt. Ped.4
                        case 10261: // Sw. 16
                        case 10263: // Sw. 4
                        case 10265: // Sw. Ped.16
                        case 10267: // Sw. Ped.8
                        case 10269: // Sw. Ped.4
                        case 10271: // Sw. Grt.16
                        case 10273: // Sw. Grt.8
                        case 10275: // Sw. Grt.4
                        case 10277: // Pos. Grt.16
                        case 10279: // Pos. Grt.8
                        case 10281: // Pos. Grt.4
                        case 10283: // Pos. Ped.8
                        case 10285: // Pos. Swell
                        case 10287: // Pos. Pos.4
                        case 10289: // Sw. Pos.16
                        case 10291: // Swell Pos
                        case 10293: // Sw. Pos.4
                        case 10295: // Grt. Sw 
                            $panelelement->DispLabelColour="Dark Blue";
                            break;

                        case 10151:
                        case 10153:
                        case 10155:
                        case 10157:
                        case 10159:
                        case 10179:
                        case 10181:
                        case 10183:
                        case 10219:
                        case 10241:
                        case 10243:
                            $panelelement->DispLabelColour="Dark Red";
                    }
                    
                    /* echo $switchid, "\t",
                         $panelelement->PositionX, "\t",
                         $panelelement->PositionY, "\t",
                         $panelelement->DispLabelText, "\n"; */
                    if (!isset($panelelement->PositionX)) $panelelement->PositionX=0;
                    if ($switchid==10245) { // BLWR
                        $panelelement->PositionX+=20;
                        $panelelement->PositionY+=20;
                    }
                    if (array_key_exists($switchid, $switchpositions)) {
                        $panelelement->PositionX=840+($switchpositions[$switchid][0]*50);
                        $panelelement->PositionY= 50+($switchpositions[$switchid][1]*50);
                    }
                    $panelelement->DispLabelFontSize=7;
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
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button06-Wood-Large-On.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button06-Wood-Large-Off.bmp",
                 "//"],
                ["OrganInstallationPackages/001632/Images/Pedals/",
                 "OrganInstallationPackages/001632/Images/Keys/",
                 "OrganInstallationPackages/001632/Images/expressionPedal/expres",
                 "Images/On1.bmp",
                 "Images/Off1.bmp",
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
            //case 10: // PED- 10 Scherp
            //case 11: // PED- 11 Mixtur
            //case 16: // PED- 16 Batalla 2
            //case 25: // GRT- 25 Mixtur
            //case 45: // SWL- 45 Scherp
            //case 46: // SWL- 46 Batala 8
            //case 56: // POS- 56 Mixtur 4x
            case 9999:
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
            case 13:
            case 15:
                if ($midi>79) return NULL;
                break;
                
            case 16:
                if ($midi>91) return NULL;
                break;

            case 28:
                if ($midi>73) return NULL;
                break;
            
            default:
                if ($rankid<17 && $midi>68) return NULL;
                if ($midi>97) return NULL;
                break;
        }

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
    public static function SPModern(array $positions=[]) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new SPModern(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            
            foreach($hwi->getStops() as $stopid=>$stop) {
                unset($stop->Rank001FirstAccessibleKeyNumber);
                unset($stop->Rank001FirstPipeNumber);
                switch ($stopid) {
                    case 2128:
                        $stop->Rank001FirstAccessibleKeyNumber=25;
                        break;
                    
                    case 2246:
                        $stop->Rank001FirstAccessibleKeyNumber=17;
                        break;
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
                            }
                        }
                    }
                }
            }
            
            $hwi->getRank(93)->Gain=9;
            $hwi->saveODF(self::TARGET, self::COMMENTS);
        }
        
        else {
            self::SPModern([1=>""]);
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\AV\ErrorHandler");
SPModern::SPModern();