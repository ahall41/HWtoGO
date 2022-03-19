<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\AV;
require_once(__DIR__ . "/../Import/Organ.php");

/**
 * Template with stub methods
 * 
 * @author andrew
 */
class Template extends \Import\Organ {

    public function createOrgan(array $hwdata): \GOClasses\Organ {
        return NULL;
    }

    public function createPanel($paneldata): \GOClasses\Panel {
        return NULL;
    }
    
    public function createManual(array $hwdata) : \GOClasses\Manual {
        return NULL;
    }

    public function configureKeyImage(?\GOClasses\Manual $manual, array $keyImageset): void {
    }
    
    public function configureKeyboardKey(\GOClasses\Manual $manual, $switchid, $midikey): void {
    }
    
    public function createWindchestGroup(array $groupdata): ?\GOClasses\WindchestGroup {
        return NULL;
    }

    public function createEnclosure($enclosuredata): \GOClasses\Enclosure {
        return NULL;
    }

    public function createTremulant(array $hwdata): ?\GOClasses\Sw1tch {
        return NULL;
    }

    public function createCoupler(array $hwdata): ?\GOClasses\Sw1tch {
        return NULL;
    }

    public function createStop(array $hwdata): ?\GOClasses\Sw1tch {
        return NULL;
    }
    
    public function createRank(array $hwdata, bool $keynoise=FALSE): ?\GOClasses\Rank {
        return NULL;
    }
    
    public function createSwitchNoise(string $type, array $switchdata): void {
    }

    protected function isNoiseSample(array $hwdata): bool {
        return FALSE;
    }
    
    public function processNoise(array $hwdata, $isattack): ?\GOClasses\Noise {
        return NULL;
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Noise {
        return NULL;
    }
    
    /**
     * Run the import
     */
    public static function Template(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        if (sizeof($positions)>0) {
            $hwi=new Template(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("surround", $target, $hwi->getOrgan()->ChurchName);
            echo $hwi->getOrgan()->ChurchName, "\n";
            $hwi->saveODF(self::TARGET . $target . ").organ");
        }
        else {
            self::Template(
                    [1=>"Near"],
                    "Near");
            self::Template(
                    [2=>"Far"],
                    "Far");
            self::Template(
                    [3=>"Rear"],
                    "Rear");
            self::Template(
                    [1=>"Near", 2=>"Far", 3=>"Rear"],
                    "Surround");
        }
    }   
}
Template::Template();