<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\PG;
require_once __DIR__ . "/Ermelo.php";

/**
 * Import Gogh Demo
 */

class ErmeloFull extends Ermelo {

    const ROOT="/GrandOrgue/Organs/PG/ErmeloFull/";
    const ODF="Ermelo.Organ_Hauptwerk_xml";
    const COMMENTS=
              "Immanuelkerk in Ermelo, Netherlands (" . self::ODF . ")\n"
            . "https://piotrgrabowski.pl/ermelo/\n"
            . "\n";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;    
    const TARGET=self::ROOT . "Ermelo (%s) 1.0.organ";
    
    // Create dummy sample file for testing ...
    public function createSample($hwdata) {
        $file=getenv("HOME") . self::ROOT . $this->sampleFilename($hwdata);
        if (!file_exists($file)) {
            $dir=dirname($file);
            if (!is_dir($dir)) mkdir($dir, 0777, TRUE);
            $blank=getenv("HOME") . self::ROOT . \GOClasses\Ambience::$blankloop;
            symlink($blank, $file);
        }
    }

    public function processNoise(array $hwdata, $isattack): ?\GOClasses\Noise {
        $noise=parent::processNoise($hwdata, $isattack);
        if ($noise) $this->createSample($hwdata);
        return $noise;
    }

    public function processSample(array $hwdata, $isattack): ?\GOClasses\Pipe {
        if (isset($hwdata["LoopCrossfadeLengthInSrcSampleMs"]) 
                && $hwdata["LoopCrossfadeLengthInSrcSampleMs"]>120) $hwdata["LoopCrossfadeLengthInSrcSampleMs"]=120;
        $pipe=parent::processSample($hwdata, $isattack);
        if ($pipe) $this->createSample($hwdata);
        return $pipe;
    }
   
    public static function ErmeloFull(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop=
                \GOClasses\Ambience::$blankloop=
                "OrganInstallationPackages/002521/Noises/BlankLoop.wav";
        
        if (sizeof($positions)>0) {
            $hwi=new ErmeloFull(self::SOURCE);
            self::Ermelo($hwi, $positions, $target);
            $hwi->saveODF(sprintf(self::TARGET, $target));
            echo $hwi->getOrgan()->ChurchName, "\n";
        }
        else {
            self::ErmeloFull(
                    [1=>"(close)"],
                    "close");
            self::ErmeloFull( 
                    [2=>"(front)"],
                    "front");
            self::ErmeloFull(
                    [3=>"(rear)"],
                    "rear");
            self::ErmeloFull( 
                    [1=>"(close)", 2=>"(front)", 3=>"(rear)"],
                    "surround");
        }
    }   
    
}
function ErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new \Exception("Error $errstr");
    die();
}
//set_error_handler("Organs\PG\ErrorHandler");
ErmeloFull::ErmeloFull();