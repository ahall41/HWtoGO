<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 *
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 *
 */

namespace Organs\Other;
require_once(__DIR__ . "/../../Import/Objects.php");


/**
 * ODF Builder for GrandOrgue based on Basilica sample set
 *
 * @author andrew
 */
class Basilica{

    const PEDALS=32; 
    const KEYS=61;
    const ROOT="/GrandOrgue/Organs/Basilica/";
    const PACKAGE="OrganInstallationPackages/001512/";
    
    public $stops=[
        ["manual"=>0, "col"=>1, "row"=>1, "dir"=>"Pedals/Basse_8"],
        ["manual"=>0, "col"=>1, "row"=>2, "dir"=>"Pedals/Bombarde_16"],
        ["manual"=>0, "col"=>1, "row"=>3, "dir"=>"Pedals/Bourdon_8"],
        ["manual"=>0, "col"=>1, "row"=>4, "dir"=>"Pedals/Clairon_4"],
        ["manual"=>0, "col"=>1, "row"=>5, "dir"=>"Pedals/Contrebasse_32"],
        ["manual"=>0, "col"=>1, "row"=>6, "dir"=>"Pedals/Fourniture_V"],
        ["manual"=>0, "col"=>2, "row"=>1, "dir"=>"Pedals/Prestant_4"],
        ["manual"=>0, "col"=>2, "row"=>2, "dir"=>"Pedals/Principal_16"],
        ["manual"=>0, "col"=>2, "row"=>3, "dir"=>"Pedals/Principal_32"],
        ["manual"=>0, "col"=>2, "row"=>4, "dir"=>"Pedals/Principal_4"],
        ["manual"=>0, "col"=>2, "row"=>5, "dir"=>"Pedals/Soubasse_16"],
        ["manual"=>0, "col"=>2, "row"=>6, "dir"=>"Pedals/Trompette_8"],
        ["manual"=>1, "col"=>3, "row"=>1, "dir"=>"Manuals/Basson_16"],
        ["manual"=>1, "col"=>3, "row"=>2, "dir"=>"Manuals/Bourdon_16"],
        ["manual"=>1, "col"=>3, "row"=>3, "dir"=>"Manuals/Bourdon_8"],
        ["manual"=>1, "col"=>3, "row"=>4, "dir"=>"Manuals/Celeste_8"],
        ["manual"=>1, "col"=>3, "row"=>5, "dir"=>"Manuals/Clairon_4"],
        ["manual"=>1, "col"=>3, "row"=>6, "dir"=>"Manuals/Clarinette_16"],
        ["manual"=>1, "col"=>3, "row"=>7, "dir"=>"Manuals/Clarinette_8"],
        ["manual"=>1, "col"=>3, "row"=>8, "dir"=>"Manuals/Cor_de_Chamois_4"],
        ["manual"=>1, "col"=>3, "row"=>9, "dir"=>"Manuals/Cor_de_Chamois_8"],
        ["manual"=>1, "col"=>4, "row"=>1, "dir"=>"Manuals/Cornet_III"],
        ["manual"=>1, "col"=>4, "row"=>2, "dir"=>"Manuals/Cromorne_8"],
        ["manual"=>1, "col"=>4, "row"=>3, "dir"=>"Manuals/Cymbale_III"],
        ["manual"=>1, "col"=>4, "row"=>4, "dir"=>"Manuals/Doublette_2"],
        ["manual"=>1, "col"=>4, "row"=>5, "dir"=>"Manuals/Dulciane_8"],
        ["manual"=>1, "col"=>4, "row"=>6, "dir"=>"Manuals/Flute_4"],
        ["manual"=>1, "col"=>4, "row"=>7, "dir"=>"Manuals/Flute_cheminee_8"],
        ["manual"=>1, "col"=>4, "row"=>8, "dir"=>"Manuals/Flute_con._2"],
        ["manual"=>1, "col"=>4, "row"=>9, "dir"=>"Manuals/Flute_douce_8"],
        ["manual"=>1, "col"=>5, "row"=>1, "dir"=>"Manuals/Flute_Fuseau_4"],
        ["manual"=>1, "col"=>5, "row"=>2, "dir"=>"Manuals/Flute_harm._4"],
        ["manual"=>1, "col"=>5, "row"=>3, "dir"=>"Manuals/Flute_trav._4"],
        ["manual"=>1, "col"=>5, "row"=>4, "dir"=>"Manuals/Fourniture_IV"],
        ["manual"=>1, "col"=>5, "row"=>5, "dir"=>"Manuals/Gambe_16"],
        ["manual"=>1, "col"=>5, "row"=>6, "dir"=>"Manuals/Gambe_8"],
        ["manual"=>1, "col"=>5, "row"=>7, "dir"=>"Manuals/Hautbois_8"],
        ["manual"=>1, "col"=>5, "row"=>8, "dir"=>"Manuals/Larigot_113"],
        ["manual"=>1, "col"=>5, "row"=>9, "dir"=>"Manuals/Mixture_IV"],
        ["manual"=>1, "col"=>6, "row"=>1, "dir"=>"Manuals/Montre_16"],
        ["manual"=>1, "col"=>6, "row"=>2, "dir"=>"Manuals/Montre_8"],
        ["manual"=>1, "col"=>6, "row"=>3, "dir"=>"Manuals/Nasard_223"],
        ["manual"=>1, "col"=>6, "row"=>4, "dir"=>"Manuals/Piccolo_1"],
        ["manual"=>1, "col"=>6, "row"=>5, "dir"=>"Manuals/Prestant_4"],
        ["manual"=>1, "col"=>6, "row"=>6, "dir"=>"Manuals/Principal_4"],
        ["manual"=>1, "col"=>6, "row"=>7, "dir"=>"Manuals/Principal_8"],
        ["manual"=>1, "col"=>6, "row"=>8, "dir"=>"Manuals/Quintaton_16"],
        ["manual"=>1, "col"=>6, "row"=>9, "dir"=>"Manuals/quinte_223"],
        ["manual"=>1, "col"=>7, "row"=>1, "dir"=>"Manuals/Regale_8"],
        ["manual"=>1, "col"=>7, "row"=>2, "dir"=>"Manuals/Salicional_8"],
        ["manual"=>1, "col"=>7, "row"=>3, "dir"=>"Manuals/Septade_117"],
        ["manual"=>1, "col"=>7, "row"=>4, "dir"=>"Manuals/Sesquialtera"],
        ["manual"=>1, "col"=>7, "row"=>5, "dir"=>"Manuals/Tierce_135"],
        ["manual"=>1, "col"=>7, "row"=>6, "dir"=>"Manuals/Trompette_16"],
        ["manual"=>1, "col"=>7, "row"=>7, "dir"=>"Manuals/trompette_8"],
        ["manual"=>1, "col"=>7, "row"=>8, "dir"=>"Manuals/Trompette_Harm._8"],
        ["manual"=>1, "col"=>7, "row"=>9, "dir"=>"Manuals/Tuba_8"],
        ["manual"=>1, "col"=>8, "row"=>1, "dir"=>"Manuals/Undamaris_8"],
        ["manual"=>1, "col"=>8, "row"=>2, "dir"=>"Manuals/Viole_de_Gambe_8"],
        ["manual"=>1, "col"=>8, "row"=>3, "dir"=>"Manuals/Voix_Humaine_8"],
        ["manual"=>1, "col"=>8, "row"=>4, "dir"=>"Manuals/Vpx-PleinJeu7x"],
    ];
    
    
    public function Build() {
        \GOClasses\Manual::$pedals=self::PEDALS;
        \GOClasses\Manual::$keys=self::KEYS;
        $organ=new \GOClasses\Organ("Basilica Stop Demo Organ");
        $panel=new \GOClasses\Panel("Console");
        $panel->DispScreenSizeHoriz=1200;
        $panel->DispScreenSizeVert=700;
        $panel->DispDrawstopCols=8;
        $panel->DispDrawstopRows=9;
        $manuals[]=new \GOClasses\Manual("Pedal", "pedal");
        $manuals[]=new \GOClasses\Manual("Great", "gt");
        $wcgs[]=new \GOClasses\WindchestGroup("Pedal");
        $wcgs[]=new \GOClasses\WindchestGroup("Great");

        foreach ($this->stops as $st) {
            $name=str_replace(["Pedals/", "Manuals/", "_"], ["", "", " "], $st["dir"]);
            $stop=new \GOClasses\Stop($name);
            $manuals[$st["manual"]]->Stop($stop);
            $stop->posRC($st["row"], $st["col"]);
            $rank=new \GOClasses\Rank($name);
            $stop->Rank($rank);
            $rank->WindchestGroup($wcgs[$st["manual"]]);
            $this->loadPipes($rank, $st["dir"], $manuals[$st["manual"]]);
        }
        \GOClasses\GOObject::save(getenv("HOME") . self::ROOT . "Basilica Stop Demo.organ");
    }
    
    private function loadPipes(\GOClasses\Rank $rank, String $dir, \GOClasses\Manual $manual) : void {
        static $mask="^[0-9]+.*\.wav";
        static $offsets=[0,1,-1,2,-2,3,-3,4,-4,5,-5,6,-6];
        $dh = opendir(getenv("HOME") . self::ROOT . self::PACKAGE . $dir);
        $files=[];
        while (($file=readdir($dh)) !== FALSE) {
            if (!preg_match("/$mask/", $file)) continue;
            $midikey=intval($file);
            $files[$midikey]=$file;
        }
        for($key=0; $key<$manual->NumberOfLogicalKeys; $key++) {
            $midi=$key+$manual->FirstAccessibleKeyMIDINoteNumber;
            $pipe=$rank->Pipe($midi, TRUE);
            foreach($offsets as $offset) {
                if (isset($files[$midi+$offset])) {
                    $pipe->Attack=self::PACKAGE . "$dir/" . $files[$midi+$offset];
                    $pipe->PitchTuning=-100*$offset;
                    break;
                }
            }
        }
    }
    
    public static function Basilica() {
        $basilica=new Basilica();
        $basilica->Build();
    }
}

Basilica::Basilica();