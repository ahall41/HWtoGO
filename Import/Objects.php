<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 *
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 *
 */

namespace Import;
require_once(__DIR__ . "/../GOClasses/Organ.php");
require_once(__DIR__ . "/../GOClasses/Panel.php");
require_once(__DIR__ . "/../GOClasses/PanelElement.php");
require_once(__DIR__ . "/../GOClasses/Manual.php");
require_once(__DIR__ . "/../GOClasses/WindchestGroup.php");
require_once(__DIR__ . "/../GOClasses/Enclosure.php");
require_once(__DIR__ . "/../GOClasses/Drawstop.php");
require_once(__DIR__ . "/../GOClasses/Stop.php");
require_once(__DIR__ . "/../GOClasses/Noise.php");
require_once(__DIR__ . "/../GOClasses/AmbientNoise.php");
require_once(__DIR__ . "/../GOClasses/SwitchNoise.php");
require_once(__DIR__ . "/../GOClasses/Sw1tch.php");
require_once(__DIR__ . "/../GOClasses/Rank.php");
require_once(__DIR__ . "/../GOClasses/KeyNoise.php");
require_once(__DIR__ . "/../GOClasses/Pipe.php");
require_once(__DIR__ . "/../GOClasses/Coupler.php");
require_once(__DIR__ . "/../GOClasses/Tremulant.php");

/**
 * Base class to create/retrieve GO Objects
 *
 * @author andrew
 */
class Objects {
    protected $Model=[];
    protected string $root="";

    const Organ=1;
    const Panels=2;
    const Manuals=3;
    const WindchestGroups=4;
    const WCG=5;
    const Enclosures=6;
    const Stops=71;
    const SwitchNoises=72;
    const Ranks=8;
    const Switches=9;
    const Couplers=10;
    const Tremulants=11;
    const Pipes=12;

    protected function newOrgan(string $name) : \GOClasses\Organ {
        return $this->Model[self::Organ]=new \GOClasses\Organ($name);
    }

    public function getOrgan() : \GOClasses\Organ {
        return $this->Model[self::Organ];
    }

    protected function newPanel($id, $name) : \GOClasses\Panel {
        return $this->Model[self::Panels][$id]=new \GOClasses\Panel($name);
    }

    public function getPanel($id, bool $hardfail=TRUE) : ?\GOClasses\Panel {
        if ($hardfail || isset($this->Model[self::Panels][$id]))
            return $this->Model[self::Panels][$id];
        else
            return NULL;
    }

    public function getPanels() : array {
        if (isset($this->Model[self::Panels]))
            return $this->Model[self::Panels];
        else
            return [];
    }

    
    protected function newManual($id, $name) : \GOClasses\Manual {
        return $this->Model[self::Manuals][$id]=new \GOClasses\Manual($name);
    }

    public function getManual($id) : \GOClasses\Manual {
        return $this->Model[self::Manuals][$id];
    }

    public function getManuals() : array {
        if (isset($this->Model[self::Manuals])) 
            return($this->Model[self::Manuals]);
        else
            return[];
    }

    protected function newWindchestGroup($id, $name) : \GOClasses\WindchestGroup {
        //echo "newWindchestGroup($id, $name)\n";
        return $this->Model[self::WindchestGroups][$id]=new \GOClasses\WindchestGroup($name);
    }

    public function getWindchestGroup($id, bool $hardfail=FALSE) : ?\GOClasses\WindchestGroup {
        if ($hardfail || isset($this->Model[self::WindchestGroups][$id]))
            return $this->Model[self::WindchestGroups][$id];
        else
            return NULL;
    }

    protected function newEnclosure($id, $name) : \GOClasses\Enclosure {
        return $this->Model[self::Enclosures][$id]=new \GOClasses\Enclosure($name);
    }

    public function getEnclosure($id) : \GOClasses\Enclosure {
        return $this->Model[self::Enclosures][$id];
    }

    protected function newSwitchNoise(int $id, string $name) : \GOClasses\SwitchNoise {
        //echo "newSwitchNoise(int $id, string $name)\n";
        return $this->Model[self::SwitchNoises][$id]=new \GOClasses\SwitchNoise($name);
    }

    public function getSwitchNoise($id, bool $hardfail=FALSE) : ?\GOClasses\SwitchNoise {
        if ($hardfail || isset($this->Model[self::SwitchNoises][$id]))
            return $this->Model[self::SwitchNoises][$id];
        else
            return NULL;
    }

    public function getSwitchNoises() : array {
        if (isset($this->Model[self::SwitchNoises]))
            return $this->Model[self::SwitchNoises];
        else
            return [];
    }

    
    protected function newAmbientNoise(int $id, string $name) : \GOClasses\AmbientNoise {
        // echo "newAmbientNoise(int $id, string $name)\n";
        return $this->Model[self::Stops][$id]=new \GOClasses\AmbientNoise($name);
    }

    protected function newStop(int $id, string $name) : \GOClasses\Stop {
        // echo "newStop(int $id, string $name)\n";
        return $this->Model[self::Stops][$id]=new \GOClasses\Stop($name);
    }

    public function getStop($id, bool $hardfail=FALSE) : ?\GOClasses\Stop {
        if ($hardfail || isset($this->Model[self::Stops][$id]))
            return $this->Model[self::Stops][$id];
        else
            return NULL;
    }

    public function getStops() : array {
        if (isset($this->Model[self::Stops]))
            return $this->Model[self::Stops];
        else
            return [];
    }

    
    protected function newRank($id, $name, $iskeynoise=FALSE) : \GOClasses\Rank {
        if ($iskeynoise)
            $rank=new \GOClasses\KeyNoise($name);
        else
            $rank=new \GOClasses\Rank($name);
        return $this->Model[self::Ranks][$id]=$rank;
    }

    public function getRank($id) : ?\GOClasses\Rank {
        if (isset($this->Model[self::Ranks][$id]))
            return $this->Model[self::Ranks][$id];
        else
            return NULL;
    }
    
    public function getRanks() {
        if (isset($this->Model[self::Ranks]))
            return $this->Model[self::Ranks];
        else
            return [];
    }

    protected function newSwitch($id, $name) : \GOClasses\Sw1tch {
        //echo "newSwitch(int $id, string $name)\n";
        return $this->Model[self::Switches][$id]=new \GOClasses\Sw1tch($name);
    }

    protected function makeSwitch(int $id, string $name) : \GOClasses\Sw1tch {
//      echo "makeSwitch(int $id, $name)\n";
        if (isset($this->Model[self::Switches][$id]))
            return $this->Model[self::Switches][$id];
        else
            return $this->newSwitch($id, $name);
    }
    
    public function getSwitches() : array {
        if (isset($this->Model[self::Switches]))
            return $this->Model[self::Switches];
        else
            return [];
    }

    public function getSwitch(int $id, bool $hardfail=TRUE) : ?\GOClasses\Sw1tch {
        if ($hardfail || isset($this->Model[self::Switches][$id]))
            return $this->Model[self::Switches][$id];
        else
            return NULL;
    }

    protected function newCoupler($id, $name) : \GOClasses\Coupler {
        return $this->Model[self::Couplers][$id]=new \GOClasses\Coupler($name);
    }

    public function getCoupler($id) : \GOClasses\Coupler {
        return $this->Model[self::Couplers][$id];
    }

    public function getCouplers() {
        if (isset($this->Model[self::Couplers]))
            return $this->Model[self::Couplers];
        else
            return [];
    }

    protected function newTremulant($id, $name, $wave=FALSE) : \GOClasses\Tremulant {
        // echo "newTremulant($id, $name, $wave)\n";
        return $this->Model[self::Tremulants][$id]=new \GOClasses\Tremulant($name, $wave);
    }

    public function getTremulant($id, bool $hardfail=TRUE) : ?\GOClasses\Tremulant {
        if ($hardfail || isset($this->Model[self::Tremulants][$id])) 
            return $this->Model[self::Tremulants][$id];
        else
            return NULL;
    }

    public function newPipe(int $pipeid, \GOClasses\GOObject $object, int $midikey=0) {
        if (!isset($this->Model[self::Pipes][$pipeid])) {
            if ($object instanceof \GOClasses\Rank)
                $this->Model[self::Pipes][$pipeid]=$object->Pipe($midikey, TRUE);
            else // Should be SwitchNoise
                $this->Model[self::Pipes][$pipeid]=$object->Noise();
        }
        return $this->Model[self::Pipes][$pipeid];
    }

    public function getPipe(int $pipeid) {
        if (isset($this->Model[self::Pipes][$pipeid]))
            return $this->Model[self::Pipes][$pipeid];
        else
            return NULL;
    }
}