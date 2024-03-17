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
 * Import Burea composite set to GrandOrgue
 * 
 * @author andrew
 */
class Burea extends AVOrgan {
    const ROOT="/GrandOrgue/Organs/AV//Swedish/";
    const ODF="Big_Burea.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Burea composite set\n"
            . "https://hauptwerk-augustine.info/Large_Dutch.php\n"
            . "\n"
            . "1.1 Added Virtual Keyboards\n"
            . "\n";
    const TARGET=self::ROOT . "Burea.1.1.organ";

    protected int $releaseCrossfadeLengthMs=0;
    
    protected $patchDisplayPages=[
        1=>["SetID"=>27],
        5=>"DELETE",
        7=>"DELETE",
    ];
    
    protected $patchDivisions=[
        7=>"DELETE"
    ];
    
    protected $patchEnclosures=[
        220=>["Name"=>"Sw.",  "GroupIDs"=>[301], "X"=>700],
        230=>["Name"=>"Pos.", "GroupIDs"=>[401], "X"=>750],
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
                           ["Blockfloj",
                            "Koppelfloj",
                            "Rausch",
                            "Quintadena",
                            "Sesquialter",
                            "rummhorn",
                           ], 
                           ["Block- floj",
                            "Koppel- floj",
                            "Rausch- ",
                            "Quint- adena",
                            "Sesqui- alter",
                            "rumm- horn",
                           ],
                            $textInstance["Text"]);

                    $panelelement->DispLabelColour="Black";

                   switch ($switchid) {
                        case 10516: // Trem Ped
                        case 10519: // Trem Gr
                        case 10522: // Trem Sw
                        case 10525: // Trem pos($switchid)                            
                            $panelelement->DispLabelColour="Dark Green";
                            break;

                        case 10438: // Ped. 16
                        case 10441: // Ped. 4
                        case 10444: // Grt. 16
                        case 10447: // Grt. 4
                        case 10450: // Grt. Ped.16
                        case 10453: // Grt. Ped.8
                        case 10456: // Grt. Ped.4
                        case 10459: // Sw. 16
                        case 10462: // Sw. 4
                        case 10465: // Sw. Ped.16
                        case 10468: // Sw. Ped.8
                        case 10471: // Sw. Ped.4
                        case 10474: // Sw. Grt.16
                        case 10477: // Sw. Grt.8
                        case 10480: // Sw. Grt.4
                        case 10483: // Pos. Grt.16
                        case 10486: // Pos. Grt.8
                        case 10489: // Pos. Grt.4
                        case 10492: // Pos. Ped.8
                        case 10495: // Pos. Swell
                        case 10498: // Pos. Pos.4
                        case 10501: // Sw. Pos.16
                        case 10504: // Swell Pos
                        case 10507: // Sw. Pos.4
                        case 10510: // Ped. Bass
                        case 10513: // Grt. Sw
                            $panelelement->DispLabelColour="Dark Blue";
                            break;
                        
                        case 10262: // Trumpet 4
                        case 10265: // Trumpet 8
                        case 10274: // Basun 16
                        case 10316: // Trumpet 8
                        case 10319: // Geigen Regal  4
                        case 10367: // Geigen Regal 8
                        case 10424: // Trumpet 8 echo
                        case 10430: // Krummhorn 8
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
                    $keyImageset["PositionX"]=452;
                    $keyImageset["PositionY"]=597;
                    $panelelement->DisplayKeys=32;
                    break;
                
                case 2:
                    $keyImageset["PositionX"]=450;
                    $keyImageset["PositionY"]=430;
                    break;
                    
                case 3:
                    $keyImageset["PositionX"]=450;
                    $keyImageset["PositionY"]=370;
                    break;

                case 4:
                    $keyImageset["PositionX"]=450;
                    $keyImageset["PositionY"]=310;
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
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button04-White-Large-On.bmp",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/Button04-White-Large-Off.bmp",
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
    public static function Burea(array $positions=[]) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        if (sizeof($positions)>0) {
            $hwi=new Burea(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            
            unset($hwi->getOrgan()->InfoFilename);
            echo ($hwi->getOrgan()->ChurchName="Burea Composite"), "\n";
            
            foreach($hwi->getStops() as $stopid=>$stop) {
                unset($stop->Rank001FirstAccessibleKeyNumber);
                unset($stop->Rank001FirstPipeNumber);
            }
            $hwi->getStop(2120)->Rank001FirstAccessibleKeyNumber=20; // Cornet

            foreach($hwi->getRanks() as $rankid=>$rank) {
                switch ($rankid) {
                    case 1:  // Subbas 32
                    case 3:  // Halflojt 16
                    case 11: // Trumpet 8
                    case 15: // Principal 16
                    case 16: // Gedackt 16
                    case 30: // Halflojt 16
                    case 49: // Rorflojt 16
                        $rank->PitchTuning=-1200;
                        break;
                    
                    case 5:  // Bas 8
                    case 29: // Geigen Regal 4
                    case 67: // Viol Celeste 4                        
                        $rank->PitchTuning=+1200;
                        break;
                }
            }
            

            $hwi->label( 75,  30, "GREAT");
            $hwi->label(860,  30, "POSITIVE");
            $hwi->label( 75, 290, "PEDAL");
            $hwi->label(860, 290, "SWELL");
            
            $hwi->addVirtualKeyboards(3,[1,2,3],[1,2,3]);
            
            $hwi->saveODF(self::TARGET, self::COMMENTS);
        }
        
        else {
            self::Burea([1=>""]);
        }
    }   
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
// set_error_handler("Organs\AV\ErrorHandler");
Burea::Burea();