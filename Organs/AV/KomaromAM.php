<?php 

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Organs\AV;
define("ALMORSE", TRUE);
require_once(__DIR__ . "/Komarom.php");

/**
 * Import Buckow-Rieger Organ from Komárom (Al Morse patch) to GrandOrgue
 * 
 * NOTE: Requires patched General section in the XML
 * 
 * @todo: See StopRank for key/stop action sound effects?
 * 
 * @author andrew
 */
class KomaromAM extends Komarom {
    const ROOT="/GrandOrgue/Organs/AV/KomaromAM/";
    const ODF="Buckow-Rieger Komarom_v2_surround_AM.Organ_Hauptwerk_xml";
    const SOURCE=self::ROOT . "OrganDefinitions/" . self::ODF;
    const COMMENTS=
              "Buckow-Rieger Organ from Komárom (Al Morse patch)\n"
            . "https://hauptwerk-augustine.info/Buckow-Rieger_Komarom.php\n"
            . "\n";
    const TARGET=self::ROOT . "Buckow-Rieger Komarom_v2_surround_AM.1.0.organ";

    protected function correctFileName(string $filename): string {
        static $files=[];
        if (sizeof($files)==0)
            $files=$this->treeWalk(getenv("HOME") . self::ROOT);
        
        $filename=str_replace(
                ["OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet008-",
                 "OrganInstallationPackages/000001/HauptwerkStandardImages/KeyImageSet002-"],
                ["OrganInstallationPackages/001756/Images/Pedals2/",
                 "OrganInstallationPackages/001756/Images/Keys/"],   
                $filename);
        if (isset($files[strtolower($filename)]))
            return $files[strtolower($filename)];
        else
            throw new \Exception ("File $filename does not exist!");
    }
    
    /**
     * Run the import
     */
    public static function KomaromAM(array $positions=[], string $target="") {
        \GOClasses\Noise::$blankloop="BlankLoop.wav";
        \GOClasses\Manual::$keys=58;
        \GOClasses\Manual::$pedals=30;
        if (sizeof($positions)>0) {
            $hwi=new KomaromAM(self::SOURCE);
            $hwi->positions=$positions;
            $hwi->import();
            $hwi->getOrgan()->ChurchName=str_replace("demo", "(AM) demo", $hwi->getOrgan()->ChurchName);
            unset($hwi->getOrgan()->InfoFilename);
            echo $hwi->getOrgan()->ChurchName, "\n";
            foreach($hwi->getManuals() as $manual) unset($manual->DisplayKeys);
            foreach($hwi->getStops() as $id=>$stop) {
                unset($stop->Rank001PipeCount);
                unset($stop->Rank002PipeCount);
                unset($stop->Rank003PipeCount);
                unset($stop->Rank004PipeCount);
                unset($stop->Rank005PipeCount);
                unset($stop->Rank006PipeCount);
                if (in_array($id,[2129,2242])) { // Cornet 3f
                    $stop->Rank001FirstAccessibleKeyNumber=20;
                    $stop->Rank002FirstAccessibleKeyNumber=20;
                    $stop->Rank003FirstAccessibleKeyNumber=20;
                }
            }
            foreach([61,62,63,64,161,162,163,164,261,262,263,264] as $rankid) {
                $hwi->getRank($rankid)->PitchTuning=10; // V.Coeli
            }
            $hwi->saveODF(sprintf(self::TARGET, $target), self::COMMENTS);
        }
        else {
            self::KomaromAM(
                    [1=>"Near", 2=>"Far", 3=>"Rear"],
                    "surround");
        }
    }   
}
KomaromAM::KomaromAM();