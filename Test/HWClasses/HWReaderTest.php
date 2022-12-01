<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for HWReader
 ******************************************************************************/

namespace HWClasses;
require_once (__DIR__ . "/../../HWClasses/HWReader.php");

class HWReaderTest extends \PHPUnit\Framework\TestCase {
    
    const Skrzatusz="Skrzatusz";
    const Groningen="Groningen-StMartini-SemiDry.DEMO";
    const MMC="MMCOrgan3-Lodge2.Organ_Hauptwerk";
    const Utrecht="Utrecht Dom, Surround DEMO";
    const Walcker="Walcker_Miskolc v2 surround_demo";
    
    private static function HWReader($xmlfile) {
        static $cache=[];
        if (!isset($cache[$xmlfile])) 
            $cache[$xmlfile]=new HWReader(__DIR__ . "/../ODF/${xmlfile}.Organ_Hauptwerk_xml");
        return $cache[$xmlfile];
    }

    public function testGeneral() {
        $skrzatusz=self::HWReader(self::Skrzatusz)->general();
        $this->assertEquals(17, sizeof($skrzatusz));
        $this->assertEquals("693", $skrzatusz["Identification_UniqueOrganID"]);
        $this->assertEquals("Skrzatusz", $skrzatusz["Identification_Name"]);
        $this->assertEquals("Poland", $skrzatusz["OrganInfo_Location"]);
        $this->assertEquals("Wilhelm Sauer", $skrzatusz["OrganInfo_Builder"]);
        $this->assertEquals("1876", $skrzatusz["OrganInfo_BuildDate"]);
        $this->assertEquals("Info/Skrzatusz.pdf", $skrzatusz["OrganInfo_InfoFilename"]);
        $this->assertEquals("1945", $skrzatusz["Display_ConsoleScreenWidthPixels"]);
        $this->assertEquals("1160", $skrzatusz["Display_ConsoleScreenHeightPixels"]);
        $this->assertEquals("9", $skrzatusz["AudioOut_AmplitudeLevelAdjustDecibels"]);
        $this->assertFalse(isset($skrzatusz["AudioEngine_BasePitchHz"]));
        
        $groningen=self::HWReader(self::Groningen)->general();
        $this->assertEquals(18, sizeof($groningen));
        $this->assertEquals("0", $groningen["AudioOut_AmplitudeLevelAdjustDecibels"]);
        $this->assertEquals("0", $groningen["AudioEngine_BasePitchHz"]);
        
        $walcker=self::HWReader(self::Walcker)->general();
        $this->assertEquals(18, sizeof($walcker));
        $this->assertEquals("-1.6e+1", $walcker["AudioOut_AmplitudeLevelAdjustDecibels"]);
        $this->assertEquals("4.4e+2", $walcker["AudioEngine_BasePitchHz"]);
    }
    
    public function testCombination() {
        $walcker=self::HWReader(self::Walcker)->combinations();
        $this->assertEquals(127, sizeof($walcker));
        $this->assertEquals([
            'CombinationID' => '1537',
            'AllowsCapture' => '6',
            'CombinationTypeCode' => '100',
            'CanDisengageControlledSwitches' => 'N',
            'Name' => 'General cancel'
        ], $walcker[1537]);
        
        $skrzatusz=self::HWReader(self::Skrzatusz)->combinations();
        $this->assertEquals(1, sizeof($skrzatusz));
        $this->assertEquals([
            'CombinationID' => '1',
            'Name' => 'Master',
            'ActivatingSwitchID' => '',
            'CanEngageControlledSwitches' => 'Y',
            'CanDisengageControlledSwitches' => 'Y',
            'AllowsCapture' => 'Y'
        ], $skrzatusz[1]);
    }

    public function testCombinationElement() {
        $walcker=self::HWReader(self::Walcker)->combinationElements();
        $this->assertEquals(2623, sizeof($walcker));
        $this->assertEquals([
            'CombinationElementID' => '193',
            'CombinationID' => '1',
            'ControlledSwitchID' => '2001',
            'CapturedSwitchID' => '2001'
        ], $walcker[193]);
        
        $skrzatusz=self::HWReader(self::Skrzatusz)->combinationElements();
        $this->assertEquals(23, sizeof($skrzatusz));
        $this->assertEquals([
            'CombinationElementID' => '21',
            'CombinationID' => '1',
            'ControlledSwitchID' => '31',
            'CapturedSwitchID' => '31',
            'InitialStoredStateIsEngaged' => 'N',
            'InvertStoredStateWhenActivating' => 'N',
            'MemorySwitchID' => ''
        ], $skrzatusz[21]);
    }

    public function testContinuousControl() {
        $utrecht=self::HWReader(self::Utrecht)->continuousControls();
        $this->assertEquals(1322, sizeof($utrecht));
        $this->assertEquals([
            'ControlID' => '14',
            'Name' => 'Compass Selector',
            'RememberStateFromLastLoad' => 'Y',
            'DefaultValue' => '1.27e+2'
        ], $utrecht[14]);
        
        $groningen=self::HWReader(self::Groningen)->continuousControls();
        $this->assertEquals(1690, sizeof($groningen));
        $this->assertEquals([
            'ControlID' => '34',
            'Name' => 'Mixer Delay'
        ], $groningen[34]);
    }

    public function testContinuousControlImageSetStages() {
        $utrecht=self::HWReader(self::Utrecht)->ContinuousControlImageSetStages();
        $this->assertEquals(605, sizeof($utrecht));
        $this->assertEquals([
            'ImageSetID' => '6',
            'HighestContinuousControlValue' => '112',
            'ImageSetIndex' => '113'
        ], $utrecht[14]);
        
        $groningen=self::HWReader(self::Groningen)->ContinuousControlImageSetStages();
        $this->assertEquals(128, sizeof($groningen));
        $this->assertEquals([
            'ImageSetID' => '6',
            'HighestContinuousControlValue' => '93',
            'ImageSetIndex' => '94'
        ], $groningen[34]);
    }

    public function testContinuousControlLinks() {
        $utrecht=self::HWReader(self::Utrecht)->ContinuousControlLinks();
        $this->assertEquals(1488, sizeof($utrecht));
        $this->assertEquals([
            'SourceControlID' => '11',
            'DestControlID' => '200002',
            'LinkTypeCode' => '3',
            'ConditionSwitchID' => '200002',
            'Name' => 'Detune 1',
            'InertiaModelTypePositiveAcceleratingCoeff' => '2.23700575e-1',
            'InertiaModelTypePositiveDampingCoeff' => '3.47886931e-1'
        ], $utrecht[11]["S"][0]);
        $this->assertEquals([
            'SourceControlID' => '11',
            'DestControlID' => '200002',
            'LinkTypeCode' => '3',
            'ConditionSwitchID' => '200002',
            'Name' => 'Detune 1',
            'InertiaModelTypePositiveAcceleratingCoeff' => '2.23700575e-1',
            'InertiaModelTypePositiveDampingCoeff' => '3.47886931e-1'
        ], $utrecht[200002]["D"][0]);
        $this->assertEquals([
            'SourceControlID' => '200010',
            'DestControlID' => '200015',
            'ConditionSwitchID' => '11',
            'Name' => 'Memorize1',
            'ConditionSwitchLinkIfEngaged' => 'Y'
        ], $utrecht[11]["C"][0]);
    }

    public function testContinuousControlStageSwitches() {
        $utrecht=self::HWReader(self::Utrecht)->ContinuousControlStageSwitches();
        $this->assertEquals(47, sizeof($utrecht));
        $this->assertEquals([
            'ControlledSwitchID' => '102001',
            'Name' => '102000ExhaustClose',
            'ContinuousControlID' => '102000',
            'EngageWhenValueIncreasing' => 'N',
            'EngageWhenValueDecreasing' => 'N',
            'DisengageWhenValueIncreasing' => 'N'
        ], $utrecht[33]);
        
        $groningen=self::HWReader(self::Groningen)->ContinuousControlStageSwitches();
        $this->assertEquals(18, sizeof($groningen));
        $this->assertEquals([
            'ContinuousControlID' => '12',
            'ControlledSwitchID' => '108000',
            'EngageWhenValueDecreasing' => 'N',
            'DisengageWhenValueIncreasing' => 'N',
            'DisengageWhenValueDecreasing' => 'N',
            'Name' => 'Inicialize 0_Bellows08',
            'ContinuousControlValue' => '77'
        ], $groningen[10]);
    }
    
    public function testDisplayPage() {
        $utrecht=self::HWReader(self::Utrecht)->displayPages();
        $this->assertEquals(6, sizeof($utrecht));
        $this->assertEquals([
            'PageID' => '3',
            'Name' => 'Mixer'
        ], $utrecht[3]);
        
        $groningen=self::HWReader(self::Groningen)->displayPages();
        $this->assertEquals(6, sizeof($groningen));
        $this->assertEquals([
            'PageID' => '2',
            'Name' => 'Simple Jamb',
            'AlternateConsoleScreenLayout2_Include' => 'Y'
        ], $groningen[2]);
    }

    public function testDivisions() {
        $utrecht=self::HWReader(self::Utrecht)->divisions();
        $this->assertEquals(4, sizeof($utrecht));
        $this->assertEquals([
            'DivisionID' => '1',
            'Name' => 'Pedal'
        ], $utrecht[1]);

        $groningen=self::HWReader(self::Groningen)->divisions();
        $this->assertEquals(4, sizeof($groningen));
        $this->assertEquals([
            'DivisionID' => '4',
            'Name' => 'BW'
        ], $groningen[4]);
    }

    public function testDivisionInputs() {
        $utrecht=self::HWReader(self::Utrecht)->divisionInputs();
        $this->assertEquals(198, sizeof($utrecht));
        $this->assertEquals([
            'DivisionID' => '2',
            'SwitchID' => '32090',
            'NormalMIDINoteNumber' => '90'
        ], $utrecht[1]);

        $groningen=self::HWReader(self::Groningen)->divisionInputs();
        $this->assertEquals(192, sizeof($groningen));
        $this->assertEquals([
            'DivisionID' => '2',
            'SwitchID' => '32037',
            'NormalMIDINoteNumber' => '37'
        ], $groningen[190]);
    }
    
    public function testEnclosures() {
        $utrecht=self::HWReader(self::Utrecht)->enclosures();
        $this->assertEquals(1, sizeof($utrecht));
        $this->assertEquals([
            'EnclosureID' => '998',
            'Name' => 'Swell',
            'ShutterPositionContinuousControlID' => '998'
        ], $utrecht[998]);

        $groningen=self::HWReader(self::Groningen)->enclosures();
        $this->assertEquals(0, sizeof($groningen));
    }
    
    public function testEnclosurePipes() {
        $utrecht=self::HWReader(self::Utrecht)->enclosurePipes();
        $this->assertEquals(508, sizeof($utrecht));
        $this->assertEquals([
            'PipeID' => '40480',
            'EnclosureID' => '998'
        ], $utrecht[40480]);
        $this->assertEquals([
            'PipeID' => '36477',
            'EnclosureID' => '998'
        ], $utrecht[36477]);

        $groningen=self::HWReader(self::Groningen)->enclosurePipes();
        $this->assertEquals(0, sizeof($groningen));
    }
    
    public function testImageSets() {
        $lodge2=self::HWReader(self::MMC)->imageSets();
        $this->assertEquals(56, sizeof($lodge2));
        $this->assertEquals([
            'ImageSetID' => '56',
            'Name' => 'CustKS_Ped06OldSm_Sharp',
            'InstallationPackageID' => '1',
            'ClickableAreaRightRelativeXPosPixels' => '12',
            'ClickableAreaBottomRelativeYPosPixels' => '70',
        ], $lodge2[56]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->imageSets();
        $this->assertEquals(37, sizeof($skrzatusz));
        $this->assertEquals([
            'ImageSetID' => '111',
            'Name' => 'Man W',
            'InstallationPackageID' => '693',
            'ClickableAreaRightRelativeXPosPixels' => '13',
            'ClickableAreaBottomRelativeYPosPixels' => '48',
            'ImageWidthPixels' => '18',
            'ImageHeightPixels' => '48',
            'ClickableAreaLeftRelativeXPosPixels' => '3',
            'ClickableAreaTopRelativeYPosPixels' => '0',
        ], $skrzatusz[111]);
    }
    
    public function testImageSetElements() {
        $lodge2=self::HWReader(self::MMC)->imageSetElements();
        $this->assertEquals(56, sizeof($lodge2));
        $this->assertEquals(81, sizeof($lodge2[24]));
        $this->assertEquals([
            'ImageSetID' => '24',
            'ImageIndexWithinSet' => '81',
            'Name' => 'Dial needle 20.00',
            'BitmapFilename' => 'HauptwerkStandardImages/WindDialNeedle20.00.bmp',
        ], $lodge2[24][81]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->imageSetElements();
        $this->assertEquals(37, sizeof($skrzatusz));
        $this->assertEquals(2, sizeof($skrzatusz[24]));
        $this->assertEquals([
            'ImageSetID' => '24',
            'ImageIndexWithinSet' => '1',
            'Name' => 'Nachtigall',
            'BitmapFilename' => 'Images/cons_e_rachtigall.png',
        ], $skrzatusz[24][1]);
        
        $utrecht=self::HWReader(self::Utrecht)->imageSetElements();
        $this->assertEquals(376, sizeof($utrecht));
        $this->assertEquals(2, sizeof($utrecht[12054]));
        $this->assertEquals([
            'ImageSetID' => '12054',
            'Name' => 'DrStop2offPd Mixtuur',
            'BitmapFilename' => 'stops2/pd_mixtuur_in.bmp'
        ], $utrecht[12054][1]);
        $this->assertEquals([
            'ImageSetID' => '12054',
            'ImageIndexWithinSet' => '2',
            'Name' => 'DrStop2Out Pd Mixtuur',
            'BitmapFilename' => 'stops2/pd_mixtuur_out.bmp'
        ], $utrecht[12054][2]);
        
    }

    public function testImageSetInstances() {
        $utrecht=self::HWReader(self::Utrecht)->imageSetInstances();
        $this->assertEquals(480, sizeof($utrecht));
        $this->assertEquals([
            'ImageSetID' => '12001',
            'ImageSetInstanceID' => '12001',
            'DisplayPageID' => '4',
            'Name' => 'Koppeling HW-Pd (SplitJamb)',
            'ScreenLayerNumber' => '6',
            'LeftXPosPixels' => '1029',
            'TopYPosPixels' => '870',
            'AlternateScreenLayout2_ImageSetID' => '14001',
            'AlternateScreenLayout2_LeftXPosPixels' => '665',
            'AlternateScreenLayout2_TopYPosPixels' => '1640'
        ], $utrecht[12001]);
        $this->assertEquals([
            'ImageSetInstanceID' => '4061',
            'ImageSetID' => '1125',
            'DisplayPageID' => '1',
            'Name' => 'Klavesa 061-c#Man.2. lowest',
            'ScreenLayerNumber' => '10',
            'LeftXPosPixels' => '715',
            'TopYPosPixels' => '494'
        ], $utrecht[4061]);

        
        $skrzatusz=self::HWReader(self::Skrzatusz)->imageSetInstances();
        $this->assertEquals(30, sizeof($skrzatusz));
        $this->assertEquals([
            'ImageSetID' => '24',
            'Name' => 'Nachtigall',
            'ImageSetInstanceID' => '24',
            'DefaultImageIndexWithinSet' => '2',
            'DisplayPageID' => '1',
            'ScreenLayerNumber' => '4',
            'LeftXPosPixels' => '1687',
            'TopYPosPixels' => '310',
            'RightXPosPixelsIfTiling' => '0',
            'BottomYPosPixelsIfTiling' => '0',
            'AlternateScreenLayout1_ImageSetID' => '',
            'AlternateScreenLayout1_LeftXPosPixels' => '',
            'AlternateScreenLayout1_TopYPosPixels' => '',
            'AlternateScreenLayout1_RightXPosPixelsIfTiling' => '',
            'AlternateScreenLayout1_BottomYPosPixelsIfTiling' => '',
            'AlternateScreenLayout2_ImageSetID' => '',
            'AlternateScreenLayout2_LeftXPosPixels' => '',
            'AlternateScreenLayout2_TopYPosPixels' => '',
            'AlternateScreenLayout2_RightXPosPixelsIfTiling' => '',
            'AlternateScreenLayout2_BottomYPosPixelsIfTiling' => '',
            'AlternateScreenLayout3_ImageSetID' => '',
            'AlternateScreenLayout3_LeftXPosPixels' => '',
            'AlternateScreenLayout3_TopYPosPixels' => '',
            'AlternateScreenLayout3_RightXPosPixelsIfTiling' => '',
            'AlternateScreenLayout3_BottomYPosPixelsIfTiling' => ''
        ], $skrzatusz[24]);
    }

    public function testKeyActions() {
        $utrecht=self::HWReader(self::Utrecht)->keyActions();
        $this->assertEquals(4, sizeof($utrecht));
        $this->assertEquals([
            'SourceKeyboardID' => '2',
            'ConditionSwitchLinkIfEngaged' => 'Y',
            'ConditionSwitchID' => '19050',
            'ActionEffectCode' => '1',
            'Name' => '3 To 2,01',
            'NumberOfKeys' => '56',
            'DestKeyboardID' => '3'
        ], $utrecht[0]);;

        
        $groningen=self::HWReader(self::Groningen)->keyActions();
        $this->assertEquals(2, sizeof($groningen));
        $this->assertEquals([
                'SourceKeyboardID' => '2',
                'DestKeyboardID' => '3',
                'Name' => '3 To 2,01',
                'ConditionSwitchID' => '19028',
                'ConditionSwitchLinkIfEngaged' => 'Y',
                'ActionEffectCode' => '1',
                'NumberOfKeys' => '54'
        ], $groningen[0]);
    }

    public function testKeyboards() {
        $lodge2=self::HWReader(self::MMC)->keyboards();
        $this->assertEquals(9, sizeof($lodge2));
        $this->assertEquals([
            'KeyboardID' => '65',
            'KeyGen_KeyImageSetID' => '4',
            'KeyGen_DisplayPageID' => '1',
            'Name' => 'CustPg1_InputKbd_DivCode1',
            'ShortName' => 'I_1',
            'KeyGen_NumberOfKeys' => '32',
            'KeyGen_MIDINoteNumberOfFirstKey' => '36',
            'KeyGen_DispKeyboardLeftXPos' => '395',
            'KeyGen_DispKeyboardTopYPos' => '530'            
        ], $lodge2[65]);;

        $walcker=self::HWReader(self::Walcker)->keyboards();
        $this->assertEquals(13, sizeof($walcker));
        $this->assertEquals([
            'KeyboardID' => '129',
            'KeyGen_KeyImageSetID' => '2',
            'KeyGen_DisplayPageID' => '1',
            'Name' => 'CustPg1_OutputKbd_DivCode1',
            'ShortName' => 'O_1',
            'KeyGen_NumberOfKeys' => '32',
            'KeyGen_MIDINoteNumberOfFirstKey' => '36',
            'KeyGen_DispKeyboardLeftXPos' => '337',
            'KeyGen_DispKeyboardTopYPos' => '585'           
        ], $walcker[129]);
        
        $groningen=self::HWReader(self::Groningen)->keyboards();
        $this->assertEquals(4, sizeof($groningen));
        $this->assertEquals([
            'KeyboardID' => '4',
            'Name' => '3. manual',
            'ShortName' => '3.man',
            'KeyGen_GenerateKeysAutomatically' => 'N',
            'Hint_PrimaryAssociatedDivisionID' => '4',
        ], $groningen[4]);
    }
    
    public function testKeyImageSets() {
        $lodge2=self::HWReader(self::MMC)->keyImageSets();
        $this->assertEquals(4, sizeof($lodge2));
        $this->assertEquals([
            'KeyImageSetID' => '1',
            'KeyShapeImageSetID_CF' => '5',
            'KeyShapeImageSetID_D' => '6',
            'KeyShapeImageSetID_EB' => '7',
            'KeyShapeImageSetID_G' => '8',
            'KeyShapeImageSetID_A' => '9',
            'KeyShapeImageSetID_WholeNatural' => '10',
            'KeyShapeImageSetID_Sharp' => '11',
            'KeyShapeImageSetID_FirstKeyDA' => '12',
            'KeyShapeImageSetID_FirstKeyG' => '13',
            'KeyShapeImageSetID_LastKeyDG' => '14',
            'KeyShapeImageSetID_LastKeyA' => '15',
            'Name' => 'Manual keys',
            'HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural' => '12',
            'HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF' => '8',
            'HorizSpacingPixels_LeftOfDASharpFromLeftOfDA' => '10',
            'HorizSpacingPixels_LeftOfGSharpFromLeftOfG' => '9',
            'HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp' => '4',
            'HorizSpacingPixels_LeftOfEBFromLeftOfDASharp' => '2',
            'HorizSpacingPixels_LeftOfAFromLeftOfGSharp' => '3'
        ], $lodge2[1]);

        $walcker=self::HWReader(self::Walcker)->keyImageSets();
        $this->assertEquals(2, sizeof($walcker));
        $this->assertEquals([
            'KeyImageSetID' => '1',
            'KeyShapeImageSetID_CF' => '14',
            'KeyShapeImageSetID_D' => '15',
            'KeyShapeImageSetID_EB' => '16',
            'KeyShapeImageSetID_G' => '17',
            'KeyShapeImageSetID_A' => '18',
            'KeyShapeImageSetID_WholeNatural' => '19',
            'KeyShapeImageSetID_Sharp' => '24',
            'KeyShapeImageSetID_FirstKeyDA' => '20',
            'KeyShapeImageSetID_FirstKeyG' => '21',
            'KeyShapeImageSetID_LastKeyDG' => '22',
            'KeyShapeImageSetID_LastKeyA' => '23',
            'Name' => 'CustKS_OldManual',
            'HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural' => '9',
            'HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF' => '6',
            'HorizSpacingPixels_LeftOfDASharpFromLeftOfDA' => '8',
            'HorizSpacingPixels_LeftOfGSharpFromLeftOfG' => '7',
            'HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp' => '3',
            'HorizSpacingPixels_LeftOfEBFromLeftOfDASharp' => '2',
            'HorizSpacingPixels_LeftOfAFromLeftOfGSharp' => '2'
        ], $walcker[1]);
        
        $skrzatusz=self::HWReader(self::Skrzatusz)->keyImageSets();
        $this->assertEquals(2, sizeof($skrzatusz));
        $this->assertEquals([
            'KeyImageSetID' => '2',
            'Name' => 'Pedals',
            'KeyShapeImageSetID_CF' => '109',
            'KeyShapeImageSetID_D' => '109',
            'KeyShapeImageSetID_EB' => '109',
            'KeyShapeImageSetID_G' => '109',
            'KeyShapeImageSetID_A' => '109',
            'KeyShapeImageSetID_WholeNatural' => '109',
            'KeyShapeImageSetID_Sharp' => '110',
            'KeyShapeImageSetID_FirstKeyDA' => '109',
            'KeyShapeImageSetID_FirstKeyG' => '109',
            'KeyShapeImageSetID_LastKeyDG' => '109',
            'KeyShapeImageSetID_LastKeyA' => '109',
            'ImageIndexWithinImageSets_Engaged' => '1',
            'ImageIndexWithinImageSets_Disengaged' => '2',
            'HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural' => '34',
            'HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF' => '19',
            'HorizSpacingPixels_LeftOfDASharpFromLeftOfDA' => '19',
            'HorizSpacingPixels_LeftOfGSharpFromLeftOfG' => '19',
            'HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp' => '19',
            'HorizSpacingPixels_LeftOfEBFromLeftOfDASharp' => '19',
            'HorizSpacingPixels_LeftOfAFromLeftOfGSharp' => '19'
        ], $skrzatusz[2]);
    }

    public function testKeyboardKeys() {
        $walcker=self::HWReader(self::Walcker)->keyboardKeys();
        $this->assertEquals(0, sizeof($walcker));

        $utrecht=self::HWReader(self::Utrecht)->keyboardKeys();
        $this->assertEquals(198, sizeof($utrecht));
        $this->assertEquals([
            'KeyboardID' => '1',
            'SwitchID' => '8065',
            'NormalMIDINoteNumber' => '65'            
        ], $utrecht[0]);
        
        $groningen=self::HWReader(self::Groningen)->keyboardKeys();
        $this->assertEquals(192, sizeof($groningen));
        $this->assertEquals([
            'KeyboardID' => '1',
            'SwitchID' => '8044',
            'NormalMIDINoteNumber' => '44'
        ], $groningen[21]);
    }

    public function testPipes() {
        $walcker=self::HWReader(self::Walcker)->pipes();
        $this->assertEquals(5765, sizeof($walcker));
        $this->assertEquals([
            'PipeID' => '548',
            'RankID' => '1',
            'WindSupply_SourceWindCompartmentID' => '4',
            'WindSupply_OutputWindCompartmentID' => '1',
            'Pitch_Tempered_RandomTuningError_ProbPctOfDetuningByLessThanMax' => '9.7e+1',
            'Pitch_Tempered_RandomTuningError_MaxDetuningPctSemitones' => '1.1999e+3',
            'Pitch_Tempered_RandomTuningError_MaxDetuningHz' => '2.5e-1',
            'Pitch_Tempered_RandomTuningError_IndexGeneratingProbabilityFn' => '2e+1',
            'VirtualOutputPos_XPosMetres' => '1.12',
            'VirtualOutputPos_ZPosMetres' => '2.6',
            'NormalMIDINoteNumber' => '36',
            'Pitch_Tempered_RankBasePitch64ftHarmonicNum' => '4'            
        ], $walcker[548]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->pipes();
        $this->assertEquals(1187, sizeof($skrzatusz));
        $this->assertEquals([
            'PipeID' => '10954',
            'RankID' => '27',
            'ControllingPalletSwitchID' => '754',
            'NormalMIDINoteNumber' => '89',
            'Pitch_Tempered_BaseTuningSchemeCode' => '4',
            'Pitch_Tempered_RankBasePitch64ftHarmonicNum' => '0',
            'Pitch_Tempered_BaseTuningDeviation' => '100',
            'Pitch_Tempered_RandomTuningError_ProbPctOfDetuningByLessThanMax' => '',
            'Pitch_Tempered_RandomTuningError_MaxDetuningPctSemitones' => '',
            'Pitch_Tempered_RandomTuningError_MaxDetuningHz' => '',
            'Pitch_Tempered_RandomTuningError_IndexGeneratingProbabilityFn' => '',
            'Pitch_OriginalOrgan_SpecificationMethodCode' => '1',
            'Pitch_OriginalOrgan_PitchHz' => '100',
            'VirtualOutputPos_XPosMetres' => '1.44',
            'VirtualOutputPos_YPosMetres' => '3',
            'VirtualOutputPos_ZPosMetres' => '2',
            'WindSupply_SourceWindCompartmentID' => '2',
            'WindSupply_OutputWindCompartmentID' => '1'            
        ], $skrzatusz[10954]);
    }
 
    public function testLayers() {
        $walcker=self::HWReader(self::Walcker)->layers();
        $this->assertEquals(5765, sizeof($walcker));
        $this->assertEquals([
            'LayerID' => '548',
            'PipeID' => '548',
            'AmpLvl_LevelAdjustDecibels' => '1.6e+1'
        ], $walcker[548]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->layers();
        $this->assertEquals(1187, sizeof($skrzatusz));
        $this->assertEquals([
            'LayerID' => '10954',
            'PipeID' => '10954',
            'PipeLayerNumber' => '1',
            'AmpLvl_LevelAdjustDecibels' => '-12'
        ], $skrzatusz[10954]);
    }
    
    public function testAttacks() {
        $walcker=self::HWReader(self::Walcker)->attacks();
        $this->assertEquals(5765, sizeof($walcker));
        $this->assertEquals([
            'UniqueID' => '548',
            'LayerID' => '548',
            'SampleID' => '1',
            'LoadSampleRange_StartPositionTypeCode' => '4',
            'LoadSampleRange_EndPositionTypeCode' => '7',
        ], $walcker[548]);

        $utrecht=self::HWReader(self::Utrecht)->attacks();
        $this->assertEquals(3171, sizeof($utrecht));
        $this->assertEquals([
            'UniqueID' => '324860',
            'LayerID' => '324860',
            'SampleID' => '324860',
            'LoadSampleRange_StartPositionTypeCode' => '4',
            'LoadSampleRange_EndPositionTypeCode' => '7',
            'LoopCrossfadeLengthInSrcSampleMs' => '198'
        ], $utrecht[324860]);
        
        $skrzatusz=self::HWReader(self::Skrzatusz)->attacks();
        $this->assertEquals(1187, sizeof($skrzatusz));
        $this->assertEquals([
            'UniqueID' => '10954',
            'LayerID' => '10954',
            'SampleID' => '10954',
            'LoadSampleRange_StartPositionTypeCode' => '4',
            'LoadSampleRange_StartPositionValue' => '',
            'LoadSampleRange_EndPositionTypeCode' => '6',
            'LoadSampleRange_EndPositionValue' => '',
            'AttackSelCriteria_HighestVelocity' => '127',
            'AttackSelCriteria_MinTimeSincePrevPipeCloseMs' => '0',
            'LoopCrossfadeLengthInSrcSampleMs' => '5'
        ], $skrzatusz[10954]);
    }
    
    public function testReleases() {
        $walcker=self::HWReader(self::Walcker)->releases();
        $this->assertEquals(15201, sizeof($walcker));
        $this->assertEquals([
            'UniqueID' => '524964',
            'LayerID' => '548',
            'SampleID' => '3',
            'ReleaseCrossfadeLengthMs' => '4',
            'ReleaseSelCriteria_LatestKeyReleaseTimeMs' => '150'
        ], $walcker[524964]);

        $utrecht=self::HWReader(self::Utrecht)->releases();
        $this->assertEquals(5581, sizeof($utrecht));
        $this->assertEquals([
            'UniqueID' => '210425',
            'LayerID' => '210420',
            'SampleID' => '210425',
            'ReleaseCrossfadeLengthMs' => '4',
            'ReleaseSelCriteria_LatestKeyReleaseTimeMs' => '187'
        ], $utrecht[210425]);
        
        $skrzatusz=self::HWReader(self::Skrzatusz)->releases();
        $this->assertEquals(1738, sizeof($skrzatusz));
        $this->assertEquals([
            'UniqueID' => '10003',
            'LayerID' => '10003',
            'SampleID' => '10003',
            'LoadSampleRange_StartPositionTypeCode' => '1',
            'LoadSampleRange_EndPositionTypeCode' => '6',
            'ReleaseCrossfadeLengthMs' => '45',
            'ReleaseSelCriteria_LatestKeyReleaseTimeMs' => '99999',
            'LoadSampleRange_StartPositionValue' => '1',
            'LoadSampleRange_EndPositionValue' => '0'
        ], $skrzatusz[10003]);
    }

    public function testRanks() {
        $lodge2=self::HWReader(self::MMC)->ranks();
        $this->assertEquals(15, sizeof($lodge2));
        $this->assertEquals([
            'RankID' => '1',
            'Name' => '01 Pedal: Bourdon 16',
            'SoundEngine01_Layer1Desc' => 'Main'            
        ], $lodge2[1]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->ranks();
        $this->assertEquals(27, sizeof($skrzatusz));
        $this->assertEquals([
            'RankID' => '27',
            'Name' => 'II Key Action Release',
            'SoundEngine01_Layer1Desc' => 'Main',
            'SoundEngine01_Layer2Desc' => '',
            'SoundEngine01_Layer3Desc' => '',
            'SoundEngine01_Layer4Desc' => '',
            'SoundEngine01_Layer5Desc' => '',
            'SoundEngine01_Layer6Desc' => '',
            'SoundEngine01_Layer7Desc' => '',
            'SoundEngine01_Layer8Desc' => ''
        ], $skrzatusz[27]);
    }

    public function testSamples() {
        $lodge2=self::HWReader(self::MMC)->samples();
        $this->assertEquals(863, sizeof($lodge2));
        $this->assertEquals([
            'SampleID' => '863',
            'InstallationPackageID' => '503',
            'SampleFilename' => 'EchChimes/079-G.wav'
        ], $lodge2[863]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->samples();
        $this->assertEquals(2056, sizeof($skrzatusz));
        $this->assertEquals([
            'SampleID' => '10003',
            'InstallationPackageID' => '693',
            'SampleFilename' => 'Noises/Nachtigall.wav',
            'Pitch_SpecificationMethodCode' => '4',
            'Pitch_RankBasePitch64ftHarmonicNum' => '0',
            'Pitch_NormalMIDINoteNumber' => '',
            'Pitch_ExactSamplePitch' => '100'
        ], $skrzatusz[10003]);
    }
    
    public function testStops() {
        $lodge2=self::HWReader(self::MMC)->stops();
        $this->assertEquals(15, sizeof($lodge2));
        $this->assertEquals([
            'StopID' => '2206',
            'DivisionID' => '3',
            'ControllingSwitchID' => '10115',
            'Name' => '023 Stop: Sw: Chimes'
        ], $lodge2[2206]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->stops();
        $this->assertEquals(19, sizeof($skrzatusz));
        $this->assertEquals([
            'StopID' => '1',
            'Name' => 'P Violon 16',
            'DivisionID' => '1',
            'ControllingSwitchID' => '11'
        ], $skrzatusz[1]);
    }

    public function testStopRanks() {
        $utrecht=self::HWReader(self::Utrecht)->stopRanks();
        $this->assertEquals(12, sizeof($utrecht));
        $this->assertEquals([
            'StopID' => '60',
            'RankID' => '604',
            'Name' => 'RW Quint 3',
            'AlternateRankID' => '608',
            'NumberOfMappedDivisionInputNodes' => '56'
        ], $utrecht[60][604]);

        $groningen=self::HWReader(self::Groningen)->stopRanks();
        $this->assertEquals(15, sizeof($groningen));
        $this->assertEquals([
            'StopID' => '55',
            'Name' => '3. octaav 4 550',
            'RankID' => '550',
            'AlternateRankID' => '559',
            'NumberOfMappedDivisionInputNodes' => '54'
        ], $groningen[55][550]);
    }

    public function testAltStopRanks() {
        $utrecht=self::HWReader(self::Utrecht)->altStopRanks();
        $this->assertEquals(7, sizeof($utrecht));
        $this->assertEquals([
            'StopID' => '60',
            'RankID' => '604',
            'Name' => 'RW Quint 3',
            'AlternateRankID' => '608',
            'NumberOfMappedDivisionInputNodes' => '56'
        ], $utrecht[60][608]);

        $groningen=self::HWReader(self::Groningen)->altStopRanks();
        $this->assertEquals(9, sizeof($groningen));
        $this->assertEquals([
            'StopID' => '55',
            'Name' => '3. octaav 4 550',
            'RankID' => '550',
            'AlternateRankID' => '559',
            'NumberOfMappedDivisionInputNodes' => '54'
        ], $groningen[55][559]);
    }

    public function testRankStops() {
        $utrecht=self::HWReader(self::Utrecht)->rankStops();
        $this->assertEquals(24, sizeof($utrecht));
        $this->assertEquals([
            'StopID' => '60',
            'RankID' => '604',
            'Name' => 'RW Quint 3',
            'AlternateRankID' => '608',
            'NumberOfMappedDivisionInputNodes' => '56'
        ], $utrecht[604][60]);

        $groningen=self::HWReader(self::Groningen)->rankStops();
        $this->assertEquals(15, sizeof($groningen));
        $this->assertEquals([
            'StopID' => '55',
            'Name' => '3. octaav 4 550',
            'RankID' => '550',
            'AlternateRankID' => '559',
            'NumberOfMappedDivisionInputNodes' => '54'
        ], $groningen[550][55]);
    }

    public function testAltRankStops() {
        $utrecht=self::HWReader(self::Utrecht)->altRankStops();
        $this->assertEquals(14, sizeof($utrecht));
        $this->assertEquals([
            'StopID' => '60',
            'RankID' => '604',
            'Name' => 'RW Quint 3',
            'AlternateRankID' => '608',
            'NumberOfMappedDivisionInputNodes' => '56'
        ], $utrecht[608][60]);

        $groningen=self::HWReader(self::Groningen)->altRankStops();
        $this->assertEquals(9, sizeof($groningen));
        $this->assertEquals([
            'StopID' => '55',
            'Name' => '3. octaav 4 550',
            'RankID' => '550',
            'AlternateRankID' => '559',
            'NumberOfMappedDivisionInputNodes' => '54'
        ], $groningen[559][55]);
    }
    
    public function testSwitches() {
        $utrecht=self::HWReader(self::Utrecht)->switches();
        $this->assertEquals(1285, sizeof($utrecht));
        $this->assertEquals([
            'SwitchID' => '11014',
            'Disp_ImageSetInstanceID' => '11014',
            'Name' => ' HW Gemshoorn 4 (Simple Screen)',
            'Disp_ImageSetIndexEngaged' => '2',
            'Disp_ImageSetIndexDisengaged' => '1'
        ], $utrecht[11014]);

        $groningen=self::HWReader(self::Groningen)->switches();
        $this->assertEquals(1340, sizeof($groningen));
        $this->assertEquals([
            'SwitchID' => '12046',
            'Name' => ' P. roerquint 6 (Split Screen)',
            'AccessibleForInput' => 'N',
            'AccessibleForOutput' => 'N',
            'Disp_ImageSetInstanceID' => '12046',
            'Disp_ImageSetIndexEngaged' => '2',
            'Disp_ImageSetIndexDisengaged' => '1'            
        ], $groningen[12046]);
    }

    public function testSwitchLinks() {
        $utrecht=self::HWReader(self::Utrecht)->switchLinks();
        $this->assertEquals(1252, sizeof($utrecht));
        $this->assertEquals([
            ['SourceSwitchID' => '13061',
             'DestSwitchID' => '19061',
             'ConditionSwitchID' => '16061']
            ], $utrecht[16061]["C"]);
        $this->assertEquals([
            ['SourceSwitchID' => '11061',
              'DestSwitchID' => '13061'],
            ['SourceSwitchID' => '12061',
              'DestSwitchID' => '13061']
            ], $utrecht[13061]["D"]);
        $this->assertEquals([
            ['SourceSwitchID' => '12061',
             'DestSwitchID' => '13061']
        ], $utrecht[12061]["S"]);

        $groningen=self::HWReader(self::Groningen)->switchLinks();
        $this->assertEquals(1266, sizeof($groningen));
        $this->assertEquals([
            'SourceSwitchID' => '5089',
            'DestSwitchID' => '994890',
            'ReevaluateIfCondSwitchChangesState' => '7',
            'EngageLinkActionCode' => '4',
        ], $groningen[5089]["S"][1]);
        $this->assertEquals([
            'SourceSwitchID' => '5087',
            'DestSwitchID' => '994870',
            'ReevaluateIfCondSwitchChangesState' => '7',
            'EngageLinkActionCode' => '4'
        ], $groningen[994870]["D"][0]);
        $this->assertEquals([
                'SourceSwitchID' => '4089',
                'DestSwitchID' => '33089',
                'ConditionSwitchID' => '12998',
                'ConditionSwitchLinkIfEngaged' => 'Y'
        ], $groningen[12998]["C"][5]);
    }

    public function testTextInstances() {
        $walcker=self::HWReader(self::Walcker)->textInstances();
        $this->assertEquals(105, sizeof($walcker));
        $this->assertEquals([
            'TextInstanceID' => '60',
            'DisplayPageID' => '1',
            'AttachedToImageSetInstanceID' => '65',
            'TextStyleID' => '2',
            'Name' => 'CustPg1_Stop 2125',
            'Text' => "Mixtur\n1 1/3'\n6f",
            'XPosPixels' => '2',
            'YPosPixels' => '12',
            'BoundingBoxWidthPixelsIfWordWrap' => '53',
            'BoundingBoxHeightPixelsIfWordWrap' => '63',          
        ], $walcker[60]);
    }

    public function testTremulants() {
        $utrecht=self::HWReader(self::Utrecht)->tremulants();
        $this->assertEquals(2, sizeof($utrecht));
        $this->assertEquals([
           'TremulantID' => '65',
            'Name' => 'RW Tremulant',
            'ControllingSwitchID' => '13065'
        ], $utrecht[65]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->tremulants();
        $this->assertEquals(1, sizeof($skrzatusz));
        $this->assertEquals([
            'TremulantID' => '1',
            'Name' => 'Tremulant 2Man',
            'ControllingSwitchID' => '33'
        ], $skrzatusz[1]);
    }

    public function testTremulantWaveforms() {
        $utrecht=self::HWReader(self::Utrecht)->tremulantWaveforms();
        print_r($utrecht);
        $this->assertEquals(138, sizeof($utrecht));
        $this->assertEquals([
            'TremulantWaveformID' => '30055',
            'TremulantID' => '65',
            'PitchAndFundamentalWaveformSampleID' => '300554',
            'ThirdHarmonicWaveformSampleID' => '300559',
            'LoopCrossfadeLengthInSrcSampleMs' => '4'
        ], $utrecht[30055]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->tremulantWaveforms();
        $this->assertEquals(1, sizeof($skrzatusz));
        $this->assertEquals([
            'TremulantWaveformID' => '1',
            'Name' => 'Ged8 1',
            'TremulantID' => '1',
            'PitchAndFundamentalWaveformSampleID' => '10101',
            'ThirdHarmonicWaveformSampleID' => '10111',
            'LoopCrossfadeLengthInSrcSampleMs' => '50',
            'PitchOutputContinuousControlID' => ''
        ], $skrzatusz[1]);
    }
    
    public function testTremulantWaveformPipes() {
        $utrecht=self::HWReader(self::Utrecht)->tremulantWaveformPipes();
        $this->assertEquals(702, sizeof($utrecht));
        $this->assertEquals([
            'PipeID' => '60491',
            'TremulantWaveformID' => '60479',
            'AmplitudeModDepthAdjustDecibels' => '8.129604e-1',
            'PitchModDepthAdjustPercent' => '8.531615638733e+1'
        ], $utrecht[60491]);

        $skrzatusz=self::HWReader(self::Skrzatusz)->tremulantWaveformPipes();
        $this->assertEquals(270, sizeof($skrzatusz));
        $this->assertEquals([
            'PipeID' => '1501',
            'TremulantWaveformID' => '1',
            'AmplitudeModDepthAdjustDecibels' => '-1',
            'PitchModDepthAdjustPercent' => '50'
        ], $skrzatusz[1501]);
    }

    public function testWindCompartments() {
        $utrecht=self::HWReader(self::Utrecht)->windCompartments();
        $this->assertEquals(65, sizeof($utrecht));
        $this->assertEquals([
            'WindCompartmentID' => '300',
            'Name' => 'Left Hoofdwerk'
        ], $utrecht[300]);

        $groningen=self::HWReader(self::Groningen)->windCompartments();
        $this->assertEquals(27, sizeof($groningen));
        $this->assertEquals([
            'WindCompartmentID' => '10062',
            'Name' => '10062'
        ], $groningen[10062]);
    }
}