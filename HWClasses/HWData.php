<?php

namespace HWClasses;
require_once (__DIR__ . "/HWReader.php");

/**
 * Access single HW data records
 *
 * @author andrew
 */
class HWData extends HWReader {
    
    private function patch(string $name, array $data) {
        foreach($data as $id=>$record) {
            if (is_array($record)) {
                foreach($record as $property=>$value)
                    $this->cache[$name][$id][$property]=$value;
            }
            elseif ($record=="DELETE")
                unset($this->cache[$name][$id]);
        }
    }
            
    public function continuousControl(int $controlid) : array {
        if (!isset($this->cache["ContinuousControl"]))
            $this->continuousControls();
        return $this->cache["ContinuousControl"][$controlid];
    }

    public function continuousControlLink(int $controlid) : array {
        if (!isset($this->cache["ContinuousControlLinkageIndex"]))
            $this->continuousControlLinks();
        if (isset($this->cache["ContinuousControlLinkageIndex"][$controlid]))
            return $this->cache["ContinuousControlLinkageIndex"][$controlid];
        else
            return [];
    }
    
    
    public function displayPage(int $pageid) : array {
        if (!isset($this->cache["DisplayPage"]))
            $this->displayPages();
        return $this->cache["DisplayPage"][$pageid];
    }
    
    public function patchDisplayPages(array $data) {
        $this->displayPages();
        $this->patch("DisplayPage", $data);
    }

    
    public function division(int $divisionid) : array {
        if (!isset($this->cache["Division"]))
            $this->divisions();
        return $this->cache["Division"][$divisionid];
    }

    public function patchDivisions(array $data) {
        $this->divisions();
        $this->patch("Division", $data);
    }

    
    public function divisionInput(int $divisionInputid) : array {
        if (!isset($this->cache["DivisionInput"]))
            $this->divisionInputs();
        return $this->cache["DivisionInput"][$divisionInputid];
    }

    
    public function enclosure(int $enclosureid) : array {
        if (!isset($this->cache["Enclosure"]))
            $this->enclosures();
        return $this->cache["Enclosure"][$enclosureid];
    }
    
    public function patchEnclosures(array $data) {
        $this->enclosures();
        $this->patch("Enclosure", $data);
    }


    public function enclosurePipe(int $enclosurePipeid) : array {
        if (!isset($this->cache["EnclosurePipe"]))
            $this->enclosurePipes();
        return $this->cache["EnclosurePipe"][$enclosurePipeid];
    }

    public function imageSetElement(int $imageSetElementid, $softfail=FALSE) : ? array {
        if (!isset($this->cache["ImageSetElementIndex"]))
            $this->imageSetElements();
        if ($softfail && !isset($this->cache["ImageSetElementIndex"][$imageSetElementid]))
            return NULL;
        return $this->cache["ImageSetElementIndex"][$imageSetElementid];
    }

    public function imageSet(int $imageSetid, $softfail=FALSE) : ? array {
        if (!isset($this->cache["ImageSet"]))
            $this->imageSets();
        if ($softfail && !isset($this->cache["ImageSet"][$imageSetid]))
            return NULL;
        return $this->cache["ImageSet"][$imageSetid];
    }

    public function imageSetInstance(int $imageSetInstanceid, bool $softfail=FALSE) : ?array {
        if (!isset($this->cache["ImageSetInstance"]))
            $this->imageSetInstances();
        if ($softfail && !isset($this->cache["ImageSetInstance"][$imageSetInstanceid]))
            return NULL;
        return $this->cache["ImageSetInstance"][$imageSetInstanceid];
    }

    public function keyAction(int $keyActionid) : array {
        if (!isset($this->cache["KeyAction"]))
            $this->keyActions();
        return $this->cache["KeyAction"][$keyActionid];
    }
    
    // Cave - not indexed
    public function patchImageSets(array $data) {
        $this->ImageSets();
        $this->patch("ImageSet", $data);
    }

    // Cave - not indexed
    public function patchKeyActions(array $data) {
        $this->keyActions();
        $this->patch("KeyAction", $data);
    }

    
    public function keyboard(int $keyboardid) : array {
        if (!isset($this->cache["Keyboard"]))
            $this->keyboards();
        return $this->cache["Keyboard"][$keyboardid];
    }

    public function patchKeyboard(array $data) {
        $this->keyboards();
        $this->patch("Keyboard", $data);
    }

    
    public function keyboardKey(int $keyboardKeyid) : array {
        if (!isset($this->cache["KeyboardKey"]))
            $this->keyboardKeys();
        return $this->cache["KeyboardKey"][$keyboardKeyid];
    }
    
    public function keyImageSet(int $keyImageSetid) : array {
        if (!isset($this->cache["KeyImageSet"]))
            $this->keyImageSets();
        return $this->cache["KeyImageSet"][$keyImageSetid];
    }

    public function patchKeyImageSets(array $data) {
        $this->keyImageSets();
        $this->patch("KeyImageSet", $data);
    }

    
    public function pipe(int $pipeid) : array {
        if (!isset($this->cache["Pipe_SoundEngine01"]))
            $this->pipes();
        return $this->cache["Pipe_SoundEngine01"][$pipeid];
    }

    public function layer(int $layerid) : array {
        if (!isset($this->cache["Pipe_SoundEngine01_Layer"]))
            $this->layers();
        return $this->cache["Pipe_SoundEngine01_Layer"][$layerid];
    }
    
    public function attack(int $attackid) : array {
        if (!isset($this->cache["Pipe_SoundEngine01_AttackSample"]))
            $this->attacks();
        return $this->cache["Pipe_SoundEngine01_AttackSample"][$attackid];
    }
    
    public function release(int $releaseid) : array {
        if (!isset($this->cache["Pipe_SoundEngine01_ReleaseSample"]))
            $this->releases();
        return $this->cache["Pipe_SoundEngine01_ReleaseSample"][$releaseid];
    }
    
    public function sample(int $sampleid) : array {
        if (!isset($this->cache["Sample"]))
            $this->samples();
        return $this->cache["Sample"][$sampleid];
    }
    
    public function rank(int $rankid, bool $hardfail=TRUE) : ? array {
        if (!isset($this->cache["Rank"]))
            $this->ranks();
        if ($hardfail || isset($this->cache["Rank"][$rankid]))
            return $this->cache["Rank"][$rankid];
        else
            return [];
    }
    
    public function patchRanks(array $data) {
        $this->ranks();
        $this->patch("Rank", $data);
    }


    public function sampledRank(int $rankid) : bool {
        if (!isset($this->cache["SampledRanksIndex"])) {
            $result=[];
            foreach(["attacks", "releases"] as $samples) {
                foreach($this->$samples() as $sample) {
                    $layer=$this->layer($sample["LayerID"]);
                    $pipe=$this->pipe($layer["PipeID"]);
                    $result[$pipe["RankID"]]=TRUE;
                }
            }
            $this->cache["SampledRanksIndex"]=$result;
        }
        return (isset($this->cache["SampledRanksIndex"][$rankid]));
    }

    public function stop(int $stopid, bool $hardfail=TRUE) : ? array {
        if (!isset($this->cache["Stop"]))
            $this->stops();
        if ($hardfail || isset($this->cache["Stop"][$stopid]))
            return $this->cache["Stop"][$stopid];
        else
            return NULL;
    }
    
    public function patchStops(array $data) {
        $this->stops();
        $this->patch("Stop", $data);
    }


    public function stopRank(int $stopid) : array {
        if (!isset($this->cache["StopRankIndex"]))
            $this->stopRanks();
        if (isset($this->cache["StopRankIndex"][$stopid]))
            return $this->cache["StopRankIndex"][$stopid];
        else
            return[];
    }

    // Cave - data is not indexed!
    public function patchStopRanks(array $data) {
        $this->stopRanks();
        $this->patch("StopRank", $data);
        unset($this->cache["StopRankIndex"]);
        unset($this->cache["AltStopRankIndex"]);
        unset($this->cache["RankStopIndex"]);
        unset($this->cache["AltRankStopIndex"]);
    }


    public function altStopRank(int $stopid) : array {
        if (!isset($this->cache["AltStopRankIndex"]))
            $this->altStopRanks();
        if (isset($this->cache["AltStopRankIndex"][$stopid]))
            return $this->cache["AltStopRankIndex"][$stopid];
        else
            return[];
    }
    
    public function rankStop(int $rankid) : array {
        if (!isset($this->cache["RankStopIndex"]))
            $this->rankStops();
        if (isset($this->cache["RankStopIndex"][$rankid]))
            return $this->cache["RankStopIndex"][$rankid];
        else
            return[];
    }
    
    public function altRankStop(int $rankid) : array {
        if (!isset($this->cache["AltRankStopIndex"]))
            $this->altRankStops();
        if (isset($this->cache["AltRankStopIndex"][$rankid]))
            return $this->cache["AltRankStopIndex"][$rankid];
        else
            return[];
    }

    public function switch(int $switchid, bool $checkexists=FALSE) : ?array {
        if (!isset($this->cache["Switch"]))
            $this->switches();
        if ($checkexists && !isset($this->cache["Switch"][$switchid]))
            return NULL;
        return $this->cache["Switch"][$switchid];
    }
    
    public function switchLink(int $switchid) : array {
        if (!isset($this->cache["SwitchLinkageIndex"]))
            $this->switchLinks();
        if (isset($this->cache["SwitchLinkageIndex"][$switchid]))
            return $this->cache["SwitchLinkageIndex"][$switchid];
        else
            return [];
    }
    
    public function tremulant(int $tremulantid) : array {
        if (!isset($this->cache["Tremulant"]))
            $this->tremulants();
        return $this->cache["Tremulant"][$tremulantid];
    }
    
    public function patchTremulants(array $data) {
        $this->tremulants();
        $this->patch("Tremulant", $data);
    }


    public function textInstance(int $attachedToImageSetInstanceID) : array {
        if (!isset($this->cache["TextInstanceIndex"])) {
            $instances=[];
            foreach($this->textInstances() as $textinstance)
                if (isset($textinstance["AttachedToImageSetInstanceID"]))
                    $instances[$textinstance["AttachedToImageSetInstanceID"]][]=$textinstance;
            $this->cache["TextInstanceIndex"]=$instances;
        }
        if (isset($this->cache["TextInstanceIndex"][$attachedToImageSetInstanceID]))
            return $this->cache["TextInstanceIndex"][$attachedToImageSetInstanceID];
        else
            return [];
    }

    public function textStyle(int $textStyleID) : array {
        if (!isset($this->cache["TextStyle"]))
            $this->textStyles();
        return $this->cache["TextStyle"][$textStyleID];
    }

    public function windCompartment(int $windCompartmentid) : array {
        if (!isset($this->cache["WindCompartment"]))
            $this->windCompartments();
        return $this->cache["WindCompartment"][$windCompartmentid];
    }

    public function patchWindCompartments(array $data) {
        $this->windCompartments();
        $this->patch("WindCompartment", $data);
    }
}