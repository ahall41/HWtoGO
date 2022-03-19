<?php

/*******************************************************************************
 * Unit tests for Import\Create
 ******************************************************************************/

namespace Import;
require_once (__DIR__ . "/../../Import/Create.php");

class CreateTestClass extends Create {
    public function __construct() {
        new \GOClasses\Organ("Test");
    }
}

class CreateTest extends \PHPUnit\Framework\TestCase {
    
    public function testCreateOrgan() {
        $testclass=new CreateTestClass();
        $organ=$testclass->createOrgan(["ChurchName"=>"Test Organ"]);
        $this->assertTrue($organ instanceof \GOClasses\Organ);
        $this->assertEquals($organ, $testclass->getOrgan());
        $this->assertEquals("Test Organ", $organ->ChurchName);
    }
    
    public function testCreatePanel() {
        $testclass=new CreateTestClass();
        $panel123=$testclass->createPanel(["PanelID"=>123, "Name"=>"Test Panel 123"]);
        $panel456=$testclass->createPanel(["PanelID"=>456, "Name"=>"Test Panel 456"]);
        $this->assertTrue($panel123 instanceof \GOClasses\Panel);
        $this->assertEquals($panel123, $testclass->getPanel(123));
        $this->assertEquals($panel456, $testclass->getPanel(456));
        $this->assertEquals("Test Panel 123",$panel123->Name);
    }
    
    public function testCreateManual() {
        $testclass=new CreateTestClass();
        $manual123=$testclass->createManual(["ManualID"=>123, "Name"=>"Test Manual 123"]);
        $manual456=$testclass->createManual(["ManualID"=>456, "Name"=>"Test Manual 456"]);
        $this->assertTrue($manual123 instanceof \GOClasses\Manual);
        $this->assertEquals($manual123, $testclass->getManual(123));
        $this->assertEquals($manual456, $testclass->getManual(456));
        $this->assertEquals("Test Manual 123",$manual123->Name);
    }

    public function testCreateWindchestGroup() {
        $testclass=new CreateTestClass();
        $group123=$testclass->createWindchestGroup(["GroupID"=>123, "Name"=>"Test WindchestGroup 123"]);
        $group456=$testclass->createWindchestGroup(["GroupID"=>456, "Name"=>"Test WindchestGroup 456"]);
        $this->assertTrue($group123 instanceof \GOClasses\WindchestGroup);
        $this->assertEquals($group123, $testclass->getWindchestGroup(123));
        $this->assertEquals($group456, $testclass->getWindchestGroup(456));
        $this->assertEquals("Test WindchestGroup 123",$group123->Name);
    }

    public function testCreateEnclosure() {
        $testclass=new CreateTestClass();
        $testclass->createWindchestGroup(["GroupID"=>12, "Name"=>"Group 12"]);
        $testclass->createWindchestGroup(["GroupID"=>34, "Name"=>"Group 34"]);
        $wcg56=$testclass->createWindchestGroup(["GroupID"=>56, "Name"=>"Group 56"]);
        $enclosure123=$testclass->createEnclosure(
                ["EnclosureID"=>123, "Name"=>"Test Enclosure 123", "GroupIDs"=>[12,56]]);
        $enclosure456=$testclass->createEnclosure(
                ["EnclosureID"=>456, "Name"=>"Test Enclosure 456", "GroupIDs"=>[34,56]]);
        $this->assertTrue($enclosure123 instanceof \GOClasses\Enclosure);
        $this->assertEquals($enclosure123, $testclass->getEnclosure(123));
        $this->assertEquals($enclosure456, $testclass->getEnclosure(456));
        $this->assertEquals("Test Enclosure 123",$enclosure123->Name);
        $this->assertEquals(2,$wcg56->NumberOfEnclosures);
    }
    
    public function testCreateStop() {
        $testclass=new CreateTestClass();
        $manual=$testclass->createManual(["ManualID"=>1, "Name"=>"Test Manual"]);
        $switch123=$testclass->createStop(
                ["StopID"=>321, "SwitchID"=>123, "Name"=>"Test Stop 321", "DivisionID"=>1]);
        $switch456=$testclass->createStop(
                ["StopID"=>654, "SwitchID"=>456, "Name"=>"Test Stop 654", "DivisionID"=>1]);
        $this->assertTrue($switch123 instanceof \GOClasses\Sw1tch);
        $stop321=$testclass->getStop(321);
        $this->assertTrue($stop321 instanceof \GOClasses\Stop);
        $this->assertEquals(1,$stop321->SwitchCount);
        $this->assertEquals(2,$manual->NumberOfStops);
        $this->assertEquals("Test Stop 321", $stop321->Name);
        $this->assertEquals("Stop Test Stop 654", $switch456->Name);

        // re-used switch
        $switch123a=$testclass->createStop(
                ["StopID"=>234, "SwitchID"=>123, "Name"=>"Test Stop 234", "DivisionID"=>1]);
        $this->assertEquals($switch123a, $switch123);
        $this->assertEquals("Stop Test Stop 321", $switch123a->Name);
    }
    
    public function testCreateRank() {
        $testclass=new CreateTestClass();
        $testclass->createManual(["ManualID"=>1, "Name"=>"Test Manual"]);
        $testclass->createWindchestGroup(["GroupID"=>1, "Name"=>"Test Group 1"]);
        $testclass->createWindchestGroup(["GroupID"=>2, "Name"=>"Test Group 2"]);
        $testclass->createStop(
                ["StopID"=>1, "SwitchID"=>1, "Name"=>"Test Stop 1", "DivisionID"=>1]);
        $testclass->createStop(
                ["StopID"=>2, "SwitchID"=>1, "Name"=>"Test Stop 2", "DivisionID"=>1]);
        $rank123=$testclass->createRank(
                ["RankID"=>123, "StopIDs"=>[1], "GroupID"=>1, "Name"=>"Rank 123"]);
        $rank456=$testclass->createRank(
                ["RankID"=>456, "StopIDs"=>[2], "GroupID"=>2, "Name"=>"Rank 456"]);
        $rank789=$testclass->createRank(
                ["RankID"=>123, "StopIDs"=>[1,2], "GroupID"=>2, "Name"=>"Rank 789"]);
        $this->assertTrue($rank123 instanceof \GOClasses\Rank);
        $this->assertEquals($rank456, $testclass->getRank(456));
        $this->assertEquals("Rank 789",$rank789->Name);
        $this->assertEquals(2, $rank789->WindchestGroup);
        $this->assertEquals(2, $testclass->getStop(1)->NumberOfRanks);
        $this->assertEquals(1, $testclass->getStop(1)->Rank001);
        $this->assertEquals(3, $testclass->getStop(1)->Rank002);
        $this->assertEquals(2, $testclass->getStop(2)->Rank001);
        $this->assertEquals(3, $testclass->getStop(2)->Rank002);
    }
    
    public function testCreateCoupler() {
        $testclass=new CreateTestClass();
        $manual=$testclass->createManual(["ManualID"=>1, "Name"=>"Test Manual"]);
        $switch=$testclass->createCoupler(["CouplerID"=>123, "ManualID"=>1, "SwitchID"=>321, "Name"=>"Test"]);
        $this->assertEquals("Coupler Test", $switch->Name);
        $coupler=$testclass->getCoupler(123);
        $this->assertEquals(1,$manual->Coupler001);
    }
    
    public function testSynthTremulant() {
        $testclass=new CreateTestClass();
        $group=$testclass->createWindchestGroup(["GroupID"=>3, "Name"=>"Test WindchestGroup"]);
        $switch=$testclass->createTremulant(
                ["TremulantID"=>1, "SwitchID"=>2, "GroupIDs"=>[3], "Name"=>"Test 1", "Type"=>"Synth"]);
        $this->assertEquals("Tremulant Test 1", $switch->Name);
        $tremulant=$testclass->getTremulant(1);
        $this->assertEquals(1,$tremulant->Switch001);
        $this->assertEquals("Synth",$tremulant->TremulantType);
        $this->assertEquals(1,$group->Tremulant001);
    }
    
    public function testSwitchedTremulant() {
        $testclass=new CreateTestClass();
        $on=$testclass->createTremulant(
                ["SwitchID"=>2, "Name"=>"Test 2", "Type"=>"Switched"]);
        $this->assertEquals("Tremulant Test 2 (on)", $on->Name);
        $off=$testclass->getSwitch(-2);
        $this->assertEquals("Tremulant Test 2 (off)", $off->Name);
        $this->assertEquals("Not", $off->Function);
        $this->assertEquals(1, $off->Switch001);
    }
}