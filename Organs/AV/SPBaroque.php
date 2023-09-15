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
                    if (!isset($panelelement->PositionX)) $panelelement->PositionX=0;
                    if ($switchid==10484) $panelelement->PositionX+=20; // BLWR
                    $panelelement->DispLabelFontSize=8;
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

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe) unset($pipe->PitchTuning);
        return $pipe;
    }
    
    /**
     * Run the import
     */
    public static function SPBaroque(array $positions=[]) {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=54;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new SPBaroque(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            /* foreach($hwi->getStops() as $id=>$stop) {
                for($n=1; $n<=$stop->NumberOfRanks; $n++) {
                    $s=$stop->int2str($n);
                    $stop->unset("Rank{$s}PipeCount");
                }
            } */
            
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