<?php

/*******************************************************************************
 * Unit tests for HWData
 ******************************************************************************/

namespace HWClasses;
require_once (__DIR__ . "/../../HWClasses/HWData.php");

class HWDataTest extends \PHPUnit\Framework\TestCase {
    
    const Skrzatusz="Skrzatusz";
    const Groningen="Groningen-StMartini-SemiDry.DEMO";
    const MMC="MMCOrgan3-Lodge2.Organ_Hauptwerk";
    const Utrecht="Utrecht Dom, Surround DEMO";
    const Walcker="Walcker_Miskolc v2 surround_demo";
    
    private static function HWData($xmlfile) {
        static $cache=[];
        if (!isset($cache[$xmlfile])) 
            $cache[$xmlfile]=new HWData(__DIR__ . "/../ODF/${xmlfile}.Organ_Hauptwerk_xml");
        return $cache[$xmlfile];
    }

    public function testRun() {
        $skrzatusz=self::HWData(self::Skrzatusz);
        $utrecht=self::HWData(self::Utrecht);
        $walcker=self::HWData(self::Walcker);
        $this->assertEquals(3, sizeof($utrecht->continuousControl(229066)));
        $this->assertEquals(2, sizeof($utrecht->continuousControlLink(11)));
        $this->assertEquals(5, sizeof($skrzatusz->displayPage(2)));
        $this->assertEquals(2, sizeof($skrzatusz->division(3)));
        $this->assertEquals(3, sizeof($utrecht->enclosure(998)));
        $this->assertEquals(2, sizeof($utrecht->enclosurePipe(40975)));
        $this->assertEquals(3, sizeof($skrzatusz->divisionInput(5)));
        $this->assertEquals(9, sizeof($skrzatusz->imageSet(5)));
        $this->assertEquals(2, sizeof($skrzatusz->imageSetElement(5)));
        $this->assertEquals(25, sizeof($skrzatusz->imageSetInstance(5)));
        $this->assertEquals(14, sizeof($skrzatusz->keyAction(2)));
        $this->assertEquals(22, sizeof($skrzatusz->keyImageSet(2)));
        $this->assertEquals(21, sizeof($skrzatusz->keyboard(3)));
        $this->assertEquals(18, sizeof($skrzatusz->pipe(101)));
        $this->assertEquals(10, sizeof($skrzatusz->attack(102)));
        $this->assertEquals(4, sizeof($skrzatusz->layer(103)));
        $this->assertEquals(10, sizeof($skrzatusz->rank(5)));
        $this->assertEquals(7, sizeof($skrzatusz->sample(106)));
        $this->assertTrue($skrzatusz->sampledRank(1));
        $this->assertFalse($skrzatusz->sampledRank(99));
        $this->assertEquals(4, sizeof($skrzatusz->stop(7)));
        $this->assertEquals(1, sizeof($skrzatusz->stopRank(8)));
        $this->assertEquals(2, sizeof($utrecht->altStopRank(60)));
        $this->assertEquals(1, sizeof($skrzatusz->rankStop(8)));
        $this->assertEquals(1, sizeof($utrecht->altRankStop(608)));
        $this->assertEquals(9, sizeof($skrzatusz->switch(11)));
        $this->assertEquals(1, sizeof($skrzatusz->switchLink(33)));
        $this->assertEquals(1, sizeof($walcker->textInstance(65)));
        $this->assertEquals(9, sizeof($walcker->textStyle(7)));
        $this->assertEquals(3, sizeof($skrzatusz->tremulant(1)));
        $this->assertEquals(7, sizeof($skrzatusz->tremulantWaveForm(1)));
        $this->assertEquals(4, sizeof($skrzatusz->tremulantWaveFormPipe(1501)));
        $this->assertEquals(2, sizeof($skrzatusz->windCompartment(2)));        
    }
    
    public function testPatchDisplayPage() {
        $skrzatusz=self::HWData(self::Skrzatusz);
        $this->assertEquals(3, sizeof($skrzatusz->displayPages()));

        $skrzatusz->patchDisplayPages([]);
        $this->assertEquals(3, sizeof($skrzatusz->displayPages()));

        $skrzatusz->patchDisplayPages([4=>[]]);
        $this->assertEquals(3, sizeof($skrzatusz->displayPages()));

        $skrzatusz->patchDisplayPages([4=>["x"=>"y"]]);
        $this->assertEquals(4, sizeof($skrzatusz->displayPages()));
        $this->assertEquals(["x"=>"y"], $skrzatusz->displayPage(4));

        $skrzatusz->patchDisplayPages([4=>["x"=>"z"]]);
        $this->assertEquals(["x"=>"z"], $skrzatusz->displayPage(4));

        $this->assertEquals([
            'PageID' => '1',
            'Name' => 'Stops',
            'AlternateConsoleScreenLayout1_Include' => '',
            'AlternateConsoleScreenLayout2_Include' => '',
            'AlternateConsoleScreenLayout3_Include' => '',         
        ], $skrzatusz->displayPage(1));
        $skrzatusz->patchDisplayPages([1=>["AlternateConsoleScreenLayout1_Include"=>"N"]]);
        $this->assertEquals([
            'PageID' => '1',
            'Name' => 'Stops',
            'AlternateConsoleScreenLayout1_Include' => 'N',
            'AlternateConsoleScreenLayout2_Include' => '',
            'AlternateConsoleScreenLayout3_Include' => '',         
        ], $skrzatusz->displayPage(1));
        
        $skrzatusz->patchDisplayPages([4=>"DELETE"]);
        $this->assertEquals(3, sizeof($skrzatusz->displayPages()));
        try {
            $this->assertEquals([], $skrzatusz->displayPage(4));
            throw new \Exception("Did not fail!!!");
        }
        catch (\Exception $ex) {
            $this->assertEquals("Undefined offset: 4", $ex->getMessage());
        }
    }
    
    public function testPatchStopRank() {
        $skrzatusz=self::HWData(self::Skrzatusz);
        
        $this->assertEquals([
            1=>[
                'StopID' => '1',
                'Name' => 'P Violon 16\'',
                'RankTypeCode' => '1',
                'RankID' => '1',
                'MIDINoteNumOfFirstMappedDivisionInputNode' => '36',
                'NumberOfMappedDivisionInputNodes' => '27',
                'MIDINoteNumIncrementFromDivisionToRank' => '0',
                'AlternateRankID' => ''               
            ]
        ], $skrzatusz->stopRank(1));
        $this->assertEquals([], $skrzatusz->altStopRank(1));
        $this->assertEquals([
            1=>[
                'StopID' => '1',
                'Name' => 'P Violon 16\'',
                'RankTypeCode' => '1',
                'RankID' => '1',
                'MIDINoteNumOfFirstMappedDivisionInputNode' => '36',
                'NumberOfMappedDivisionInputNodes' => '27',
                'MIDINoteNumIncrementFromDivisionToRank' => '0',
                'AlternateRankID' => ''               
            ]
        ], $skrzatusz->RankStop(1));
        $this->assertEquals([], $skrzatusz->altRankStop(1));
        
        $skrzatusz->patchStopRanks([
                0=>[
                    'AlternateRankID' => '1',
                    'RankID' => '2',
                ]]);

        $this->assertEquals([
            2=>[
                'StopID' => '1',
                'Name' => 'P Violon 16\'',
                'RankTypeCode' => '1',
                'RankID' => '2',
                'MIDINoteNumOfFirstMappedDivisionInputNode' => '36',
                'NumberOfMappedDivisionInputNodes' => '27',
                'MIDINoteNumIncrementFromDivisionToRank' => '0',
                'AlternateRankID' => '1'               
            ]
        ], $skrzatusz->StopRank(1));
        $this->assertEquals([
            1=>[
                'StopID' => '1',
                'Name' => 'P Violon 16\'',
                'RankTypeCode' => '1',
                'RankID' => '2',
                'MIDINoteNumOfFirstMappedDivisionInputNode' => '36',
                'NumberOfMappedDivisionInputNodes' => '27',
                'MIDINoteNumIncrementFromDivisionToRank' => '0',
                'AlternateRankID' => '1'               
            ]
        ], $skrzatusz->altStopRank(1));
        $this->assertEquals([], $skrzatusz->RankStop(1));
        $this->assertEquals([
            1=>[
                'StopID' => '1',
                'Name' => 'P Violon 16\'',
                'RankTypeCode' => '1',
                'RankID' => '2',
                'MIDINoteNumOfFirstMappedDivisionInputNode' => '36',
                'NumberOfMappedDivisionInputNodes' => '27',
                'MIDINoteNumIncrementFromDivisionToRank' => '0',
                'AlternateRankID' => '1'               
            ]
        ], $skrzatusz->altRankStop(1));
    }

    public function testPatchEnclosure() {
        $utrecht=self::HWData(self::Utrecht);
        $this->assertEquals([
            'EnclosureID' => '998',
            'Name' => 'Swell',
            'ShutterPositionContinuousControlID' => '998'
        ], $utrecht->enclosure(998));
        $utrecht->patchEnclosures([998=>["x"=>"y"]]);
        $this->assertEquals([
            'EnclosureID' => '998',
            'Name' => 'Swell',
            'ShutterPositionContinuousControlID' => '998',
            'x' => 'y'
        ], $utrecht->enclosure(998));
    }
}