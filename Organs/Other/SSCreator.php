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
 * ODF Builder for GrandOrgue based on SSCreator sample set
 *
 * @author andrew
 */
class SSCreator{

    const PEDALS=32; 
    const KEYS=61;
    const ROOT="/GrandOrgue/Organs/SSCreator/";
    const PACKAGE="800500/";
    
    public $stops=[
        ["manual"=>1, "col"=>1, "row"=>1, "dir"=>"Basson 16"],
        ["manual"=>1, "col"=>1, "row"=>2, "dir"=>"Bourdon 8"],
        ["manual"=>1, "col"=>1, "row"=>3, "dir"=>"Clairon 4"],
        ["manual"=>1, "col"=>1, "row"=>4, "dir"=>"Claribel Flute 8"],
        ["manual"=>1, "col"=>1, "row"=>5, "dir"=>"Diapason 8"],
        ["manual"=>1, "col"=>1, "row"=>6, "dir"=>"Flute 8"],
        ["manual"=>1, "col"=>1, "row"=>7, "dir"=>"Harmonic Flute 8"],
        ["manual"=>1, "col"=>2, "row"=>1, "dir"=>"Hautbois 8"],
        ["manual"=>1, "col"=>2, "row"=>2, "dir"=>"Hohl Flute 8"],
        ["manual"=>1, "col"=>2, "row"=>3, "dir"=>"Keraulophone 8"],
        ["manual"=>1, "col"=>2, "row"=>4, "dir"=>"Prestant 4"],
        ["manual"=>1, "col"=>2, "row"=>5, "dir"=>"Tibia Clausa 8"],
        ["manual"=>1, "col"=>2, "row"=>6, "dir"=>"Trompette 8"],
    ];
    
    
    public function Build() {
        \GOClasses\Manual::$pedals=self::PEDALS;
        \GOClasses\Manual::$keys=self::KEYS;
        $organ=new \GOClasses\Organ("SSCreator Stop Demo Organ");
        $panel=new \GOClasses\Panel("Console");
        $panel->DispScreenSizeHoriz=800;
        $panel->DispScreenSizeVert=600;
        $panel->DispDrawstopCols=2;
        $panel->DispDrawstopRows=8;
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
        \GOClasses\GOObject::save(getenv("HOME") . self::ROOT . "SSCreator.organ");
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
                    $pipe->LoopCrossfadeLength=50;
                    break;
                }
            }
        }
    }
    
    public static function SSCreator() {
        $basilica=new SSCreator();
        $basilica->Build();
    }
}

SSCreator::SSCreator();