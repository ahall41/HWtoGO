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
 * Import Sonus Paradisi Chapelet, opus 3742 (1995), Bellevue, Washington to GrandOrgue
 * The mixer panel could not be completed as the HW ODF references
 * to the corresponding images are missing
 *  
 * @author andrew
 */
class Chapelet extends SPOrgan {
    const ROOT="/GrandOrgue/Organs/SP/Chapelet/";
    const SOURCE="OrganDefinitions/Chapelet Spanish Collection DEMO.Organ_Hauptwerk_xml";
    const TARGET=self::ROOT . "Chapelet Spanish Collection (Demo - %s) 1.0.organ";
    const REVISIONS="";

    protected string $root=self::ROOT;
    protected array $rankpositions=[
        0=>self::RANKS_DIRECT,
        1=>self::RANKS_DIFFUSE];
        
    public $positions=[];

    protected $patchDisplayPages=[ // Set is for background, Switch/Layout is for controls
            1=>[
                0=>["SetID"=>1030]
               ],
            2=>[
                0=>["Group"=>"Simple", "Name"=>"Landscape", "Instance"=>13000, "SetID"=>1035],
                1=>[],
                2=>["Group"=>"Simple", "Name"=>"Portrait", "Instance"=>13000, "SetID"=>1037],
               ],
            3=>"DELETE",
            4=>[
                0=>["Group"=>"Galician", "Name"=>"Landscape", "Instance"=>12000, "SetID"=>1032],
                1=>[],
                2=>["Group"=>"Galician", "Name"=>"Portrait", "Instance"=>12000, "SetID"=>1034],
               ],
    ];

    protected $patchDivisions=[
        1=>"DELETE",
        2=>"DELETE",
        3=>"DELETE",
        5=>"DELETE",
    ];

    protected $patchTremulants=[
        9=>["Type"=>"Wave", "GroupIDs"=>[]], // TBC
    ];

    public function createSwitchNoise(string $type, array $hwdata) : void {
        return; // No Op
    }
    
    public function createOrgan(array $hwdata): \GOClasses\Organ {
        $hwdata["Identification_UniqueOrganID"]=2290;
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

    public function processSample(array $hwdata, bool $isattack): ?\GOClasses\Pipe {
        //$hwdata["ReleaseCrossfadeLengthMs"]=100;
        return parent::processSample($hwdata, $isattack);
    }

    /**
     * Run the import
     */
    public static function Chapelet(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=61;
        if (sizeof($positions)>0) {
            $hwi=new Chapelet(self::ROOT . self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName.=" ($target)";
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->getManual(4)->NumberOfLogicalKeys=73;
            $hwi->saveODF(sprintf(self::TARGET, $target), self::REVISIONS);
        }
        else {
            self::Chapelet(
                    [
                        self::RANKS_DIFFUSE=>"Diffuse", 
                        self::RANKS_DIRECT=>"Direct", 
                    ],
                   "4ch");
        }
    }
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\SP\ErrorHandler");
Chapelet::Chapelet();