<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Import;
require_once(__DIR__ . "/Images.php");

/**
 * Abstract class for importing an organ into GO from HW
 *
 * @author andrew
 */
abstract class Organ extends Images {
    
    /**
     * Additional configuration data - this will be merged with
     * or replace any data from HW
     */
    protected $patchDisplayPages=[];
    protected $patchDivisions=[];
    protected $patchEnclosures=[];
    protected $patchImageSets=[];
    protected $patchKeyActions=[];
    protected $patchKeyboards=[];
    protected $patchKeyImageSets=[];
    protected $patchRanks=[];
    protected $patchStops=[];
    protected $patchStopRanks=[];
    protected $patchTremulants=[];
    protected $patchWindCompartments=[];
    
    protected $samplePitches=[]; // [Filename=>PitchHz, ...
    
    /**
     * Full import
     */
    public function import() : void {
        $hwd=$this->hwdata;
        $this->patchData($hwd);
        $this->createOrgan($hwd->general());
        $this->createPanels($hwd->general(), $hwd->displayPages());
        $this->createManuals($keyboards=$hwd->keyboards());
        $this->configureKeyImages($hwd->keyImageSets(),$keyboards);
        $this->configureKeyboardKeys($hwd->keyboardKeys());
        $this->createWindChestGroups($hwd->divisions());
        $this->createEnclosures($hwd->enclosures());
        $this->createTremulants($tremulants=$hwd->tremulants());
        $this->createCouplers($keyActions=$hwd->keyActions());
        $this->createStops($stops=$hwd->stops());
        $this->createRanks($hwd->ranks());
        $this->createSwitchNoises($tremulants, $keyActions, $stops);
        $this->processSamples($hwd->attacks(), TRUE);
        $this->processSamples($hwd->releases(), FALSE);
        $this->cloneTremmed();
    }

    /**
     * Patch the source data
     */
    protected function patchData(\HWClasses\HWData $hwd) : void {
        $hwd->patchDisplayPages($this->patchDisplayPages);
        $hwd->patchDivisions($this->patchDivisions);
        $hwd->patchEnclosures($this->patchEnclosures);
        $hwd->patchImageSets($this->patchImageSets);
        $hwd->patchKeyActions($this->patchKeyActions);
        $hwd->patchKeyboard($this->patchKeyboards);
        $hwd->patchKeyImageSets($this->patchKeyImageSets);
        $hwd->patchRanks($this->patchRanks);
        $hwd->patchStops($this->patchStops);
        $hwd->patchStopRanks($this->patchStopRanks);
        $hwd->patchTremulants($this->patchTremulants);
        $hwd->patchWindCompartments($this->patchWindCompartments);
    }
    
    /**
     * Create Couplers
     * 
     * @param array $keyactions - HW Key Actions 
     * @return void
     */
    public function createCouplers(array $keyactions) : void {
        foreach($keyactions as $keyaction) {
            if (isset($keyaction["ConditionSwitchID"]))
                $this->createCoupler($keyaction);
        }
    }

    /**
     * Create the enclosures
     * 
     * @param array $enclosures - Merged with $this->enclosures)
     * @return void
     */
    public function createEnclosures(array $enclosures) : void {
        asort($enclosures);
        foreach ($enclosures as $enclosure)
            $this->createEnclosure($enclosure);
    }

    /**
     * Create the set of keyboard key images
     * Ideally we should use keyboardKeys OR keyImages
     * Only created where KeysID is set in $this->manuals
     * @param array $keyboardKeys: HW keyboardKeys data
     * @todo Offsets for spacing keys correctly
     */
    public function configureKeyboardKeys(array $keyboardKeys) : void {
        // Reorder source data
        $index=[];
        foreach($keyboardKeys as $keyboardKey) {
            $switch=$this->hwdata->switch($keyboardKey["SwitchID"]);
            if (isset($switch["Disp_ImageSetInstanceID"])
                    && isset($keyboardKey["NormalMIDINoteNumber"]))
                $index[$keyboardKey["KeyboardID"]][$keyboardKey["NormalMIDINoteNumber"]]
                        =$keyboardKey["SwitchID"];
        }

        $manuals=$this->getManuals();
        asort($manuals);
        foreach($manuals as $manid=>$manual) {
            if (isset($index[$manid])) {
                $midikeys=$index[$manid];
                asort($midikeys);
                $manual->DisplayKeys=0;
                foreach($midikeys as $midikey=>$switchid)
                    $this->configureKeyboardKey($manual, $switchid, $midikey);
                $manual->NumberOfLogicalKeys=$manual->DisplayKeys;
                $manual->NumberOfAccessibleKeys=$manual->DisplayKeys;
            }
        }
    }

    /**
     * Create a set of KeyImages
     * Only created where KeyImageID is set in $this->manuals
     * 
     * @param array $keyImageSets - HW Data key image sets
     * @return void
     */
    public function configureKeyImages(array $keyImageSets, array $keyboards) : void {
        asort($keyImageSets);
        foreach ($keyImageSets as $keyImageSet) {
            $this->configureKeyImage(NULL, $keyImageSet);
        }
    }

    /**
     * Create the manuals
     * 
     * @param array $keyboards - HW Keyboards - merged with $this->manuals
     */
    public function createManuals(array $keyboards) : void {
        asort($keyboards);
        foreach ($keyboards as $keyboard)
            $this->createManual($keyboard);
    }

    /**
     * Create the panels
     * @param array $hwwgeneral - HW General data (Panel000)
     * @param array $hwpages - Hw DisplayPages data
     * @return void
     */
    public function createPanels(array $hwgeneral, array $hwpages) : void {
        asort($hwpages);
        foreach($hwpages as $hwpage) {
            $panel=$this->createPanel(array_merge($hwgeneral, $hwpage));
            if ($panel!==NULL)
                $this->configurePanelImage($panel, $hwpage);
        }
    }

    /**
     * Allocate a rank to its stops 
     *
     * @param array $hwdata
     * @return int Stop ID
     * @throws \Exception
     * @deprecated
     */
    protected function rankStopIDs(array $hwdata): array {
        $result=[];
        $rankid=abs($hwdata["RankID"]);
        foreach($this->hwdata->rankStop($rankid) as $rankstop)
            $result[]=$rankstop["StopID"];
        foreach($this->hwdata->altRankStop($rankid) as $rankstop)
            $result[]=$rankstop["StopID"];
        return $result;
    }
    
    /**
     * Determine if the rank is used (has samples)
     * 
     * @param array $hwdata - HW stop data
     * @return boolean
     */
    protected function rankInUse(array $hwdata) : bool {
        return $this->hwdata->sampledRank(abs($hwdata["RankID"]));
    }
        
    
    protected function rankStopData(int $rankid, int $stopid): array {
        $rankstops=array_merge(
                $this->hwdata->rankStop($rankid),
                $this->hwdata->altRankStop($rankid));
        foreach($rankstops as $rankstop) {
            if ($rankstop["StopID"]==$stopid) 
                return $rankstop;
        }
        return [];
    }

    /**
     * Create the Ranks. Requires rankStopIDs() and rankGroupID() to be implemented
     * @param array $ranksdata - HW Ranks
     * @return void
     */
    public function createRanks(array $ranksdata) : void {
        asort($ranksdata);
        foreach($ranksdata as $rankdata)
            $this->createRank($rankdata);
    }
    
    /**
     * Create the switch noise effects
     * At this stage all the (positive) switches should need an appropriate noise
     */
    public function createSwitchNoises(
            array $tremulants,
            array $keyactions,
            array $stopsdata) : void {

        asort($tremulants);
        foreach($tremulants as $tremulant)
            $this->createSwitchNoise(self::TremulantNoise, $tremulant);
        
        asort($keyactions);
        foreach($keyactions as $keyaction) {
            if (isset($keyaction["ConditionSwitchID"]))
                $this->createSwitchNoise(self::CouplerNoise, $keyaction);
        }
        
        asort($stopsdata);
        foreach($stopsdata as $stopdata)
            $this->createSwitchNoise(self::SwitchNoise, $stopdata);
    }

    /**
     * Determine if the stop is used (has samples)
     * 
     * @param array $hwdata - HW stop data
     * @return boolean
     */
    protected function stopInUse(array $hwdata) : bool {
        $ranks=$this->hwdata->stopRank(abs($hwdata["StopID"]));
        foreach($ranks as $rankid=>$rankdata) {
            if ($this->hwdata->sampledRank($rankid))
                return TRUE;
        }
        return FALSE;
    }

    /**
     * Find ranks attached to a stop @deprecated
     *
     * @param array $hwdata
     * @return array of int - list of Rank IDs
     * @deprecated
     */
    protected function stopRankIDs(array $hwdata): array {
        $result=[];
        foreach($this->hwdata->stopRank(abs($hwdata["StopID"])) as $rankstop)
            $result[]=$rankstop["RankID"];
        return $result;
    }

    /**
     * Create the Stops
     * @param array $stopsdata
     * @return void
     */
    public function createStops(array $stopsdata) : void {
        asort($stopsdata);
        foreach($stopsdata as $stopdata) {
            $stopid=$stopdata["StopID"];
            if (isset($this->stops[$stopid]))
                $stopdata=array_merge($stopdata, $this->stops[$stopid]);
            $this->createStop($stopdata);
        }
    }
    
    /**
     * Create the tremulants
     * 
     * @param array $tremulants - HW data merged with %$this->tremulants
     * @return void
     */
    public function createTremulants(array $tremulants) : void {
        asort($tremulants);
        foreach($tremulants as $tremulant)
            $this->createTremulant($tremulant);
    }
    
    /**
     * Create the Windchest Groups
     * @param array $divisions. Merged with $this->divisions
     * @return void
     */
    public function createWindchestGroups(array $divisions) : void { 
        asort($divisions);
        foreach($divisions as $division)
            $this->createWindChestGroup($division);
    }
    
    /**
     * Read sample pitches generated by LoopAuditioneer
     * @param string $filename: source file
     */
    public function readLAPitches(string $filename) : void {
        $this->samplePitches=[];
        $fh=fopen(getenv("HOME") . $filename, "r");
        $curfile=NULL;
        while (($line=fgets($fh)) !== FALSE) {
            $exp=explode("\t", $line);
            if (!empty($exp[0]))
                $curfile=trim(str_replace("|", "/", $exp[0]));
            elseif (strpos($exp[1], "Resulting Frequency =")!==FALSE) {
                $pitch=floatval(substr($exp[1], 22));
                $this->samplePitches[$curfile]=$pitch;
            }
        }
        fclose($fh);
    }
            
    /*
     * Apply any pitches loaded earlier
     */
    protected function samplePitchMidi(array $hwdata): ?float {
        if (isset($this->samplePitches[$filename=$hwdata["SampleFilename"]]))
            $hwdata["Pitch_ExactSamplePitch"]=$this->samplePitches[$filename];
        return parent::samplePitchMidi($hwdata);
    }    

    /**
     * Process attack and release samples. Layer, pipe and sample file data is
     * amalgamated with the HW attack/release sample
     *
     * @param array $hwdata HW attack or release sample
     * @param bool $isattack TRUE for an attack (else a release) sample
     * @return void
     */
    public function processSamples(array $hwdata, bool $isattack) : void {
        $array=[];
        foreach($hwdata as $datum) {
            $layer=$this->hwdata->layer($datum["LayerID"]);
            $pipe=$this->hwdata->pipe($layer["PipeID"]);
            $sample=$this->hwdata->sample($datum["SampleID"]);
            $array[]=array_merge(
                    $isattack ? [] : ["ReleaseSelCriteria_LatestKeyReleaseTimeMs"=>999999], 
                    $datum, $layer, $sample, $pipe);
        }

        if (!$isattack) // Sort by PipeID and ReleaseTime
            array_multisort(array_column($array, "PipeID"), SORT_ASC,
                       array_column($array, "ReleaseSelCriteria_LatestKeyReleaseTimeMs"), SORT_ASC,
                       array_column($array, "UniqueID"), SORT_ASC,
                       $array);
        else
            array_multisort(array_column($array, "PipeID"), SORT_ASC,
                       array_column($array, "UniqueID"), SORT_ASC,
                       $array);
        

        foreach($array as $record) {
            if ($this->isNoiseSample($record))
                $this->processNoise($record, $isattack);
            else
                $this->processSample($record, $isattack);
        }
    }
    
    /**
     * Clone missing pipes in sampled tremulant ranks
     */
    public function CloneTremmed() : void {
        foreach($this->getRanks() as $id=>$frrank) {
            if ($id>0 && (($torank=$this->getRank(-$id))!==NULL)) {
                $frpipes=$frrank->Pipes();
                $topipes=$torank->Pipes();
                foreach($frpipes as $midikey=>$pipe) {
                    if (!isset($topipes[$midikey]))
                        $torank->Pipe($midikey, $pipe);
                }
            }
        }
    }
}