<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\SP;
require_once __DIR__ . "/SPOrgan.php";

/**
 * Import Sonus Paradisi Hradec Kralove, church of the Assumption of the BVM, Streussel  Organ 1765 to GrandOrgue
 * 
 * @author andrew
 */
class HradecKralove extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/HradecKralove/";
    const SOURCE="OrganDefinitions/Hradec Kralove - Maria - Wet.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Hradec Kralove - Maria - Wet 1.0.organ";
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIFFUSE,
    ];
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
        1=>[
            0=>["SetID"=>1030]
           ],
        2=>[
            0=>["SetID"=>1035, "Instance"=>11000],
           ],
        3=>[
            0=>["Group"=>"Landscape", "SetID"=>1031, "Instance"=>12000], // Left
            1=>[],
            2=>["Group"=>"Portrait",  "SetID"=>1033, "Instance"=>12000], // Left (P)
           ],
        4=>[
            0=>["Group"=>"Landscape", "SetID"=>1032, "Instance"=>12000], // Right
            1=>[],
            2=>["Group"=>"Portrait",  "SetID"=>1034, "Instance"=>12000], // Right (P)
           ],
    ];

    protected $patchDivisions=[
            8=>["DivisionID"=>8, "Name"=>"Blower", "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    public function patchData(\HWClasses\HWData $hwd): void {
        parent::patchData($hwd);
        return;
        $instances=$this->hwdata->imageSetInstances();
        foreach ($instances as $instance) {
            //if (strpos(strtolower($instance["Name"]), "swl")!==FALSE) {
            if ($instance["DisplayPageID"]==1) {
                echo $instance["ImageSetInstanceID"], " ",
                     $instance["DisplayPageID"], " ",
                     $instance["Name"], "\n";
            }
        } 
        exit();
    }
    
    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=1810; 
        return parent::createOrgan($hwdata);
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

    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        static $layouts=[0=>"", 1=>"AlternateScreenLayout1_",
            2=>"AlternateScreenLayout2_", 3=>"AlternateScreenLayout3_"];

        if (isset($data["ShutterPositionContinuousControlID"]) 
                && !empty($data["ShutterPositionContinuousControlID"])) {
            $hwd=$this->hwdata;
            $slink=$hwd->continuousControlLink($data["ShutterPositionContinuousControlID"])["D"][0];
            $dlinks=$hwd->continuousControlLink($slink["SourceControlID"]);
            foreach($dlinks["S"] as $dlink) {
                $control=$hwd->continuousControl($dlink["DestControlID"]);
                if (isset($control["ImageSetInstanceID"])) {
                    $instance=$hwd->imageSetInstance($control["ImageSetInstanceID"]);
                    if ($instance!==NULL
                            && isset($this->patchDisplayPages[$instance["DisplayPageID"]])) {
                        foreach($layouts as $layoutid=>$layout) {
                            if (isset($instance["${layout}ImageSetID"])
                                && !empty($instance["${layout}ImageSetID"])) {
                                $panel=$this->getPanel(($instance["DisplayPageID"]*10)+$layoutid, FALSE);
                                $instanceid=$instance["ImageSetInstanceID"];
                                if ($panel!==NULL 
                                        && !($instanceid==968 && $layoutid==1)) {
                                    $pe=$panel->GUIElement($enclosure);
                                    $this->configureEnclosureImage($pe, ["InstanceID"=>$instanceid], $layoutid);
                                }
                            }
                        }
                    }
                }
            }
        }
        else
            parent::configurePanelEnclosureImages($enclosure, $data);
    }
    
    protected function samplePitchMidi(array $hwdata) : ?float {
        // The pipes start at 024C but are actually 036C !
        return parent::samplePitchMidi($hwdata)+12;
    }

    protected function configureAttack(array $hwdata, \GOClasses\Pipe $pipe) : void {
        if (strpos($hwdata["SampleFilename"], "_bis")===FALSE 
                && strpos($hwdata["SampleFilename"], "_ter")===FALSE )
            parent::configureAttack($hwdata, $pipe);
    }

    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        //unset($hwdata["ReleaseCrossfadeLengthMs"]); 
        //$hwdata["ReleaseCrossfadeLengthMs"]=30;
        if (($pipe=parent::processSample($hwdata, $isattack))) {
            unset($pipe->PitchTuning);
        }
        return $pipe;
    }

    /**
     * Run the import
     */
    public static function HradecKralove(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new HradecKralove(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace(" Demo", " ($target)", $hwi->getOrgan()->ChurchName);
            foreach(\GOClasses\Organ::getObjects("PanelElement") as $object) {
                if ($object instanceof \GOClasses\PanelElement) {
                    unset($object->MouseRectLeft);
                    unset($object->MouseRectWidth);
                    unset($object->MouseRectTop);
                    unset($object->MouseRectHeight);
                    unset($object->MouseRadius);
                }
                if ($object instanceof \GOClasses\Stop) {
                    unset($stop->Rank001PipeCount);
                }
            }
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
            self::HradecKralove(
                    [
                        self::RANKS_DIFFUSE=>"Diffuse", 
                    ],
                   "");
        }
    }
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\SP\ErrorHandler");
HradecKralove::HradecKralove();