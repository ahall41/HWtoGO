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
                "g"=>"GenerateMemorySwitch",
                "h"=>"MemorySwitchID",
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
                "a"=>"Name",
                "b"=>"BinaryOperationCode",
                "c"=>"FirstSourceControl_ID",
                "d"=>"FirstSourceControl_Increment",
                "e"=>"FirstSourceControl_Coefficient",
                "f"=>"SecondSourceControl_ID",
                "g"=>"SecondSourceControl_Increment",
                "h"=>"SecondSourceControl_Coefficient",
                "i"=>"DestControl_ID",
                "j"=>"DestControl_Increment",
                "k"=>"DestControl_Coefficient",            
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
                "c"=>"InpGen_GenKeyActionInputsAutomatically",
                "d"=>"InpGen_NumberOfInputs",
                "e"=>"InpGen_MIDINoteNumberOfFirstInput",
        ],"DivisionInput"=>[	
        ],"Enclosure"=>[	
                "a"=>"EnclosureID",
                "b"=>"Name",
                "c"=>"ShutterPositionContinuousControlID",
        ],"EnclosurePipe"=>[	
                "a"=>"EnclosureID",
                "b"=>"PipeID",
                "c"=>"FiltParamWhenClsd_OverallAttnDb",
                "d"=>"FiltParamWhenClsd_MaxFreqHz",
                "e"=>"FiltParamWhenClsd_MinFreqHz",
                "f"=>"FiltParamWhenClsd_ExtraAttnAtMinDb",
                "g"=>"FiltParamWhenOpen_MaxFreqHz",
                "h"=>"FiltParamWhenOpen_MinFreqHz",
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
                "j"=>"TransparencyMaskBitmapFilename",
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
                "d"=>"DefaultInputOutputKeyboardAsgnCode",
                "e"=>"AccessibleForInput",
                "f"=>"AccessibleForOutput",
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
                "x"=>"Hint_PrimaryAssociatedDivisionID",
                "y"=>"Hint_SecondAssociatedDivisionID",
                "z"=>"Hint_ThirdAssociatedDivisionID",
               "a1"=>"Hint_MasterCouplersKeyboardAsgnCode",
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
                "p"=>"VirtualOutputPos_YPosMetres",
                "q"=>"VirtualOutputPos_ZPosMetres",
                "r"=>"WindSupply_SourceWindCompartmentID",
                "s"=>"WindSupply_OutputWindCompartmentID",
                "t"=>"WindSupply_MassFlowRateKilogramsPerSecAtReferencePressureDiff",
                "u"=>"WindSupply_ReferencePressureDifferenceInches",
                "v"=>"WindSupply_FlowConditionalOnPalletState",
                "w"=>"WindSupply_KeyVelocityResp_RespondToVelocity",
                "x"=>"WindSupply_KeyVelocityResp_InitialFlowScalingPct",
                "y"=>"WindSupply_KeyVelocityResp_TimeToReachMaxFlowMsWithMinVel",
                "z"=>"WindSupply_KeyVelocityResp_TimeToReachMaxFlowMsWithMaxVel",
               "a1"=>"WindSupply_FlowRandomisation_RandomiseFlow",
               "b1"=>"WindSupply_FlowRandomisation_PositiveAcceleratingCoeff",
               "c1"=>"WindSupply_FlowRandomisation_PositiveDampingCoeff",
               "d1"=>"WindSupply_FlowRandomisation_IndexGeneratingProbabilityFn",
               "e1"=>"WindSupply_FlowRandomisation_MaxFlowFluctuationPct",
               "f1"=>"TremulantDepthRandomisation_RandomiseTremDepth",
               "g1"=>"TremulantDepthRandomisation_PositiveAcceleratingCoeff",
               "h1"=>"TremulantDepthRandomisation_PositiveDampingCoeff",
               "i1"=>"TremulantDepthRandomisation_IndexGeneratingProbabilityFn",
               "j1"=>"TremulantDepthRandomisation_MaxDepthFluctuationPct",
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
                "d"=>"Main_Sustaining",
                "e"=>"Main_AttackSelCriteria_ContinuousControlID",
                "f"=>"Main_ReleaseSelCriteria_ContinuousControlID",
                "g"=>"AmpLvl_SoundMeanAmpLevelPreScaledForVirtOutPos",
                "h"=>"AmpLvl_LevelAdjustDecibels",
                "i"=>"AmpLvl_VelocitySensitivityMaxAttenuationDecibels",
                "j"=>"AmpLvl_InvertVelocitySensitivity",
                "k"=>"AmpLvl_PctOfReferenceAirFlowRateAtWhichPipeBeginsToSound",
                "l"=>"AmpLvl_PctOfReferenceAirFlowRateAtWhichPipeAtMaxAmplitude",
                "m"=>"AmpLvl_AirFlowDirectionCode",
                "n"=>"AmpLvl_WindModelModDepthAdjustDecibels",
                "p"=>"AmpLvl_TremulantModDepthAdjustDecibels",
                "q"=>"AmpLvl_EnclosureModDepthAdjustDecibels",
                "r"=>"AmpLvl_StereoBalanceAdjustPercent",
                "s"=>"AmpLvl_ScalingContinuousControlID",
                "t"=>"PitchLvl_DetuningPercentSemitones",
                "u"=>"PitchLvl_PercentageOfPipeReferenceAirMassFlowRate",
                "v"=>"PitchLvl_PitchDecrementPctSemitonesAtThisFlowRate",
                "w"=>"PitchLvl_PitchLockingModeCode",
                "x"=>"PitchLvl_WindModelModDepthAdjustPercent",
                "y"=>"PitchLvl_TremulantModDepthAdjustPercent",
                "z"=>"PitchLvl_ScalingContinuousControlID",
               "a1"=>"PitchLvl_IncrementingContinuousControlID",
               "b1"=>"PitchLvl_IncrementingCtsCtrlSensitivityHzPerCtrlUnit",
               "c1"=>"HarmonicShaping_ThirdAndUpperHarmonicsLevelAdjustDecibels",
               "d1"=>"HarmonicShaping_PercentageOfPipeReferenceAirMassFlowRate",
               "e1"=>"HarmonicShaping_ThirdAndUpperHarmonicsAttnAtThisFlowRateDecibls",
               "f1"=>"HarmonicShaping_WindModelModDepthAdjustDecibelsAtThirdHarmonic",
               "g1"=>"HarmonicShaping_TremulantModDepthAdjustDecibelsAtThirdHarmonic",
               "h1"=>"HarmonicShaping_IncrementingContinuousControlID",
               "i1"=>"VoicingEQ01_TransitionFrequencyKHertz",
               "j1"=>"VoicingEQ01_TransitionWidthAsPercentOfTransitionFrequency",
               "k1"=>"VoicingEQ01_HighFrequencyBoostDecibels",
               "l1"=>"EnclosureFilters_EnclosureModDepthAdjustDecibels",
               "m1"=>"ReverbTailTruncation_ModeCode",
               "n1"=>"ReverbTailTruncation_DecayLengthAsMsForMiddleCOn8FtStop",
               "o1"=>"AudioOut_OptimalChannelFormatCode",
               "p1"=>"AudioOut_OptimalSampleResolutionCode",
        ],"Pipe_SoundEngine01_ReleaseSample"=>[
                "a"=>"UniqueID",
                "b"=>"LayerID",
                "c"=>"SampleID",
                "d"=>"LoadSampleRange_StartPositionTypeCode",
                "e"=>"LoadSampleRange_StartPositionValue",
                "f"=>"LoadSampleRange_EndPositionTypeCode",
                "g"=>"LoadSampleRange_EndPositionValue",
                "h"=>"AttackSelCriteria_HighestVelocit",
                "i"=>"AttackSelCriteria_MinTimeSincePrevPipeCloseMs",
                "j"=>"AttackSelCriteria_HighestCtsCtrlValue",
                "k"=>"ScaleAmplitudeAutomatically",
                "l"=>"DontBypassAmplitudeScalingIfUserDisablesMultipleReleases",
                "m"=>"PhaseAlignAutomatically",
                "n"=>"ReleaseCrossfadeLengthMs",
                "p"=>"ReleaseSelCriteria_HighestVelocity",
                "q"=>"ReleaseSelCriteria_LatestKeyReleaseTimeMs",
                "r"=>"ReleaseSelCriteria_HighestCtsCtrlValue",
                "s"=>"ReleaseSelCriteria_PreferThisRelForAttackID",
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
                "h"=>"LicenceSerialNumRequiredForSampleFile",
        ],"Stop"=>[	
                "a"=>"StopID",
                "b"=>"Name",
                "c"=>"DivisionID",
                "d"=>"ControllingSwitchID",
                "e"=>"Hint_DefaultAssignmentCodeOfAssocInputOutputSwitch",
                "f"=>"Hint_PrimaryAssociatedRankID",
        ],"StopRank"=>[	
                "a"=>"StopID",
                "b"=>"Name",
                "c"=>"RankTypeCode",
                "d"=>"RankID",
                "e"=>"ExternalRankID",
                "f"=>"ActionTypeCode",
                "g"=>"ActionEffectCode",
                "h"=>"MIDINoteNumOfFirstMappedDivisionInputNode",
                "i"=>"NumberOfMappedDivisionInputNodes",
                "j"=>"MIDINoteNumIncrementFromDivisionToRank",
                "k"=>"PipeMIDINoteNum036_PizzOrReitPeriodMs",
                "l"=>"PipeMIDINoteNum096_PizzOrReitPeriodMs",
                "m"=>"SwitchIDToSwitchToAlternateRank",
                "n"=>"RetriggerNotesWhenSwitchingBetweenNormalAndAlternateRanks",
                "p"=>"AlternateRankID",
                "q"=>"AlternateExternalRankID",
        ],"Switch"=>[	
                "a"=>"SwitchID",
                "b"=>"Name",
                "c"=>"DefaultInputOutputSwitchAsgnCode",
                "d"=>"Latching",
                "e"=>"DefaultToEngaged",
                "f"=>"RememberStateFromLastLoad",
                "g"=>"AccessibleForInput",
                "h"=>"AccessibleForOutput",
                "i"=>"Clickable",
                "j"=>"DefaultEngageVelocity",
                "k"=>"Disp_ImageSetInstanceID",
                "l"=>"Disp_ImageSetIndexEngaged",
                "m"=>"Disp_ImageSetIndexDisengaged",
        ], "SwitchExclusiveSelectGroup"=>[
                "a"=>"GroupID",
                "b"=>"Names",
        ], "SwitchExclusiveSelectGroupElement"=>[
                "a"=>"SwitchID",
                "b"=>"GroupID",
        ],"SwitchLinkage"=>[	
                "a"=>"SourceSwitchID",
                "b"=>"DestSwitchID",
                "c"=>"ConditionSwitchID",
                "d"=>"SourceSwitchLinkIfEngaged",
                "e"=>"ConditionSwitchLinkIfEngaged",
                "f"=>"EngageLinkActionCode",
                "g"=>"DisengageLinkActionCode",
                "h"=>"ReevaluateIfCondSwitchChangesState",
        ],"WindCompartment"=>[	
                "a"=>"WindCompartmentID",
                "b"=>"Name",
                "c"=>"InfiniteVolume",
                "d"=>"StandardVolumeMetresCubed",
                "e"=>"DefaultAirPressureInches",
                "-"=>"SendPressureOutputToAContinuousControl",
                "f"=>"PressureOutputContinuousControlID",
                "g"=>"Bellows_HasBellows",
                "h"=>"Bellows_FrameBaseWidthMetres",
                "i"=>"Bellows_FrameBaseLengthMetres",
                "j"=>"Bellows_MaximumExtensionMetres",
                "k"=>"Bellows_MovementDirectionCode",
                "l"=>"Bellows_ShapeCode",
                "m"=>"Bellows_ExtensionSettingContinuousControlID",
                "n"=>"Bellows_MassOfMovingBoardActedOnByGravityKg",
                "p"=>"Bellows_MassOfMovingBoardGivingRiseToInertiaKg",
                "q"=>"Bellows_DistOfCenOfMassOfMvgBrdFromAxisIfDiagTypMetres",
                "r"=>"Bellows_PositiveDampingCoefficient",
                "s"=>"Bellows_OpeningSprings_HasOpeningSprings",
                "t"=>"Bellows_OpeningSprings_OverallForceAtReferenceBlwsExtnNewtons",
                "u"=>"Bellows_OpeningSprings_ReferenceBellowsExtensionMetres",
                "v"=>"Bellows_OpeningSprings_SpringResponseIndex",
                "w"=>"Bellows_OpeningSprings_SpringLengthWhenBellowsFullyOpenMetres",
                "x"=>"Bellows_OpeningSprings_SpringLengthAtWhichTensionedMetres",
                "y"=>"Bellows_ClosingSprings_HasClosingSprings",
                "z"=>"Bellows_ClosingSprings_OverallForceAtReferenceBlwsExtnNewtons",
               "a1"=>"Bellows_ClosingSprings_ReferenceBellowsExtensionMetres",
               "b1"=>"Bellows_ClosingSprings_SpringResponseIndex",
               "c1"=>"Bellows_ClosingSprings_SpringLengthWhenBellowsFullyClsdMetres",
               "d1"=>"Bellows_ClosingSprings_SpringLengthAtWhichTensionedMetres",
               "e1"=>"Bellows_ExtensionOutputContinuousControlID",
        ],"WindCompartmentLinkage"=>[	
                "a"=>"FirstWindCompartmentID",
                "b"=>"SecondWindCompartmentID",
                "c"=>"Name",
                "d"=>"ValveControlTypeCode",
                "e"=>"ValveControllingSwitchID",
                "f"=>"ValveOpenIfControllingSwitchEngaged",
                "g"=>"ValveControllingContinuousControlID",
                "h"=>"MassFlowRateKilogramsPerSecAtReferencePressureDiff",
                "i"=>"ReferencePressureDifferenceInches",
                "j"=>"FlowRandomisation_RandomiseFlow",
                "k"=>"FlowRandomisation_PositiveAcceleratingCoeff",
                "l"=>"FlowRandomisation_PositiveDampingCoeff",
                "m"=>"FlowRandomisation_IndexGeneratingProbabilityFn",
                "n"=>"FlowRandomisation_MaxFlowFluctuationPct",
                "p"=>"FlowRateOutputCtsCtrl_SendToAContinuousControl",
                "q"=>"FlowRateOutputCtsCtrl_ContinuousControlID",
                "r"=>"FlowRateOutputCtsCtrl_MaxMassFlowRateKilogramsPerSec",
        ],"_General"=>[	
            "a"=>"Sys_ObjectID",
            "b"=>"Identification_UniqueOrganID",
            "c"=>"Identification_Name",
            "d"=>"Identification_LCDDisplayShortName",
            "e"=>"OrganInfo_Location",
            "f"=>"OrganInfo_Builder",
            "g"=>"OrganInfo_BuildDate",
            "h"=>"OrganInfo_Comments",
            "i"=>"OrganInfo_InstallationPackageID",
            "j"=>"OrganInfo_InfoFilename",
            "k"=>"OrganInfo_MIDIDemoFilename",
            "l"=>"Display_ConsoleScreenWidthPixels",
            "m"=>"Display_ConsoleScreenHeightPixels",
            "n"=>"Display_AlternateConsoleScreenLayout1_WidthPixels",
            "p"=>"Display_AlternateConsoleScreenLayout1_HeightPixels",
            "q"=>"Display_AlternateConsoleScreenLayout2_WidthPixels",
            "r"=>"Display_AlternateConsoleScreenLayout2_HeightPixels",
            "s"=>"Display_AlternateConsoleScreenLayout3_WidthPixels",
            "t"=>"Display_AlternateConsoleScreenLayout3_HeightPixels",
            "u"=>"Control_OrganVersion",
            "v"=>"Control_MinimumHauptwerkVersion",
            "w"=>"Control_CurrentHauptwerkVersion",
            "x"=>"Control_OrganDefinitionSupplierID",
            "y"=>"Control_OrganDefinitionSupplierName",
            "z"=>"Control_FileIsCompacted_AlwaysSetThisToNIfEditingManually",
           "a1"=>"AudioOut_OptimalFormat_SampleRateCode",
           "b1"=>"AudioOut_AmplitudeLevelAdjustDecibels",
           "c1"=>"AudioEngine_WindFineIterFreqNanoseconds",
           "d1"=>"AudioEngine_EnablePlayingAtOriginalOrganPitch",
           "e1"=>"AudioEngine_EnablePlayingWithoutInterpolation",
           "f1"=>"AudioEngine_DisableUserReleaseTruncationOption",
           "g1"=>"AudioEngine_DisableUserVoicingAdjustments",
           "h1"=>"AudioEngine_OnlyAllowThisTemperamentMode",
           "i1"=>"AudioEngine_AssumePitchUnknownForPhaseAlignmt",
           "j1"=>"SpecialObjects_DefaultDisplayPageID",
           "k1"=>"SpecialObjects_MasterCaptureSwitchID",
           "l1"=>"SpecialObjects_RegistrationSeqTemplateCombinationID",
           "m1"=>"SpecialObjects_OpenAirWindCompartmentID",
            "n1"=>"AudioEngine_BasePitchHz",
        ],"Tremulant"=>[	
                "a"=>"TremulantID",
                "b"=>"Name",
                "c"=>"ControllingSwitchID",
                "d"=>"FrequencyWhenEngagedHz",
                "e"=>"FrequencyWhenDisengagedHz",
                "f"=>"StartRatePercent",
                "g"=>"StopRatePercent",
                "h"=>"FrequencyRandomisation_RandomiseFrequency",
                "i"=>"FrequencyRandomisation_PositiveAcceleratingCoeff",
                "j"=>"FrequencyRandomisation_PositiveDampingCoeff",
                "k"=>"FrequencyRandomisation_IndexGeneratingProbabilityFn",
                "l"=>"FrequencyRandomisation_MaxFreqFluctuationPct",
                "m"=>"DepthRandomisation_RandomiseDepth",
                "n"=>"DepthRandomisation_PositiveAcceleratingCoeff",
                "p"=>"DepthRandomisation_PositiveDampingCoeff",
                "q"=>"DepthRandomisation_IndexGeneratingProbabilityFn",
                "r"=>"DepthRandomisation_fMaxDepthFluctuationPct",
                "s"=>"PhaseAngleOutputContinuousControlID",
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
                "a"=>"TextStyleID", // StyleID ???
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
     * @param string|null $source=> HW XML File
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
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }
        return $this->domRead($name, $index);
    }
    
    private function objectList(string $name) {
        foreach($this->document->firstChild->childNodes as $node) {
            if ($node->nodeType!=XML_ELEMENT_NODE) {continue;}
            $objecttype=$node->attributes->getNamedItem("ObjectType");
            //print_r($objecttype);
            if ($objecttype->nodeValue==$name) {
                return $node;
            }
        }
        return NULL;
    }

    public function domRead($name, $index) {
        $map=[];
        foreach (self::$maps[$name] as $fr=>$to) {
            $map[$fr]=$to;
            $map[$to]=$to;
        }
        $result=[];
        
        $objectList=$this->objectList($name);  
        if (!$objectList) {return [];}
        
        foreach($objectList->childNodes as $row) {
            if ($row->nodeType!=XML_ELEMENT_NODE) {continue;}
            $data=[];
            foreach($row->childNodes as $cell) {
                if ($cell->nodeType!=XML_ELEMENT_NODE) {continue;}
                if (array_key_exists($nodename=$cell->nodeName, $map)) {
                    $data[$map[$nodename]]=$cell->nodeValue;
                }
            }
            if (sizeof($data)>0) {
                if (empty($index)) {
                    $result[]=$data;
                }
                else {
                    $result[$data[$index]]=$data;
                }
            }
        }
        return $this->cache[$name]=$result;
    }

    public function xpathRead(string $name, string $index=NULL) {
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
        if (!isset($this->cache["CombinationElementIndex"])) {
            $result=[];
            foreach ($this->read("CombinationElement") as $element) {
                $ind1=$element["CombinationID"];
                $ind2=$element["CombinationElementID"];
                $result[$ind1][$ind2]=$element;
            }
            $this->cache["CombinationElementIndex"]=$result;
        }
        return $this->cache["CombinationElementIndex"];
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
        if (!isset($this->cache["EnclosurePipeIndex"])) {
            $result=[];
            foreach($this->read("EnclosurePipe") as $ep) {
                $result[$ep["PipeID"]][$ep["EnclosureID"]]=$ep;
            }
            $this->cache["EnclosurePipeIndex"]=$result;
        }
        return $this->cache["EnclosurePipeIndex"];
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
