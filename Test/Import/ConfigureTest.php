<?php

/*******************************************************************************
 * Unit tests for Import\Configure
 ******************************************************************************/

namespace Import;
require_once (__DIR__ . "/../../Import/Configure.php");

class ConfigureTestClass extends Configure {
    public bool $isnoisestop=FALSE;

    public function __construct() {
        new \GOClasses\Organ("Test");
    }

    public function createSwitch(int $id) : void {
        $this->newSwitch($id, $id);
    }
    
    public function testPipePitchMidi(array $hwdata): ?float {
        return parent::pipePitchMidi($hwdata);
    }

    public function testSampleHarmonicNumber(array $hwdata): ?float {
        return parent::sampleHarmonicNumber($hwdata);
    }

    public function testSampleMidiKey(array $hwdata) : int {
        return parent::sampleMidiKey($hwdata);
    }
    
    public function testSamplePitchMidi(array $hwdata) : ?float {
        return parent::samplePitchMidi($hwdata);
    }
    
    public function testSampleTuning(array $hwdata) : ?float {
        return parent::sampleTuning($hwdata);
    }
    protected function rankStopData(int $rankid, int $stopid) : array {
        $data=[
            1=>[], 
            3=>["MIDINoteNumIncrementFromDivisionToRank"=>12,
                "NumberOfMappedDivisionInputNodes"=>6,
                "MIDINoteNumOfFirstMappedDivisionInputNode"=>42]
        ];
        
        if (isset($data[$stopid]))
            return $data[$stopid];
        else
            return [];
    }

    protected function configurePanelSwitchImages(?\GOClasses\Sw1tch $switch, array $data): void {
    }
    
    protected function configurePanelEnclosureImages(\GOClasses\Enclosure $enclosure, array $data): void {
    }
    
}

class ConfigureTest extends \PHPUnit\Framework\TestCase {

    public function testCreateOrgan() {
        $testclass=new ConfigureTestClass();
        $organ=$testclass->createOrgan([
            "Identification_Name"=>"Identification_Name",
            "OrganInfo_Location"=>"OrganInfo_Location",
            "OrganInfo_Builder"=>"OrganInfo_Builder",
            "OrganInfo_BuildDate"=>"OrganInfo_BuildDate",
            "OrganInfo_Comments"=>"OrganInfo_Comments",
            "Identification_UniqueOrganID"=>"123456",
            "OrganInfo_InfoFilename"=>"OrganInfo_InfoFilename",
        ]);
        $this->assertEquals("Identification_Name", $organ->ChurchName);
        $this->assertEquals("OrganInfo_Location", $organ->ChurchAddress);
        $this->assertEquals("OrganInfo_Builder", $organ->OrganBuilder);
        $this->assertEquals("OrganInfo_BuildDate", $organ->OrganBuildDate);
        $this->assertEquals("OrganInfo_Comments", $organ->OrganComments);
        $this->assertEquals(
                "OrganInstallationPackages/123456/OrganInfo_InfoFilename",
                $organ->InfoFilename);
    }

    public function testCreatePanel() {
        $testclass=new ConfigureTestClass();
        $panel0=$testclass->createPanel([
            "PageID"=>1,
            "Name"=>"Panel",
            "Identification_Name"=>"Identification_Name",
            "Identification_LCDDisplayShortName"=>"Panel",
            "Display_ConsoleScreenWidthPixels"=>"123",
            "Display_ConsoleScreenHeightPixels"=>"231",
        ]);
        $this->assertEquals("Panel", $panel0->Name);
        $this->assertEquals("123", $panel0->DispScreenSizeHoriz);
        $this->assertEquals("231", $panel0->DispScreenSizeVert);
    }

    public function testCreateManual() {
        $testclass=new ConfigureTestClass();
        $manual=$testclass->createManual([
            "KeyboardID"=>1,
            "Name"=>"Manual Name",
            "KeyGen_NumberOfKeys"=>"30",
            "InpGen_NumberOfInputs"=>"60",
            "KeyGen_MIDINoteNumberOfFirstKey"=>"30",
            "InpGen_MIDINoteNumberOfFirstInput"=>"36",
            "KeyGen_DispKeyboardLeftXPos"=>"123",
            "KeyGen_DispKeyboardTopYPos"=>"321"
        ]);
        $this->assertEquals("Manual Name",$manual->Name);
        $this->assertEquals("60",$manual->NumberOfAccessibleKeys);
        $this->assertEquals("60",$manual->NumberOfLogicalKeys);
        $this->assertEquals("36",$manual->FirstAccessibleKeyMIDINoteNumber);
        $this->assertEquals("123",$manual->PositionX);
        $this->assertEquals("321",$manual->PositionY);
        
        $this->assertTrue(
                $testclass->createManual(["KeyboardID"=>2, "Name"=>"Manual Name"])
                instanceof \GOClasses\Manual);
        
    }

    public function testCreateStop() {
        $testclass=new ConfigureTestClass();
        $testclass->createManual(["KeyboardID"=>12, "Name"=>"Manual Name"]);
        $switch=$testclass->createStop([
            "StopID"=>"11",
            "DivisionID"=>"12",
            "ControllingSwitchID=12",
            "SwitchID"=>13,
            "Name"=>"Name",
        ]);
        $this->assertEquals("Stop Name", $switch->Name);
        $this->assertTrue($testclass->getStop(11) instanceof \GOClasses\Stop);
        $this->assertTrue($testclass->getSwitch(13)===$switch);
        
        $switch=$testclass->createStop([
            "StopID"=>"13",
            "ControllingSwitchID"=>12,
            "DivisionID"=>"12",
            "Name"=>"Name",
        ]);
        $this->assertTrue($testclass->getSwitch(12)===$switch);
    }

    public function testCreateRank() {
        $testclass=new ConfigureTestClass();
        $testclass->createWindchestGroup(["GroupID"=>123, "Name"=>"Test"]);
        $rank1=$testclass->createRank([
            "RankID"=>1, "Name"=>"Name", "StopIDs"=>[], "GroupID"=>123
        ]);
        $this->assertTrue($rank1 instanceof \GOClasses\Rank);
        $this->assertTrue($testclass->getRank(1)===$rank1);

        $testclass->createManual(["KeyboardID"=>1, "Name"=>"Test"]);
        $testclass->createStop(["StopID"=>"1", "DivisionID"=>"1", "ControllingSwitchID"=>1, "Name"=>"Name"]);
        $testclass->createStop(["StopID"=>"2", "DivisionID"=>"1", "ControllingSwitchID"=>1, "Name"=>"Name"]);
        $testclass->createStop(["StopID"=>"3", "DivisionID"=>"1", "ControllingSwitchID"=>1, "Name"=>"Name"]);
        $rank2=$testclass->createRank([
            "RankID"=>2, "Name"=>"Name", "StopIDs"=>[1,3], "GroupID"=>123
        ]);
        $this->assertTrue($rank1 instanceof \GOClasses\Rank);
        
        $stop1=$testclass->getStop(1);
        $this->assertFalse(isset($stop3->Rank001FirstPipeNumber));
        $this->assertFalse(isset($stop3->Rank001PipeCount));
        $this->assertFalse(isset( $stop3->Rank001FirstAccessibleKeyNumber));
        $this->assertEquals(2, $stop1->Rank001);
        $stop2=$testclass->getStop(2);
        $this->assertEquals(0 ,$stop2->NumberOfRanks);
        $stop3=$testclass->getStop(3);
        $this->assertEquals(2, $stop3->Rank001);
        $this->assertEquals(13, $stop3->Rank001FirstPipeNumber);
        $this->assertEquals(6, $stop3->Rank001PipeCount);
        $this->assertEquals(7, $stop3->Rank001FirstAccessibleKeyNumber);
        echo $stop3;
        
    }

    public function testCreateCoupler() {
        $testclass=new ConfigureTestClass();
        $testclass->createManual(["KeyboardID"=>2, "Name"=>"Manual 2"]);
        $testclass->createManual(["KeyboardID"=>4, "Name"=>"Manual 4"]);
        $switch=$testclass->createCoupler([
            "Name"=>"Name",
            "CouplerID"=>7,
            "SwitchID"=>3,
            "SourceKeyboardID"=>2,
            "DestDivisionID"=>4,
            "MIDINoteNumberIncrement"=>12
        ]);
        $this->assertTrue($switch instanceof \GOClasses\Sw1tch);
        $this->assertEquals($testclass->getSwitch(3), $switch);
        $coupler=$testclass->getCoupler(7);
        $this->assertEquals(12, $coupler->DestinationKeyshift);
        
        $switch=$testclass->createCoupler([
            "Name"=>"Name",
            "CouplerID"=>7,
            "ConditionSwitchID"=>8,
            "SourceKeyboardID"=>2,
            "DestDivisionID"=>4,
            "MIDINoteNumberIncrement"=>12
        ]);
        $this->assertEquals($testclass->getSwitch(8), $switch);
    }

    public function testCreateEnclosure() {
        $testclass=new ConfigureTestClass();
        $testclass->createWindchestGroup(["GroupID"=>32, "Name"=>"Test"]);
        $enclosure=$testclass->createEnclosure(
                ["EnclosureID"=>1, "Name"=>"Test", "AmpMinimumLevel"=>23, "GroupIDs"=>[32]]);
        $this->assertEquals(23, $enclosure->AmpMinimumLevel);
    }

    public function testCreateSwitchNoise() {
        $testclass=new ConfigureTestClass();
        $testclass->createManual(["KeyboardID"=>1, "Name"=>"Test 1"]);
        $testclass->createManual(["KeyboardID"=>2, "Name"=>"Test 2"]);
        $testclass->createWindchestGroup(["GroupID"=>32, "Name"=>"Test"]);

        $switch1=$testclass->createSwitch(1);
        $testclass->createSwitchNoise(
                Configure::TremulantNoise, 
                ["ControllingSwitchID"=>1, "GroupID"=>32]);
        $this->assertEquals(1, $testclass->getSwitchNoise(1)->Switch001);
        $this->assertEquals(1, $testclass->getSwitchNoise(-1)->Switch001);
        
        $switch2=$testclass->createSwitch(2);
        $testclass->createSwitchNoise(
                Configure::TremulantNoise, 
                ["SwitchID"=>2, "GroupID"=>32]);
        $this->assertEquals(2, $testclass->getSwitchNoise(2)->Switch001);
        $this->assertEquals(2, $testclass->getSwitchNoise(-2)->Switch001);
        
        $switch3=$testclass->createSwitch(3);
        $testclass->createSwitchNoise(
                Configure::CouplerNoise,
                ["ConditionSwitchID"=>3, "GroupID"=>32]);
        $this->assertEquals(3, $testclass->getSwitchNoise(3)->Switch001);
        $this->assertEquals(3, $testclass->getSwitchNoise(-3)->Switch001);

        $switch4=$testclass->createSwitch(4);
        $testclass->createSwitchNoise(
                Configure::CouplerNoise, 
                ["ConditionSwitchID"=>4, "GroupID"=>32]);
        $this->assertEquals(4, $testclass->getSwitchNoise(4)->Switch001);
        $this->assertEquals(4, $testclass->getSwitchNoise(-4)->Switch001);

        $switch5=$testclass->createSwitch(5);
        $testclass->createSwitchNoise(
                Configure::SwitchNoise, 
                ["ControllingSwitchID"=>5, "GroupID"=>32]);
        $this->assertEquals(5, $testclass->getSwitchNoise(5)->Switch001);
        $this->assertEquals(5, $testclass->getSwitchNoise(-5)->Switch001);
        
    }

    public function testCreateTremulant() {
        $testclass=new ConfigureTestClass();
        $wcg3=$testclass->createWindchestGroup(["GroupID"=>3, "Name"=>"Test"]);
        $this->assertNull($wcg3->Tremulant001);
        $switch=$testclass->createTremulant(
                ["TremulantID"=>23, "Name"=>"Tremulant 32", 
                    "ControllingSwitchID"=>23, "Type"=>"Synth", "GroupIDs"=>[2,3]]);
        $this->assertTrue($switch instanceof \GOClasses\Sw1tch);
        $this->assertEquals($testclass->getSwitch(23), $switch);
        $this->assertTrue($testclass->getTremulant(23) instanceof \GOClasses\Tremulant);
        $this->assertEquals(1, $wcg3->Tremulant001);

        $switch=$testclass->createTremulant(
                ["TremulantID"=>22, "Name"=>"Tremulant 22", "SwitchID"=>17,
                    "Type"=>"Switched", "GroupIDs"=>[1,3]]);
        $this->assertEquals($testclass->getSwitch(17), $switch);
        $this->assertNull($testclass->getTremulant(22, FALSE));
        $this->assertEquals("002", $switch->instance());
        $off=$testclass->getSwitch(-17);
        $this->assertEquals("Not", $off->Function);
        $this->assertEquals(2, $off->Switch001);
    }
 
    public function testProcessSample() {
        $testclass=new ConfigureTestClass();
        $testclass->createWindchestGroup(["GroupID"=>34, "Name"=>"Test"]);
        $testclass->createRank(["RankID"=>4, "Name"=>"Name", "StopIDs"=>[], "GroupID"=>34]);
        $sampledata=[
            "PipeID"=>41,
            "RankID"=>4,
            "NormalMIDINoteNumber"=>"41",
            "Pitch_Tempered_RankBasePitch64ftHarmonicNum"=>"12",
            "InstallationPackageID"=>"123",
            "SampleFilename"=>"Attack1.wav"
        ];
        $pipe=$testclass->processSample($sampledata, TRUE);
        $this->assertEquals(
                "Pipe000AttackCount=0\n" .
                "Pipe000ReleaseCount=0\n" .
                "Pipe000HarmonicNumber=12\n" .
                "Pipe000=OrganInstallationPackages/000123/Attack1.wav\n", (string) $pipe);

        $sampledata["SampleFilename"]="Attack2.wav";
        $testclass->processSample($sampledata, TRUE);
        $this->assertEquals(1, $pipe->AttackCount);

        $sampledata["SampleFilename"]="Release1.wav";
        $testclass->processSample($sampledata, FALSE);
        $this->assertEquals(1, $pipe->ReleaseCount);
    }

    private function testProcessNoise() { 
        return; /** @todo - model has changed ??? */
        $testclass=new ConfigureTestClass();
        $testclass->isnoisestop=TRUE;
        $testclass->createStop([
            "StopID"=>"1",
            "ControllingSwitchID"=>1,
            "Name"=>"Name",
        ]);
        $this->assertTrue($testclass->getStop(1) instanceof \GOClasses\SwitchNoise);
        $noise=$testclass->getStop(1)->Noise();
        $sampledata=[
            "PipeID"=>123,
            "Pitch_Tempered_RankBasePitch64ftHarmonicNum"=>"12",
            "InstallationPackageID"=>"123",
            "SampleFilename"=>"Attack1.wav"
        ];
        $testclass->processNoise($sampledata, TRUE);
        $sampledata["SampleFilename"]="Release1.wav";
        $testclass->processNoise($sampledata, FALSE);
        $this->assertEquals(
                "Pipe001AttackCount=0\n" .
                "Pipe001ReleaseCount=1\n" .
                "Pipe001Percussive=N\n" .
                "Pipe001=OrganInstallationPackages/000123/Attack1.wav\n" .
                "Pipe001LoadRelease=N\n" .
                "Pipe001Release001=OrganInstallationPackages/000123/Release1.wav\n" .
                "Pipe001Release001MaxKeyPressTime=-1\n", (string) $noise);
    }
    
    public function testPipePitchMidi() {
        $testclass=new ConfigureTestClass();
        $this->assertNull($testclass->testPipePitchMidi([]));
        $this->assertEquals(36.0, $testclass->testPipePitchMidi(["NormalMIDINoteNumber"=>36]));
        $this->assertEquals(71.21309485364912, $testclass->testPipePitchMidi(["Pitch_OriginalOrgan_PitchHz"=>500]));
    }
    
    public function testSampleHarmonicNumber() {
        $testclass=new ConfigureTestClass();
        $this->assertEquals(8,$testclass->testSampleHarmonicNumber([]));
        $this->assertEquals(12,$testclass->testSampleHarmonicNumber(["Pitch_Tempered_RankBasePitch64ftHarmonicNum"=>12]));
        $this->assertEquals(24,$testclass->testSampleHarmonicNumber(["Pitch_RankBasePitch64ftHarmonicNum"=>24]));
    }

    public function testSampleMidiKey() {
        $testclass=new ConfigureTestClass();
        $this->assertEquals(39,$testclass->testSampleMidiKey(["NormalMIDINoteNumber"=>39]));
        $this->assertEquals(65,$testclass->testSampleMidiKey(["Pitch_NormalMIDINoteNumber"=>65]));
        $this->assertEquals(36,$testclass->testSampleMidiKey(["SampleFilename"=>"036-c.wav"]));
        try {
            $this->assertNull($testclass->testSampleMidiKey(["PipeID"=>1234]));
            throw new \Exception("Expected to fail!");
        }
        catch (\Exception $ex) {
            $this->assertEquals("Unable to determine midi key for pipe 1234", $ex->getMessage());
        }
    }
    
    public function testSamplePitchMidi() {
        $testclass=new ConfigureTestClass();
        $this->assertNull($testclass->testSamplePitchMidi([]));
        $this->assertEquals(71.62368343770409,$testclass->testSamplePitchMidi(["Pitch_ExactSamplePitch"=>512]));
        $this->assertEquals(65.0,$testclass->testSampleMidiKey(["Pitch_NormalMIDINoteNumber"=>65]));
    }
    
    public function testSampleTuning() {
        $testclass=new ConfigureTestClass();
        $this->assertNull($testclass->testSampleTuning([]));
        $this->assertNull($testclass->testSampleTuning(["Pitch_ExactSamplePitch"=>512]));
        $this->assertNull($testclass->testSampleTuning(["Pitch_OriginalOrgan_PitchHz"=>500]));
        $this->assertEquals(0.0, $testclass->testSampleTuning(
                ["Pitch_ExactSamplePitch"=>512, "Pitch_OriginalOrgan_PitchHz"=>512]));
        $this->assertEquals(-41.05885840549632, $testclass->testSampleTuning(
                ["Pitch_ExactSamplePitch"=>512, "Pitch_OriginalOrgan_PitchHz"=>500]));
        $this->assertEquals(+41.05885840549632, $testclass->testSampleTuning(
                ["Pitch_ExactSamplePitch"=>500, "Pitch_OriginalOrgan_PitchHz"=>512]));
    }
}