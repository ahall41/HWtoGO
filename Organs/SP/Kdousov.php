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
 * Import Sonus Paradisi Kdousov Wet to GrandOrgue
 *  
 * @author andrew
 */
class Kdousov extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/Kdousov/";
    const SOURCE="OrganDefinitions/Kdousov Wet.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Kdousov Wet 1.0.organ";
    const RANKS_WET=0;
    
    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_WET
    ];
    protected int    $switchwcg=700;
    
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030, "Instance"=>1300]
               ],
            2=>"DELETE", // Blower
            3=>"DELETE", // Voicing
    ];

    protected $patchDivisions=[
            7=>["DivisionID"=>7, "Name"=>"Stops", "Noise"=>TRUE],
            8=>["DivisionID"=>8, "Name"=>"Blower", "Noise"=>TRUE],
            9=>["DivisionID"=>9, "Name"=>"Tracker", "Noise"=>TRUE]
    ];

    protected $patchEnclosures=[
        907=>["EnclosureID"=>907,"Name"=>"Stops",   "GroupIDs"=>[700], "AmpMinimumLevel"=>1],
        908=>["EnclosureID"=>908,"Name"=>"Blower",  "GroupIDs"=>[800], "AmpMinimumLevel"=>1],
        909=>["EnclosureID"=>909,"Name"=>"Tracker", "GroupIDs"=>[900], "AmpMinimumLevel"=>1]
    ];

    protected $patchStops=[
        250=>["StopID"=>250, "DivisionID"=>1, "Name"=>"Blower",     "ControllingSwitchID"=>NULL, "Engaged"=>"Y", "Ambient"=>TRUE, "GroupID"=>800],
        -11=>["StopID"=>-11, "DivisionID"=>1, "Name"=>"PE Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -12=>["StopID"=>-12, "DivisionID"=>2, "Name"=>"HW Key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -13=>["StopID"=>-13, "DivisionID"=>3, "Name"=>"PO key On",  "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -21=>["StopID"=>-21, "DivisionID"=>1, "Name"=>"PE Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -22=>["StopID"=>-22, "DivisionID"=>2, "Name"=>"HW Key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
        -23=>["StopID"=>-23, "DivisionID"=>3, "Name"=>"PO key Off", "ControllingSwitchID"=>NULL, "Engaged"=>"Y"],
    ];
    
    protected $patchRanks=[
          8=>["Noise"=>"StopOn",  "GroupID"=>700, "StopIDs"=>[]],
          9=>["Noise"=>"StopOff", "GroupID"=>700, "StopIDs"=>[]],
        950=>["Noise"=>"Ambient", "GroupID"=>800, "StopIDs"=>[250]],
        981=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-11]],
        982=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-12]],
        983=>["Noise"=>"KeyOn",   "GroupID"=>900, "StopIDs"=>[-13]],
        991=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-21]],
        992=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-22]],
        993=>["Noise"=>"KeyOff",  "GroupID"=>900, "StopIDs"=>[-23]],
    ];

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=348; 
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
        $filename=str_replace("//", "/", $filename);
        if (isset($files[strtolower($filename)])) {
            $result=$files[strtolower($filename)];
            if (strpos($filename, "backgrounds"))
                $result=str_replace("OrganInstallationPackages/002302/", "", $result);
            return $result;
        }
        else
            throw new \Exception ("File $filename does not exist!");
    }
    
    /**
     * Run the import
     */
    public static function Kdousov(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=49;
        \GOClasses\Manual::$pedals=22;
        if (sizeof($positions)>0) {
            $hwi=new Kdousov(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            foreach($hwi->getStops() as $stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
            }
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(sprintf(self::TARGET, $target));
        }
        else {
             self::Kdousov(
                    [
                        self::RANKS_WET=>"Wet",
                    ],
                   "Wet");
        }
    }
}
Kdousov::Kdousov();