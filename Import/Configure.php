<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 *
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 *
 */

namespace Import;
require_once(__DIR__ . "/Create.php");

/**
 * HW Import Configuration layer, mapping HW->GO
 *
 * @author andrew
 */
abstract Class Configure extends Create {

    /**
     * Create the Organ. <b>This must be called first</b>
     * @param array $hwdata - HW General Data
     * @return void
     */
    public function createOrgan(array $hwdata) : \GOClasses\Organ {
        $organ=parent::createOrgan(["ChurchName"=>$hwdata["Identification_Name"]]);
        $map=[
            ["ChurchAddress","Identification_Name"],
            ["ChurchAddress","OrganInfo_Location"],
            ["OrganBuilder","OrganInfo_Builder"],
            ["OrganBuildDate","OrganInfo_BuildDate"],
            ["OrganComments","OrganInfo_Comments"],
        ];
        if (isset($hwdata["OrganInfo_InfoFilename"]))
            $organ->InfoFilename=
                    sprintf("OrganInstallationPackages/%06d/%s",
                            $hwdata["Identification_UniqueOrganID"],
                            $hwdata["OrganInfo_InfoFilename"]);
        $this->map($map, $hwdata, $organ);
        if (isset($hwdata["AudioOut_AmplitudeLevelAdjustDecibels"]))
            $organ->Gain=floatval($hwdata["AudioOut_AmplitudeLevelAdjustDecibels"]);
        return $organ;
    }

    /**
     * Create a panel. Panel 0 must be first!
     * @param array $hwdata. For panel 0 this will be HW General data
     *                          Other panels will be custom (PanelID + Name)
     * @return \GOClasses\Panel
     */
    public function createPanel(array $hwdata) : ?\GOClasses\Panel {
        $panelid=(isset($hwdata["PanelID"])) ? $hwdata["PanelID"]
                : (isset($hwdata["PageID"]) ? $hwdata["PageID"] : FALSE);
        if ($panelid===FALSE)
            return NULL;
        else {
            $hwdata["PanelID"]=$panelid;
            $panel=parent::createPanel($hwdata);
            $map=[
                ["Name","Identification_LCDDisplayShortName"],
                ["Name","Name"],
                ["Group","Group"],
                ["DispScreenSizeHoriz","Display_ConsoleScreenWidthPixels"],
                ["DispScreenSizeVert","Display_ConsoleScreenHeightPixels"]
            ];
            $this->map($map, $hwdata, $panel);
            return $panel;
        }
    }

    /**
     * Create a manual. Pedals must be first!
     *
     * @param array $hwdata HW Keyboard data
     * @return \GOClasses\Manual
     */
    public function createManual(array $hwdata) : ?\GOClasses\Manual {
        $manual=parent::createManual(
                ["ManualID"=>$hwdata["KeyboardID"], "Name"=>$hwdata["Name"]]);
        $map=[
            ["NumberOfAccessibleKeys","KeyGen_NumberOfKeys"],
            ["NumberOfLogicalKeys","KeyGen_NumberOfKeys"],
            ["NumberOfAccessibleKeys","InpGen_NumberOfInputs"],
            ["NumberOfLogicalKeys","InpGen_NumberOfInputs"],
            ["FirstAccessibleKeyMIDINoteNumber","KeyGen_MIDINoteNumberOfFirstKey"],
            ["FirstAccessibleKeyMIDINoteNumber","InpGen_MIDINoteNumberOfFirstInput"],
            ["PositionX","KeyGen_DispKeyboardLeftXPos"],
            ["PositionY","KeyGen_DispKeyboardTopYPos"],
        ];
        $this->map($map, $hwdata, $manual);
        return $manual;
    }

    /**
     * Allocate a stop to a manual. Assumed same as DivisionID
     *
     * @param array $hwdata
     * @return int - Manual ID
     */
    protected function stopManualID(array $hwdata) : int {
        if (isset($hwdata["ManualID"]))
            return $hwdata["ManualID"];
        elseif (isset($hwdata["SourceKeyboardID"]))
            return $hwdata["SourceKeyboardID"];
        return $hwdata["DivisionID"];
    }

    /**
     * Determine if the stop is used (has samples)
     * 
     * @param array $hwdata - HW stop data
     * @return boolean
     */
    protected function stopInUse(array $hwdata) : bool {
        return TRUE;
    }

    /**
     * Configure switch images on the panels
     * 
     * @param \GOClasses\Sw1tch|null $switch
     * @param array $data
     * @return void
     * @throws \Exception
     */
    protected function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
        throw new \Exception("Method configurePanelSwitchImages needs to be implemented!");
    }

    /**
     * Create a Stop and its controlling switch
     *
     * @param array $hwdata HW Stop data, containing at least StopID and NAme
     *
     * @return \GOClasses\Sw1tch
     */
    public function createStop(array $hwdata) : ?\GOClasses\Sw1tch {
        if (!isset($hwdata["SwitchID"])) $hwdata["SwitchID"]=$hwdata["ControllingSwitchID"];
        if ($this->stopInUse($hwdata)) {
            if (!isset($hwdata["DivisionID"])) {
                $hwdata["DivisionID"]=$this->stopManualID($hwdata);
            }
            $switch=parent::createStop($hwdata);
            $stop=$this->getStop($hwdata["StopID"]);
            if ($switch!==NULL) {
                if (isset($hwdata["Engaged"])) {
                    $switch->DefaultToEngaged=$hwdata["Engaged"];
                    $switch->GCState=-1;
                }
                if (isset($hwdata["StoreInGeneral"])) {
                    $stop->StoreInGeneral=$hwdata["StoreInGeneral"];
                }
                if (isset($hwdata["StoreInDivisional"])) {
                    $stop->StoreInDivisional=$hwdata["StoreInDivisional"];
                }
                $this->configurePanelSwitchImages($switch, $hwdata);
            }
            elseif (isset($hwdata["Engaged"]) && $stop!==NULL) {
                $stop->DefaultToEngaged=$hwdata["Engaged"];
                $stop->GCState=-1;
            }

            return $switch;
        }
        else {
            $this->configurePanelSwitchImages(NULL, $hwdata); 
            return NULL;
        }
    }

    /**
     * Create a noise for the requested Tremulant/Coupler/Stop
     * 
     * @param string $type  : self::TremulantNoise, self::CouplerNoise, self::SwitchNoise
     * @param array $switchdata
     * @return \GOClasses\Noise|null
     */
    public function createSwitchNoise(string $type, array $switchdata): void {
        switch ($type) {
            case self::TremulantNoise:
            case self::SwitchNoise:    
                $switchid=
                    isset($switchdata["SwitchID"]) 
                    ? $switchdata["SwitchID"] 
                    : $switchdata["ControllingSwitchID"];
                break;
            
            case self::CouplerNoise:
                $switchid=
                    isset($switchdata["SwitchID"]) 
                    ? $switchdata["SwitchID"] 
                    : $switchdata["ConditionSwitchID"];
                break;
        }
        
        if ($switchid!==NULL && ($switch=$this->getSwitch($switchid))!==NULL) {
            $name=$switch->Name;
            $windchestgroup=$this->getWindchestGroup($switchdata["GroupID"]);
            if (!$windchestgroup) {return;}
            $manual=$this->getManual(
                    isset($switchdata["DivisionID"]) && !empty($switchdata["DivisionID"])
                    ? $switchdata["DivisionID"] : 1);
            $on=$this->newSwitchNoise($switchid, "$name (on)");
            $on->WindchestGroup($windchestgroup);
            $on->Switch($switch);
            $manual->Stop($on);
            $off=$this->newSwitchNoise(-$switchid, "$name (off)");
            $off->WindchestGroup($windchestgroup);
            $manual->Stop($off);
            $off->Function="Not";
            $off->Switch($switch);
            unset($off->SwitchCount);
        }
    }

    /**
     * Create a "tremulant" and associated switches
     * Where there are separate tremmed stops, we actually
     * create 2 switches (1 for On and 1 for Off)
     * 
     * @param array $hwdata including TremulantID, Name, SwitchID (optional) and Type
     * for Type != "Switched", we also need GroupID
     * @return \GOClasses\Coupler
     */
    public function createTremulant(array $hwdata) : ?\GOClasses\Sw1tch {
        $map=[
            ["Period","Period"],
            ["AmpModDepth","AmpModDepth"],
            ["StartRate","StartRate"],
            ["StopRate","StopRate"],
        ];
        
        if (!isset($hwdata["SwitchID"])) $hwdata["SwitchID"]=$hwdata["ControllingSwitchID"];
        $switch=parent::createTremulant($hwdata);
        if (($tremulant=$this->getTremulant($hwdata["TremulantID"],FALSE))) {$this->map($map, $hwdata, $tremulant);}
        $this->configurePanelSwitchImages($switch, $hwdata);
        return $switch;
    }

    public function createWindchestGroup(array $groupdata) : ?\GOClasses\WindchestGroup {
        if (!isset($groupdata["GroupID"])) $groupdata["GroupID"]=$groupdata["DivisionID"];
        return parent::createWindchestGroup($groupdata);
    }
    
    /**
     * Obtain stop data for a rank. Stub - see Organ.php
     * 
     * @param int $rankid
     * @param int $stopid
     * @return array
     * @throws \Exception
     */
    protected function rankStopData (int $rankid, int $stopid) : array {
        throw new \Exception("Method rankStopData needs to be implemented!");
    }
    
    /**
     * Allocate a rank to its stops
     *
     * @param array $hwdata
     * @return int Stop ID
     * @throws \Exception
     */
    protected function rankStopIDs(array $hwdata) : array {
        throw new \Exception("Method rankStopIDs needs to be implemented!");
    }

    /**
     * Allocate a rank to its Windchest Group
     *
     * @param array $hwdata
     * @return int Windchest Group ID
     * @throws \Exception
     * @deprecated
     */
    protected function rankGroupID(array $hwdata) : int {
        throw new \Exception("Method rankGroupID needs to be implemented!");
    }

    /**
     * Determine if the rank is used (has samples)
     * 
     * @param array $hwdata - HW stop data
     * @return boolean
     */
    protected function rankInUse(array $hwdata) : bool {
        return TRUE;
    }
    
    /**
     * Create a Rank
     *
     * @param array $hwdata HW Rank data
     * @return \GOClasses\Rank
     * 
     * NoviSad. GrossQuinta
     */
    public function createRank(array $hwdata, bool $keynoise=FALSE) : ?\GOClasses\Rank {
        if (isset($hwdata["Noise"]) &&
             in_array($hwdata["Noise"], ["StopOn","StopOff"])) return NULL;

        if ($this->rankInUse($hwdata)) {
            if (!isset($hwdata["StopIDs"]))
                $hwdata["StopIDs"]=$this->rankStopIDs($hwdata);
            if (!isset($hwdata["GroupID"]))
                $hwdata["GroupID"]=$this->rankGroupID($hwdata);
            $rankid=$hwdata["RankID"];
            $keynoise=isset($hwdata["Noise"])
                    && in_array($hwdata["Noise"], ["KeyOn","KeyOff"]);
            $rank=parent::createRank($hwdata, $keynoise);
            if ($rank!==NULL) {
                foreach($hwdata["StopIDs"] as $stopid) {
                    $srdata=$this->rankStopData($rankid, abs($stopid));
                    $stop=$this->getStop($stopid);
                    if ($stop!==NULL 
                            && !($stop instanceof \GOClasses\AmbientNoise)
                            && !($stop instanceof \GOClasses\SwitchNoise)
                            && !($rank instanceof \GOClasses\KeyNoise)) {
                        $ranknum=$stop->int2str($stop->NumberOfRanks);
                        if (isset($srdata["MIDINoteNumIncrementFromDivisionToRank"])) {
                                if ($srdata["MIDINoteNumIncrementFromDivisionToRank"]>0)
                                    $stop->set("Rank${ranknum}FirstPipeNumber", 1+$srdata["MIDINoteNumIncrementFromDivisionToRank"]);
                                elseif ($srdata["MIDINoteNumIncrementFromDivisionToRank"]<0)
                                    $stop->set("Rank${ranknum}FirstAccessibleKeyNumber", 1-$srdata["MIDINoteNumIncrementFromDivisionToRank"]);
                        }
                        if (isset($srdata["NumberOfMappedDivisionInputNodes"])
                                && !empty($srdata["NumberOfMappedDivisionInputNodes"]))
                            $stop->set("Rank${ranknum}PipeCount", $srdata["NumberOfMappedDivisionInputNodes"]);
                        if (isset($srdata["MIDINoteNumOfFirstMappedDivisionInputNode"]) 
                                && ($srdata["MIDINoteNumOfFirstMappedDivisionInputNode"]>1)) 
                            $stop->set("Rank${ranknum}FirstAccessibleKeyNumber", 
                                    $srdata["MIDINoteNumOfFirstMappedDivisionInputNode"] - 35);
                    }
                }
                if (isset($hwdata["PitchTuning"]) 
                        && ($hwdata["PitchTuning"]>1)) 
                    $rank->set("PitchTuning", $hwdata["PitchTuning"]);
            }
        }
        else
            $rank=NULL;
        return $rank;
    }

    /**
     * Create a coupler (and associated switch)
     * @param array $hwdata HW Key Action data
     * @return \GOClasses\Sw1tch
     */
    public function createCoupler(array $hwdata) : ?\GOClasses\Sw1tch {
        if (!isset($hwdata["SwitchID"])) $hwdata["SwitchID"]=$hwdata["ConditionSwitchID"];
        if (!isset($hwdata["CouplerID"])) $hwdata["CouplerID"]=$hwdata["ConditionSwitchID"];
        $hwdata["ManualID"]=$hwdata["SourceKeyboardID"];
        $switch=parent::createCoupler($hwdata);
        $this->configurePanelSwitchImages($switch, $hwdata); 
        $coupler=$this->getCoupler($hwdata["CouplerID"]);
        $manualid=
                isset($hwdata["DestDivisionID"]) 
                ? $hwdata["DestDivisionID"] 
                : $hwdata["DestKeyboardID"];
        $coupler->DestinationManual
                =intval($this->getManual($manualid)->instance());
        if (isset($hwdata["MIDINoteNumberIncrement"])
                && !empty($hwdata["MIDINoteNumberIncrement"]))
            $coupler->DestinationKeyshift=$hwdata["MIDINoteNumberIncrement"];
        if (isset($hwdata["MIDINoteNumOfFirstSourceKey"])
                && !empty($hwdata["MIDINoteNumOfFirstSourceKey"]))
            $coupler->FirstMIDINoteNumber=$hwdata["MIDINoteNumOfFirstSourceKey"];
        if (isset($hwdata["NumberOfKeys"])
                && !empty($hwdata["NumberOfKeys"]))
            $coupler->NumberOfKeys=$hwdata["NumberOfKeys"];
        if (isset($hwdata["ActionTypeCode"])) {
            switch ($hwdata["ActionTypeCode"]) {
                case 1: // Regular
                    break;
                case 2: // Bass ?
                    $coupler->CouplerType="Bass ";
                    break;
                case 3: // Melody ?
                    $coupler->CouplerType="Melody";
                    break;
                default:
                    throw new \Exception("Unknown ActionTypeCode (" . $hwdata["ActionTypeCode"]
                            . ") in Coupler ". $hwdata["Name"]);
            }
        }
        unset($coupler->DefaultToEngaged);
        unset($coupler->DispLabelColour);
        if (empty($coupler->DestinationKeyshift) &&
                $hwdata["SourceKeyboardID"]==$manualid) {
            $coupler->UnisonOff="Y";
            unset($coupler->CouplerType);                
        }
        $coupler->Displayed="N";
        return $switch;
    }

    /**
     * Create enclosure images
     * 
     * @param \GOClasses\Enclosure $enclosure
     * @param array $data
     * @return void
     */
    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
        throw new \Exception("Method configurePanelEnclosureImages needs to be implemented!");
    }
    
    /**
     * Create an Enclosure
     * 
     * @param array $hwdata
     * @return \GOClasses\Manual
     */
    public function createEnclosure(array $hwdata): ?\GOClasses\Enclosure {
        $enclosure=parent::createEnclosure($hwdata);
        $map=[
            ["AmpMinimumLevel","AmpMinimumLevel"]
        ];
        $this->map($map, $hwdata, $enclosure);
        $this->configurePanelEnclosureImages($enclosure, $hwdata);
        return $enclosure;
    }

    /**
     * Determine midi key for sample
     *
     * @param array $hwdata Amalgamated Attack or Release data
     * @return int
     * @throws \Exception
     */
    protected function sampleMidiKey(array $hwdata) : int {
        if (isset($hwdata["NormalMIDINoteNumber"])
                && !empty($hwdata["NormalMIDINoteNumber"]))
            return $hwdata["NormalMIDINoteNumber"];
        elseif(isset($hwdata["Pitch_NormalMIDINoteNumber"])
                && !empty($hwdata["Pitch_NormalMIDINoteNumber"]))
            return $hwdata["Pitch_NormalMIDINoteNumber"];
        elseif(isset($hwdata["SampleFilename"])
                && !empty($hwdata["SampleFilename"])) {
            $paths=explode("/", $hwdata["SampleFilename"]);
            $midi=intval($paths[array_key_last($paths)]);
            if ($midi>=0 && $midi<=127) return $midi;
        }
        throw new \Exception("Unable to determine midi key for pipe " . $hwdata["PipeID"]);
    }

    /**
     * Convert Hz to Midi note+fraction
     * @param float $hz Pitch in Hz
     * @return float Midi note+fraction
     * https://www.music.mcgill.ca/~gary/307/week1/node28.html
     */
    protected function HzToMidi(float $hz) {
        return  12 * log(floatval($hz) / 220.0) / log(2.0) + 57;
    }

    /**
     * Convert  Midi note+fraction to Hz
     * @param float $hz Pitch in Hz
     * @return float Midi note+fraction
     * https://www.music.mcgill.ca/~gary/307/week1/node28.html
     */
    protected function MidiToHz(float $midi) {
        return  440 * pow(2.0, ($midi-69)/12);
    }

    /**
     * Determine midi note of a pipe (exact if possible) for Pitch Correction
     * @param array $hwdata Amalgamated Attack or Release data
     * @return float|null
     */
    protected function pipePitchMidi(array $hwdata) : ?float {
        if (isset($hwdata["Pitch_OriginalOrgan_PitchHz"])
                && !empty($hwdata["Pitch_OriginalOrgan_PitchHz"]))
            return $this->HzToMidi($hwdata["Pitch_OriginalOrgan_PitchHz"]);
        elseif (isset($hwdata["NormalMIDINoteNumber"])
                && !empty($hwdata["NormalMIDINoteNumber"]))
            return floatval($hwdata["NormalMIDINoteNumber"]);
        else
            return NULL;
    }

    /**
     * Determine midi note of a sample (exact if possible) for Pitch Correction
     * @param array $hwdata Amalgamated Attack or Release data
     * @return float|null
     */
    protected function samplePitchMidi(array $hwdata) : ?float {
        if (isset($hwdata["Pitch_ExactSamplePitch"])
                && !empty($hwdata["Pitch_ExactSamplePitch"]))
            return $this->HzToMidi($hwdata["Pitch_ExactSamplePitch"]);
        elseif(isset($hwdata["Pitch_NormalMIDINoteNumber"])
                && !empty($hwdata["Pitch_NormalMIDINoteNumber"]))
            return floatval($hwdata["Pitch_NormalMIDINoteNumber"]);
        elseif(isset($hwdata["SampleFilename"])
                && !empty($hwdata["SampleFilename"])) {
            $paths=explode("/", $hwdata["SampleFilename"]);
            $midi=intval($paths[array_key_last($paths)]);
            if ($midi>=0 && $midi<=127) return floatval($midi);
        }
        return NULL;
    }

    /**
     * Determine pitch correction for an attack/release
     *
     * @param array $hwdata Amalgamated Attack or Release data
     * @return float|null
     */
    protected function sampleTuning(array $hwdata) : ?float {
        if (empty($sample=$this->samplePitchMidi($hwdata))
                || empty($pipe=$this->pipePitchMidi($hwdata))) {
            return NULL; }
        else
            return 100*($pipe-$sample);
    }

    /**
     * Determine harmonic number for an attack/release
     *
     * @param array $hwdata Amalgamated Attack or Release data
     * @return float|null
     */
    protected function sampleHarmonicNumber(array $hwdata) : ?float {
        if (isset($hwdata["Pitch_Tempered_RankBasePitch64ftHarmonicNum"])
                && !empty($hwdata["Pitch_Tempered_RankBasePitch64ftHarmonicNum"]))
            return $hwdata["Pitch_Tempered_RankBasePitch64ftHarmonicNum"];
        elseif(isset($hwdata["Pitch_RankBasePitch64ftHarmonicNum"])
                && !empty($hwdata["Pitch_RankBasePitch64ftHarmonicNum"]))
            return $hwdata["Pitch_RankBasePitch64ftHarmonicNum"];
        else
            return 8;
    }

    /**
     * Configure an attack sample
     *
     * @param array $hwdata - Amalgamated sample data
     * @param \GOClasses\Pipe $pipe
     * @return void
     */
    protected function configureAttack(array $hwdata, \GOClasses\Pipe $pipe) : void {
        if (isset($hwdata["LoopCrossfadeLengthInSrcSampleMs"]) 
                && $hwdata["LoopCrossfadeLengthInSrcSampleMs"]>120)
            throw new \Exception(
                    "LoopCrossfadeLength " 
                    . $hwdata["LoopCrossfadeLengthInSrcSampleMs"] 
                    . ">120, attack "
                    . $hwdata["UniqueID"] . " "
                    . $hwdata["SampleFilename"]);
        if ($pipe instanceof \GOClasses\Noise) 
            $map=[
                ["Attack","SampleFilename"],
                ["Gain","AmpLvl_LevelAdjustDecibels"]
            ];
        else
            $map=[
                ["Attack","SampleFilename"],
                ["AttackStart","LoadSampleRange_StartPositionValue"],
                //["AttackVelocity","AttackSelCriteria_HighestVelocity"],
                ["AttackMaxTimeSinceLastRelease","AttackSelCriteria_MinTimeSincePrevPipeCloseMs"],
                ["LoopCrossfadeLength","LoopCrossfadeLengthInSrcSampleMs"],
                ["Gain","AmpLvl_LevelAdjustDecibels"]
            ];
        $this->map($map, $hwdata, $pipe);
        if (isset($hwdata["IsTremulant"])) $pipe->AttackIsTremulant=$hwdata["IsTremulant"];
    }

    /**
     * Configure an release sample
     *
     * @param array $hwdata - Amalgamated sample data
     * @param \GOClasses\Pipe $pipe
     * @return void
     */
    protected function configureRelease(array $hwdata, \GOClasses\Pipe $pipe) : void {
        if (isset($hwdata["ReleaseCrossfadeLengthMs"]) 
                && $hwdata["ReleaseCrossfadeLengthMs"]>200)
            throw new \Exception(
                    "ReleaseCrossfadeLength " 
                    . $hwdata["ReleaseCrossfadeLengthMs"] 
                    . ">200, release "
                    . $hwdata["UniqueID"] . " "
                    . $hwdata["SampleFilename"]);
        if ($pipe instanceof \GOClasses\Noise)
            $map=[
                ["Release","SampleFilename"],
                ["Gain","AmpLvl_LevelAdjustDecibels"],
                ["ReleaseMaxKeyPressTime","ReleaseSelCriteria_LatestKeyReleaseTimeMs"],
            ];
        else
            $map=[
                ["Release","SampleFilename"],
                ["ReleaseCuePoint","LoadSampleRange_StartPositionValue"],
                ["ReleaseEnd","LoadSampleRange_EndPositionValue"],
                ["ReleaseCrossfadeLength","ReleaseCrossfadeLengthMs"],
                ["ReleaseMaxKeyPressTime","ReleaseSelCriteria_LatestKeyReleaseTimeMs"],
            ];
        $this->map($map, $hwdata, $pipe);
        if (isset($hwdata["IsTremulant"])) $pipe->ReleaseIsTremulant=$hwdata["IsTremulant"];
    }

    /**
     * Correction to file name - implementation dependant
     * 
     * @param string $filename
     * @return string - corrected name 
     */
    protected function correctFileName(string $filename) : string {
        return $filename;
    }

    
    /**
     * Extract filename from sample data
     * 
     * @param array $hwdata
     * @return string
     */
    protected function sampleFilename(array $hwdata) : string {
        return $this->correctFileName(
                sprintf("OrganInstallationPackages/%06d/%s",
                            $hwdata["InstallationPackageID"],
                            $hwdata["SampleFilename"]));
    }

    /**
     * Is the sample for a noise?
     *
     * @param array $hwdata Attack or Release data
     * @return int
     * @throws \Exception
     */
    protected function isNoiseSample(array $hwdata): bool {
        return isset(($rankdata=$this->hwdata->rank($hwdata["RankID"]))["Noise"])
                && in_array($rankdata["Noise"], ["StopOn","StopOff","Ambient"]);
    }

    /**
     * Process and attack/release sample
     *
     * @param array $hwdata - amalgamated data (attack/release, layer, pipe, sample)
     * @param bool $attack - sample is attack (else a release)
     * @return \GOClasses\Pipe
     */
    public function processSample(array $hwdata, bool $isattack) : ?\GOClasses\Pipe {
        $pipe=$this->getPipe($pipeid=$hwdata["PipeID"]);
        if ($pipe===NULL) {
            $rank=$this->getRank($hwdata["RankID"]);
            if ($rank===NULL) return NULL;
            $midikey=$this->sampleMidiKey($hwdata);
            $pipe=$this->newPipe($pipeid, $rank, $midikey);
        }

        if (!isset($pipe->PitchTuning)
                && !empty($pt=$this->sampleTuning($hwdata)))
            $pipe->PitchTuning=$pt;
        if (!isset($pipe->HarmonicNumber)
                && !empty($hn=$this->sampleHarmonicNumber($hwdata)))
            $pipe->HarmonicNumber=$hn;

        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        if ($isattack)
            $this->configureAttack($hwdata, $pipe);
        else
            $this->configureRelease($hwdata, $pipe);
        return $pipe;
    }

    /**
     * Allocate a noise sample to a stop
     *
     * @param array $hwdata Attack or Release data
     * @return int
     * @throws \Exception
     * @deprecated
     */
    protected function noiseStop(array $hwdata, bool $isattack) : ?\GOClasses\SwitchNoise {
        return NULL;
    }

    /**
     * Process a stop noise sample
     * @param array $hwdata - amalgamated data (attack/release, layer, pipe, sample)
     * @param bool $attack - sample is attack (else a release)
     * @return \GOClasses\Noise
     */
    public function processNoise(array $hwdata, bool $isattack) : ?\GOClasses\Pipe {
        $pipeid=$hwdata["PipeID"];
        $pipe=$this->getPipe($pipeid);
        if ($pipe===NULL) {
            $stop=$this->noiseStop($hwdata, $isattack);
            if ($stop!==NULL) {
                $pipe=$this->newPipe($pipeid, $stop);
            }
            else
                return NULL;
        }
        
        $hwdata["SampleFilename"]=$this->sampleFilename($hwdata);
        if ($isattack)
            $this->configureAttack($hwdata, $pipe);
        else
            $this->configureRelease($hwdata, $pipe);
        return $pipe;
    }

    /**
     * Map HW data to GO object
     *
     * @param array $map: Data to map (to=>fr)
     * @param array $source: HW data
     * @param ODFData $dest: GO object
     */
    protected function map(array $map, array $source, \GOClasses\GOBase $dest) : void {
        foreach ($map as $m) {
            $go=$m[0];
            $hw=$m[1];
            if (array_key_exists($hw, $source)) {
                $value=$source[$hw];
                if (($value!=="") && ($value!==NULL)) {
                    $dest->$go=$value;
                }
            }
        }
    }

    /**
     * Save the finished result
     * 
     * @param string $organfile - output GO ODF
     */
    public function saveODF(string $organfile, string $comments="") {
        if (substr($organfile,0,1)=="/")
            $organfile=getenv("HOME") . $organfile;
        $comments .=
              "This GrandOrgue Organ Definition File (ODF) is provided under the Creative Commons Non-Commercial\n"
            . "Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)\n"
            . "https://creativecommons.org/licenses/by-nc-sa/4.0/\n"
            . "\n"
            . "You are free to use this ODF. You may also share it, provided you share it with these conditions.\n"
            . "\n"
            . "Extracted from the original Hauptwerk XML by Andrew Hall\n"
            . "https://sites.google.com/view/andrews-odfs/home\n"
            . "\n";
        \GOClasses\GOObject::save($organfile, $comments);
    }
    
    public function readSamplePitch($filename) : float {
        require_once (__DIR__ . "/WavReader.php");
        $reader=new WavReader(getenv("HOME") . $filename);
        $reader->header();
        while (!$reader->isEof()) {
            $chunk=$reader->chunk();
            if ($chunk["id"]=="smpl" && $chunk["size"]>20) {
                $midi=$chunk["MIDINote"] + ($chunk["MIDICents"]/100);
                return $this->MidiToHz($midi);
            }
        }
        return 0.0;
    }
}