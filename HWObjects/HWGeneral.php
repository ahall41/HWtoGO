<?php

namespace HWClasses;
require_once(__DIR__ . "/HWObject.php");

/**
 * Description of HWGeneral
 *
 * @author andrew
 */
class HWGeneral extends HWObject {
    
    protected array $map=[
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
       "n1"=>"AudioEngine_BasePitchHz"
    ];
}
