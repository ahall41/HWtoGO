<?php

/*
 * Copyright (C) 2026 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons BY-NC-CA licence
 * https://creativecommons.org/licenses/by-nc-sa/4.0/
 * 
 */

namespace Organs\PG;
require_once __DIR__ . "/../../Import/Organ.php";

/**
 * Import Dusseldorf Demo - RANKS ONLY!
 */

class DusseldorfRanks extends  \Import\Organ {

    protected int $loopCrossfadeLengthInSrcSampleMs=5;
    
    public $positions=[];

    public function createManuals(array $keyboards): void {
        $this->getOrgan()->HasPedals="N";
        $this->newManual(1, "Dummy");
        $this->getOrgan()->NumberOfManuals=1;
    }
    
    public function configureKeyboardKeys(array $keyboardKeys): void {
        return;
    }
    
    public function createPanels(array $hwgeneral, array $hwpages): void {
        $this->createPanel([
            "PanelID"=>1,
            "Name"=>"Dummy!"
        ]);
    }
    
    public function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        return;
    }

    public function createWindchestGroups(array $divisions): void {
        $this->createWindchestGroup([
            "Name"=>"Main",
            "DivisionID"=>1
        ]);
    }
    
    public function createCouplers(array $keyactions): void {
        return;
    }

    public function createEnclosures(array $enclosures): void {
        return;
    }
    
    public function createTremulants(array $tremulants): void {
        return;
    }

    public function createStops(array $stopsdata): void {
        return;
    }
    
    public function createSwitchNoises(array $tremulants, array $keyactions, array $stopsdata): void {
        return;
    }
    
    public function createRank(array $hwdata, bool $keynoise = FALSE): ?\GOClasses\Rank {
        if (str_contains($hwdata["Name"], "Stop action noises")) {
           return NULL; 
        }
        $hwdata["GroupID"]=1;  
        return parent::createRank($hwdata, $keynoise);
    }
    
    protected function rankInUse(array $hwdata) : bool {
        return TRUE;
    }
    
    public function processNoise(array $hwdata, bool $isattack): ?\GOClasses\Noise {
        return NULL;
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        unset($hwdata["LoadSampleRange_EndPositionValue"]);
        $rankid=$hwdata["RankID"];
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe && $isattack) {
            $pipe->MIDIKeyOverride=$or=floor($key=$this->samplePitchMidi($hwdata));
            $pipe->MIDIPitchFraction=100*($key-$or);
        }
        return $pipe;
    }
    
    public function import(): void {
        parent::import();
        foreach ($this->getRanks() as $rank) {
            if (str_contains($rank->Name, "Key action noise")) {
                foreach ($rank->Pipes() as $pipe) {
                    unset($pipe->PitchTuning);
                }
            }
        }
    }
    
    protected static function DusseldorfRanks(DusseldorfRanks $hwi) {
        \GOClasses\Manual::$keys=61;
        \GOClasses\Manual::$pedals=32;
        $hwi->import();
    }
}

class DusseldorfDemoRanks extends DusseldorfRanks {

    const ROOT="/GrandOrgue/Organs/PG/Dusseldorf/";
    const ODF="Dusseldorf, St Lambertus (demo).Organ_Hauptwerk_xml";
    const COMMENTS=
              "Basilika St. Lambertus in Düsseldorf, Germany (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/dusseldorf-st-lambertus/ \n"
            . "\n";
    
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Dusseldorf, St Lambertus (demo) 1.0.organ";
    
    public static function DusseldorfDemoRanks() {
        \GOClasses\Noise::$blankloop=
                "OrganInstallationPackages/002526/Noises/BlankLoop.wav";
        $hwi=new DusseldorfRanks(self::SOURCE);
        self::DusseldorfRanks($hwi);
        $hwi->saveODF(self::TARGET);
        echo $hwi->getOrgan()->ChurchName, "\n";
    }   
    
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
set_error_handler("Organs\PG\ErrorHandler");
DusseldorfDemoRanks::DusseldorfDemoRanks();