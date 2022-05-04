<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GOClasses;
require_once __DIR__ . "/GOBase.php";

/**
 * Read .organ or .cmb file
 *
 * @author andrew
 */
class GOLoader extends GOBase {
    
    public array $Sections=[];
    public array $Pipes=[]; // Rank:Pipe indexed by Sample
    public array $Ranks=[];
    public array $Stops=[];
    public array $StopRanks=[];
    
    public function __construct(?string $file=NULL, string $type="plain") {
        if ($file) {
            if ($this->load($file, $type));
                $this->index();
        }
    }

    private static function open(string $file, string $type) {
        switch ($type) {
            case "gz":
                return gzopen($file, "r");

            default:
                return fopen($file, "r");
        }
        
    }
    
    private static function gets($handle, string $type) {
        switch ($type) {
            case "gz":
                if (gzeof($handle))
                    return FALSE;
                else
                    return gzgets($handle);

            default:
                return fgets($handle);
        }
    }

    public function load(string $file, string $type="plain") {
        $this->Sections=[];
        if (($handle=self::open($file, $type, "r"))) {
            $section="Unknown";
            $this->Sections=[];
            $object=NULL;
            while (($line=self::gets($handle, $type)) !== FALSE) {
                $line=preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $line);
                if (!empty($line) && substr($line, 0, 1)!==";") {
                    if (preg_match("/^\[.*\]/", $line)) {
                        $section=trim($line, "[]");
                        $object=NULL;
                    } 
                    else {
                        if (empty($object)) {
                            $object=new GOLoader();
                            $this->Sections[$section][]=$object;
                        }
                        $split=explode("=", $line, 2);
                        $name=$split[0];
                        $object->$name=$split[1];
                    }
                }
            }
            return $this->Sections;
        }
        else
            return FALSE;
    }
    
    public function index() {
        foreach ($this->Sections as $name=>$section) {
            if (substr_compare($name, "Rank", 1, 4))
                $this->indexRank($section, $name);
            elseif (substr_compare($name, "Stop", 1, 4))
                $this->indexStop($section, $name);
        }
    }
    
    public function indexRank(array $rank, string $name) {
        $this->Ranks[$name]=$rank;
        for ($pipe=1; $pipe<=$rank["NumberOfLogicalPipes"]; $pipe++) {
            $sample=$rank[sprintf("Pipe%3d", $pipe)];
            $this->Pipes[$sample]="$name:$pipe";
        }
    }
    
    public function indexStop(array $stop, string $name) {
        $this->Stops[$name]=$stop;
        for($rank=1; $rank<$stop["NumberOfRanks"]; $stop++) {
            $rankno=$stop[sprintf("Pipe%3d", $rank)];
            $this->StopRanks[$rankno][]=$name;
        }
    }
}