<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace HWClasses;

/**
 * Read (and map condensed) HW data
 * @todo - return null values to avoid iffset() as well as ! empty() later on
 *
 * @author andrew
 */
class HWReader {
    
    private $document;
    protected $cache=[];
    
    private static $maps=
        ["Combination"=>[	
                "a"=>"CombinationID",
                "b"=>"Name",
                "c"=>"CombinationTypeCode",
                "d"=>"ActivatingSwitchID",
                "e"=>"CanEngageControlledSwitches",
                "f"=>"CanDisengageControlledSwitches",
                "g"=>"AllowsCapture",
        ],"CombinationElement"=>[	
                "a"=>"CombinationElementID",
                "b"=>"CombinationID",
                "c"=>"ControlledSwitchID",
                "d"=>"CapturedSwitchID",
                "e"=>"InitialStoredStateIsEngaged",
                "f"=>"InvertStoredStateWhenActivating",
                "g"=>"MemorySwitchID",
        ],"ContinuousControl"=>[	
                "a"=>"ControlID",
                "b"=>"Name",
                "c"=>"DefaultInputOutputContinuousCtrlAsgnCode",
                "d"=>"AccessibleForInput",
                "e"=>"AccessibleForOutput",
                "f"=>"DefaultValue",
                "g"=>"RememberStateFromLastLoad",
                "h"=>"Clickable",
                "i"=>"ClickingHigherIncreasesValue",
                "j"=>"ImageSetInstanceID",
        ],"ContinuousControlDoubleLinkage"=>[	
        ],"ContinuousControlImageSetStage"=>[
                "a"=>"ImageSetID",
                "b"=>"HighestContinuousControlValue",
                "c"=>"ImageSetIndex",
        ],"ContinuousControlLinkage"=>[	
                "a"=>"SourceControlID",
                "b"=>"DestControlID",
                "c"=>"Name",
                "d"=>"LinkTypeCode",
                "e"=>"WillisTypeIncSpeedInMillisecondsPerStepWithOneStepDiff",
                "f"=>"InertiaModelTypePositiveAcceleratingCoeff",
                "g"=>"InertiaModelTypePositiveDampingCoeff",
                "h"=>"ConditionSwitchID",
                "i"=>"ConditionSwitchLinkIfEngaged",
                "j"=>"ReevaluateIfCondSwitchChangesState",
                "k"=>"InvertSourceControlValue",
                "l"=>"SourceControlValueIncrement",
                "m"=>"SourceControlValueCoefficient",
                "n"=>"SourceControlValueIndex",
        ],"ContinuousControlStageSwitch"=>[	
                "a"=>"Name",
                "b"=>"ContinuousControlID",
                "c"=>"ContinuousControlValue",
                "d"=>"ControlledSwitchID",
                "e"=>"EngageWhenValueIncreasing",
                "f"=>"EngageWhenValueDecreasing",
                "g"=>"DisengageWhenValueIncreasing",
                "h"=>"DisengageWhenValueDecreasing",
        ],"DisplayPage"=>[	
                "a"=>"PageID",
                "b"=>"Name",
                "c"=>"AlternateConsoleScreenLayout1_Include",
                "d"=>"AlternateConsoleScreenLayout2_Include",
                "e"=>"AlternateConsoleScreenLayout3_Include",
        ],"Division"=>[	
                "a"=>"DivisionID",
                "b"=>"Name",
        ],"DivisionInput"=>[	
        ],"Enclosure"=>[	
                "a"=>"EnclosureID",
                "b"=>"Name",
                "c"=>"ShutterPositionContinuousControlID",
        ],"EnclosurePipe"=>[	
                "b"=>"PipeID",
                "a"=>"EnclosureID",
        ],"ImageSet"=>[	
                "a"=>"ImageSetID",
                "b"=>"Name",
                "c"=>"InstallationPackageID",
                "d"=>"ImageWidthPixels",
                "e"=>"ImageHeightPixels",
                "f"=>"ClickableAreaLeftRelativeXPosPixels",
                "g"=>"ClickableAreaRightRelativeXPosPixels",
                "h"=>"ClickableAreaTopRelativeYPosPixels",
                "i"=>"ClickableAreaBottomRelativeYPosPixels",
        ],"ImageSetElement"=>[	
                "a"=>"ImageSetID",
                "b"=>"ImageIndexWithinSet",
                "c"=>"Name",
                "d"=>"BitmapFilename",
        ],"ImageSetInstance"=>[	
                "a"=>"ImageSetInstanceID",
                "b"=>"Name",
                "c"=>"ImageSetID",
                "d"=>"DefaultImageIndexWithinSet",
                "e"=>"DisplayPageID",
                "f"=>"ScreenLayerNumber",
                "g"=>"LeftXPosPixels",
                "h"=>"TopYPosPixels",
                "i"=>"RightXPosPixelsIfTiling",
                "j"=>"BottomYPosPixelsIfTiling",
                "k"=>"AlternateScreenLayout1_ImageSetID",
                "l"=>"AlternateScreenLayout1_LeftXPosPixels",
                "m"=>"AlternateScreenLayout1_TopYPosPixels",
                "n"=>"AlternateScreenLayout1_RightXPosPixelsIfTiling",
                "p"=>"AlternateScreenLayout1_BottomYPosPixelsIfTiling",
                "q"=>"AlternateScreenLayout2_ImageSetID",
                "r"=>"AlternateScreenLayout2_LeftXPosPixels",
                "s"=>"AlternateScreenLayout2_TopYPosPixels",
                "t"=>"AlternateScreenLayout2_RightXPosPixelsIfTiling",
                "u"=>"AlternateScreenLayout2_BottomYPosPixelsIfTiling",
                "v"=>"AlternateScreenLayout3_ImageSetID",
                "w"=>"AlternateScreenLayout3_LeftXPosPixels",
                "x"=>"AlternateScreenLayout3_TopYPosPixels",
                "y"=>"AlternateScreenLayout3_RightXPosPixelsIfTiling",
                "z"=>"AlternateScreenLayout3_BottomYPosPixelsIfTiling",
        ],"KeyAction"=>[	
                "a"=>"SourceKeyboardID",
                "b"=>"DestIsKeyboardNotDivision",
                "c"=>"DestKeyboardID",
                "d"=>"DestDivisionID",
                "e"=>"Name",
                "f"=>"ConditionSwitchID",
                "g"=>"ConditionSwitchLinkIfEngaged",
                "h"=>"ActionTypeCode",
                "i"=>"ActionEffectCode",
                "j"=>"PipeMIDINoteNum036_PizzOrReitPeriodMs",
                "k"=>"PipeMIDINoteNum096_PizzOrReitPeriodMs",
                "l"=>"MIDINoteNumOfFirstSourceKey",
                "m"=>"NumberOfKeys",
                "n"=>"MIDINoteNumberIncrement",
        ],"Keyboard"=>[	
                "a"=>"KeyboardID",
                "b"=>"Name",
                "c"=>"ShortName",
                "?"=>"DefaultInputOutputKeyboardAsgnCode",
                "?"=>"AccessibleForInput",
                "?"=>"AccessibleForOutput",
                "d"=>"Hint_PrimaryAssociatedDivisionID",
                "?"=>"Hint_SecondAssociatedDivisionID",
                "?"=>"Hint_ThirdAssociatedDivisionID",
                "?"=>"Hint_MasterCouplersKeyboardAsgnCode",
                "g"=>"KeyGen_GenerateKeysAutomatically",
                "h"=>"KeyGen_NumberOfKeys",
                "i"=>"KeyGen_MIDINoteNumberOfFirstKey",
                "j"=>"KeyGen_KeyImageSetID",
                "k"=>"KeyGen_DisplayPageID",
                "l"=>"KeyGen_DispKeyboardLeftXPos",
                "m"=>"KeyGen_DispKeyboardTopYPos",
                "n"=>"KeyGen_AlternateScreenLayout1_KeyImageSetID",
                "p"=>"KeyGen_AlternateScreenLayout1_DispKeyboardLeftXPos",
                "q"=>"KeyGen_AlternateScreenLayout1_DispKeyboardTopYPos",
                "r"=>"KeyGen_AlternateScreenLayout2_KeyImageSetID",
                "s"=>"KeyGen_AlternateScreenLayout2_DispKeyboardLeftXPos",
                "t"=>"KeyGen_AlternateScreenLayout2_DispKeyboardTopYPos",
                "u"=>"KeyGen_AlternateScreenLayout3_KeyImageSetID",
                "v"=>"KeyGen_AlternateScreenLayout3_DispKeyboardLeftXPos",
                "w"=>"KeyGen_AlternateScreenLayout3_DispKeyboardTopYPos",
        ],"KeyboardKey"=>[	
                "a"=>"KeyboardID",
                "b"=>"SwitchID",
                "c"=>"NormalMIDINoteNumber",
        ],"Pipe_SoundEngine01"=>[	
                "a"=>"PipeID",
                "b"=>"RankID",
                "c"=>"ControllingPalletSwitchID",
                "d"=>"NormalMIDINoteNumber",
                "e"=>"Pitch_Tempered_BaseTuningSchemeCode",
                "f"=>"Pitch_Tempered_RankBasePitch64ftHarmonicNum",
                "g"=>"Pitch_Tempered_BaseTuningDeviation",
                "h"=>"Pitch_Tempered_RandomTuningError_ProbPctOfDetuningByLessThanMax",
                "i"=>"Pitch_Tempered_RandomTuningError_MaxDetuningPctSemitones",
                "j"=>"Pitch_Tempered_RandomTuningError_MaxDetuningHz",
                "k"=>"Pitch_Tempered_RandomTuningError_IndexGeneratingProbabilityFn",
                "l"=>"Pitch_OriginalOrgan_SpecificationMethodCode",
                "m"=>"Pitch_OriginalOrgan_PitchHz",
                "n"=>"VirtualOutputPos_XPosMetres",
                "o"=>"VirtualOutputPos_YPosMetres",
                "p"=>"VirtualOutputPos_ZPosMetres",
                "r"=>"WindSupply_SourceWindCompartmentID",
                "s"=>"WindSupply_OutputWindCompartmentID",
        ],"Pipe_SoundEngine01_AttackSample"=>[	
                "a"=>"UniqueID",
                "b"=>"LayerID",
                "c"=>"SampleID",
                "d"=>"LoadSampleRange_StartPositionTypeCode",
                "e"=>"LoadSampleRange_StartPositionValue",
                "f"=>"LoadSampleRange_EndPositionTypeCode",
                "g"=>"LoadSampleRange_EndPositionValue",
                "h"=>"AttackSelCriteria_HighestVelocity",
                "i"=>"AttackSelCriteria_MinTimeSincePrevPipeCloseMs",
                "k"=>"LoopCrossfadeLengthInSrcSampleMs",
        ],"Pipe_SoundEngine01_Layer"=>[	
                "a"=>"LayerID",
                "b"=>"PipeID",
                "c"=>"PipeLayerNumber",
                "h"=>"AmpLvl_LevelAdjustDecibels",
        ],"Pipe_SoundEngine01_ReleaseSample"=>[	
                "a"=>"UniqueID",
                "b"=>"LayerID",
                "c"=>"SampleID",
                "d"=>"ReleaseCrossfadeLengthMs",
                "q"=>"ReleaseSelCriteria_LatestKeyReleaseTimeMs",
                "e"=>"LoadSampleRange_StartPositionTypeCode",
                "f"=>"LoadSampleRange_StartPositionValue",
                "g"=>"LoadSampleRange_EndPositionTypeCode",
                "h"=>"LoadSampleRange_EndPositionValue",
        ],"Rank"=>[	
                "a"=>"RankID",
                "b"=>"Name",
                "c"=>"SoundEngine01_Layer1Desc",
                "d"=>"SoundEngine01_Layer2Desc",
                "e"=>"SoundEngine01_Layer3Desc",
                "f"=>"SoundEngine01_Layer4Desc",
                "g"=>"SoundEngine01_Layer5Desc",
                "h"=>"SoundEngine01_Layer6Desc",
                "i"=>"SoundEngine01_Layer7Desc",
                "j"=>"SoundEngine01_Layer8Desc",
        ],"RequiredInstallationPackage"=>[	
        ],"Sample"=>[	
                "a"=>"SampleID",
                "b"=>"InstallationPackageID",
                "c"=>"SampleFilename",
                "d"=>"Pitch_SpecificationMethodCode",
                "e"=>"Pitch_RankBasePitch64ftHarmonicNum",
                "f"=>"Pitch_NormalMIDINoteNumber",
                "g"=>"Pitch_ExactSamplePitch",
        ],"Stop"=>[	
                "a"=>"StopID",
                "b"=>"Name",
                "c"=>"DivisionID",
                "d"=>"ControllingSwitchID",
        ],"StopRank"=>[	
                "a"=>"StopID",
                "b"=>"Name",
                "c"=>"RankTypeCode",
                "d"=>"RankID",
                "h"=>"MIDINoteNumOfFirstMappedDivisionInputNode",
                "i"=>"NumberOfMappedDivisionInputNodes",
                "j"=>"MIDINoteNumIncrementFromDivisionToRank",
                "p"=>"AlternateRankID",
        ],"Switch"=>[	
                "a"=>"SwitchID",
                "b"=>"Name",
                "c"=>"DefaultInputOutputSwitchAsgnCode",
                "e"=>"DefaultToEngaged",
                "g"=>"AccessibleForInput",
                "h"=>"AccessibleForOutput",
                "k"=>"Disp_ImageSetInstanceID",
                "l"=>"Disp_ImageSetIndexEngaged",
                "m"=>"Disp_ImageSetIndexDisengaged",
        ],"SwitchLinkage"=>[	
                "a"=>"SourceSwitchID",
                "b"=>"DestSwitchID",
                "c"=>"ConditionSwitchID",
                "d"=>"SourceSwitchLinkIfEngaged",
                "e"=>"ConditionSwitchLinkIfEngaged",
                "f"=>"ReevaluateIfCondSwitchChangesState",
                "g"=>"EngageLinkActionCode",
                "h"=>"DisengageLinkActionCode",
        ],"WindCompartment"=>[	
                "a"=>"WindCompartmentID",
                "b"=>"Name",
        ],"WindCompartmentLinkage"=>[	
        ],"_General"=>[	
                "b"=>"Identification_UniqueOrganID",
                "c"=>"Identification_Name",
                "d"=>"Identification_LCDDisplayShortName",
                "e"=>"OrganInfo_Location",
                "f"=>"OrganInfo_Builder",
                "g"=>"OrganInfo_BuildDate",
                "h"=>"OrganInfo_Comments",
                "j"=>"OrganInfo_InfoFilename",
                "l"=>"Display_ConsoleScreenWidthPixels",
                "m"=>"Display_ConsoleScreenHeightPixels",
                "n"=>"Display_AlternateConsoleScreenLayout1_WidthPixels",
                "o"=>"Display_AlternateConsoleScreenLayout1_HeightPixels",
                "p"=>"Display_AlternateConsoleScreenLayout2_WidthPixels",
                "q"=>"Display_AlternateConsoleScreenLayout2_HeightPixels",
                "r"=>"Display_AlternateConsoleScreenLayout3_WidthPixels",
                "s"=>"Display_AlternateConsoleScreenLayout3_HeightPixels",
                "?1"=>"AudioOut_AmplitudeLevelAdjustDecibels",
                "?2"=>"AudioEngine_BasePitchHz",
        ],"Tremulant"=>[	
                "a"=>"TremulantID",
                "b"=>"Name",
                "c"=>"ControllingSwitchID",
        ],"KeyImageSet"=>[	
                "a"=>"KeyImageSetID",
                "b"=>"Name",
                "c"=>"KeyShapeImageSetID_CF",
                "d"=>"KeyShapeImageSetID_D",
                "e"=>"KeyShapeImageSetID_EB",
                "f"=>"KeyShapeImageSetID_G",
                "g"=>"KeyShapeImageSetID_A",
                "h"=>"KeyShapeImageSetID_WholeNatural",
                "i"=>"KeyShapeImageSetID_Sharp",
                "j"=>"KeyShapeImageSetID_FirstKeyDA",
                "k"=>"KeyShapeImageSetID_FirstKeyG",
                "l"=>"KeyShapeImageSetID_LastKeyDG",
                "m"=>"KeyShapeImageSetID_LastKeyA",
                "n"=>"ImageIndexWithinImageSets_Engaged",
                "o"=>"ImageIndexWithinImageSets_Disengaged",
                "q"=>"HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural",
                "r"=>"HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF",
                "s"=>"HorizSpacingPixels_LeftOfDASharpFromLeftOfDA",
                "t"=>"HorizSpacingPixels_LeftOfGSharpFromLeftOfG",
                "u"=>"HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp",
                "v"=>"HorizSpacingPixels_LeftOfEBFromLeftOfDASharp",
                "w"=>"HorizSpacingPixels_LeftOfAFromLeftOfGSharp",
        ],"DivisionInput"=>[	
                "a"=>"DivisionID",
                "b"=>"SwitchID",
                "c"=>"NormalMIDINoteNumber",
        ],"TextStyle"=>[	
                "a"=>"TextStyleID",
                "b"=>"Name",
                "c"=>"Face_WindowsName",
                "d"=>"Face_MacName",
                "e"=>"Face_LinuxName",
                "f"=>"Font_SizePixels",
                "g"=>"Font_WeightCode",
                "h"=>"Font_Italic",
                "i"=>"Font_Underline",
                "j"=>"Colour_Red",
                "k"=>"Colour_Green",
                "l"=>"Colour_Blue",
                "m"=>"HorizontalAlignmentCode",
                "n"=>"VerticalAlignmentCode",
        ],"TextInstance"=>[	
                "a"=>"TextInstanceID",
                "b"=>"Name",
                "c"=>"TextStyleID",
                "d"=>"Text",
                "e"=>"DisplayPageID",
                "f"=>"XPosPixels",
                "g"=>"YPosPixels",
                "h"=>"BoundingBoxWidthPixelsIfWordWrap",
                "i"=>"BoundingBoxHeightPixelsIfWordWrap",
                "j"=>"AttachedToAnImageSetInstance",
                "k"=>"AttachedToImageSetInstanceID",
                "l "=>"PosRelativeToTopLeftOfImageSetInstance",
        ],"TremulantWaveform"=>[	
                "a"=>"TremulantWaveformID",
                "b"=>"Name",
                "c"=>"TremulantID",
                "d"=>"PitchAndFundamentalWaveformSampleID",
                "e"=>"ThirdHarmonicWaveformSampleID",
                "f"=>"LoopCrossfadeLengthInSrcSampleMs",
                "g"=>"PitchOutputContinuousControlID",
        ],"TremulantWaveformPipe"=>[	
                "a"=>"PipeID",
                "b"=>"TremulantWaveformID",
                "c"=>"AmplitudeModDepthAdjustDecibels",
                "d"=>"PitchModDepthAdjustPercent",
        ]];	

    /**
     * Constructor
     * @param string|null $source: HW XML File
     */
    public function __construct(string $source=NULL) {
        $this->document=new \DOMDocument();
        if ($this->document->load($source)===FALSE)
            throw new \Exception("Unable to load XML file $source");
    }

     /**
     * Read data for specified object
     * @param string $name  - Source object name
     * @param string $index - Optional indexing
     */
    public function read(string $name, string $index=NULL) {
        if (isset($this->cache[$name]))
            return $this->cache[$name];
        
        $map=[];
        foreach (self::$maps[$name] as $fr=>$to) {
            $map[$fr]=$to;
            $map[$to]=$to;
        }
        $xpath=new \DOMXPath($this->document);
        $result=[];
        $filter="[@ObjectType='$name']";

        foreach ($xpath->query("//ObjectList$filter") as $objectlist) {
            foreach ($objectlist->childNodes as $node) {
                if (!$node instanceof \DOMElement) continue;
                $data=[];
                foreach ($node->childNodes as $element) {
                    if (array_key_exists($nodename=$element->nodeName, $map))
                        $data[$map[$nodename]]=$element->nodeValue;
                }
                if (sizeof($data)>0) {
                    if (empty($index))
                        $result[]=$data;
                    else
                         $result[$data[$index]]=$data;
                }
            }
        }
        
        return $this->cache[$name]=$result;
    }
    
    public function general() {
        return $this->read("_General")[0];
    }

    public function combinations() {
        return $this->read("Combination", "CombinationID");
    }

    public function combinationElements() {
        return $this->read("CombinationElement", "CombinationElementID");
    }

    public function continuousControls() {
        return $this->read("ContinuousControl", "ControlID");
    }

    public function continuousControlImageSetStages() {
        return $this->read("ContinuousControlImageSetStage");
    }
    
    public function continuousControlLinks() {
        if (!isset($this->cache["ContinuousControlLinkageIndex"])) {
            $result=[];
            foreach($this->read("ContinuousControlLinkage") as $link) {
                if (isset($link["SourceControlID"]))
                    $result[$link["SourceControlID"]]["S"][]=$link;
                if (isset($link["DestControlID"]))
                    $result[$link["DestControlID"]]["D"][]=$link;
                if (isset($link["ConditionSwitchID"]))
                    $result[$link["ConditionSwitchID"]]["C"][]=$link;
            }
            $this->cache["ContinuousControlLinkageIndex"]=$result;
        }
        return $this->cache["ContinuousControlLinkageIndex"];
    }
    
    
    
    public function continuousControlStageSwitches() {
        return $this->read("ContinuousControlStageSwitch");
    }

    public function displayPages() {
        return $this->read("DisplayPage", "PageID");
    }

    public function divisions() {
        return $this->read("Division", "DivisionID");
    }

    public function divisionInputs() {
        return $this->read("DivisionInput");
    }

    
    public function enclosures() {
        return $this->read("Enclosure", "EnclosureID");
    }

    public function enclosurePipes() {
        return $this->read("EnclosurePipe", "PipeID");
    }

    public function imageSets() {
        return $this->read("ImageSet", "ImageSetID");
    }

    public function imageSetElements() {
        if (!isset($this->cache["ImageSetElementIndex"])) {
            $result=[];
            foreach ($this->read("ImageSetElement") as $element) {
                $ind1=$element["ImageSetID"];
                $ind2=isset($element["ImageIndexWithinSet"]) ? $element["ImageIndexWithinSet"] : 1;
                $result[$ind1][$ind2]=$element;
            }
            $this->cache["ImageSetElementIndex"]=$result;
        }
        return $this->cache["ImageSetElementIndex"];
    }

    public function imageSetInstances() {
        return $this->read("ImageSetInstance", "ImageSetInstanceID");
    }

    public function keyActions() {
        return $this->read("KeyAction");
    }

    public function keyboards() {
        return $this->read("Keyboard", "KeyboardID");
    }

    public function keyboardKeys() {
        return $this->read("KeyboardKey");
    }

    public function keyImageSets() {
        return $this->read("KeyImageSet", "KeyImageSetID");
    }

    public function pipes() {
        return $this->read("Pipe_SoundEngine01", "PipeID");
    }

    public function layers() {
        return $this->read("Pipe_SoundEngine01_Layer", "LayerID");
    }

    public function attacks() {
        return $this->read("Pipe_SoundEngine01_AttackSample", "UniqueID");
    }
    
    public function releases() {
        return $this->read("Pipe_SoundEngine01_ReleaseSample", "UniqueID");
    }
    
    public function ranks() {
        return $this->read("Rank", "RankID");
    }
    
    public function samples() {
        return $this->read("Sample", "SampleID");
    }
    
    public function stops() {
        return $this->read("Stop", "StopID");
    }
    
    public function stopRanks() {
        if (!isset($this->cache["StopRankIndex"])) {
            $result=[];
            foreach($this->read("StopRank") as $stoprank) {
                $result[$stoprank["StopID"]][$stoprank["RankID"]]=$stoprank;
            }
            $this->cache["StopRankIndex"]=$result;
        }
        return $this->cache["StopRankIndex"];
    }

    public function altStopRanks() {
        if (!isset($this->cache["AltStopRankIndex"])) {
            $result=[];
            foreach($this->read("StopRank") as $stoprank) {
                if (isset($stoprank["AlternateRankID"])
                        && !empty($stoprank["AlternateRankID"]))
                    $result[$stoprank["StopID"]][$stoprank["AlternateRankID"]]=$stoprank;
            }
            $this->cache["AltStopRankIndex"]=$result;
        }
        return $this->cache["AltStopRankIndex"];
    }
    
    public function rankStops() {
        if (!isset($this->cache["RankStopIndex"])) {
            $result=[];
            foreach($this->read("StopRank") as $stoprank) {
                $result[$stoprank["RankID"]][$stoprank["StopID"]]=$stoprank;
            }
            $this->cache["RankStopIndex"]=$result;
        }
        return $this->cache["RankStopIndex"];
    }

    public function altRankStops() {
        if (!isset($this->cache["AltRankStopIndex"])) {
            $result=[];
            foreach($this->read("StopRank") as $stoprank) {
                if (isset($stoprank["AlternateRankID"])
                        && !empty($stoprank["AlternateRankID"]))
                    $result[$stoprank["AlternateRankID"]][$stoprank["StopID"]]=$stoprank;
            }
            $this->cache["AltRankStopIndex"]=$result;
        }
        return $this->cache["AltRankStopIndex"];
    }
    
    public function switches() {
        return $this->read("Switch", "SwitchID");
    }
    
    public function switchLinks() {
        if (!isset($this->cache["SwitchLinkageIndex"])) {
            $result=[];
            foreach($this->read("SwitchLinkage") as $link) {
                if (isset($link["SourceSwitchID"]))
                    $result[$link["SourceSwitchID"]]["S"][]=$link;
                if (isset($link["DestSwitchID"]))
                    $result[$link["DestSwitchID"]]["D"][]=$link;
                if (isset($link["ConditionSwitchID"]))
                    $result[$link["ConditionSwitchID"]]["C"][]=$link;
            }
            $this->cache["SwitchLinkageIndex"]=$result;
        }
        return $this->cache["SwitchLinkageIndex"];
    }
    
    public function textStyles() {
        return $this->read("TextStyle", "TextStyleID");
    }

    public function textInstances() {
        return $this->read("TextInstance", "TextInstanceID");
    }

    public function tremulants() {
        return $this->read("Tremulant", "TremulantID");
    }

    public function tremulantWaveforms() {
        return $this->read("TremulantWaveform", "TremulantWaveformID");
    }

    public function tremulantWaveformPipes() {
        return $this->read("TremulantWaveformPipe", "PipeID");
    }
    
    public function windCompartments() {
        return $this->read("WindCompartment", "WindCompartmentID");
    }
}
