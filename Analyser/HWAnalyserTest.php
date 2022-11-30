<?php

/*******************************************************************************
 * Unit tests for HWAnalyser
 * Not so much a unit test - more a convenient way of examioning the sample sets!
 ******************************************************************************/

namespace HWClasses;
require_once (__DIR__ . "/../../HWClasses/HWAnalyser.php");

class HWAnalyserTest extends \PHPUnit\Framework\TestCase {
    
    const Skrzatusz="Skrzatusz";
    const Groningen="Groningen-StMartini-SemiDry.DEMO";
    const MMC="MMCOrgan3-Lodge2.Organ_Hauptwerk";
    const Utrecht="Utrecht Dom, Surround DEMO";
    const Walcker="Walcker_Miskolc v2 surround_demo";
    
    private static function HWAnalyser($xmlfile) : HWAnalyser {
        $analyser=new HWAnalyser();
        $analyser->analyse(__DIR__ . "/../ODF/${xmlfile}.Organ_Hauptwerk_xml");
        return $analyser;
    }

    public function xxtestMMC() {
        $hwa=self::HWAnalyser(self::MMC);
        $this->assertEquals(15, sizeof($hwa->ranksused));
        $this->assertEquals(821, sizeof($hwa->pipesused));
        $this->assertEquals(122, sizeof($hwa->ranksused[25]));
        
        $this->assertEquals(2, sizeof($hwa->pipesused[550]));
        $this->assertEquals([
            'PipeID' => '550',
            'RankID' => 1,
            'SampleID' => '3',
            'PipeMidi' => '38',
            'PipeHn' => '4',
            'PipeHz' => null,
            'SampleMidi' => 0,
            'SampleHn' => null,
            'SampleHz' => null,          
        ], $hwa->pipesused[550][0]);
        
        $s1=$hwa->pipesSummary([550]);
        $this->assertEquals([
            'Pipes' => 1,
            'PipeMidiFr' => '38',
            'PipeMidiTo' => '38',
            'PipeHz' => 0,
            'PipeHn' => 2,
            'SampleMidiFr' => 0,
            'SampleMidiTo' => 0,
            'SampleHz' => 0,
            'SampleHn' => 0,
            'Samples' => 2,
        ], $s1);
        
        $s2=$hwa->pipesSummary($hwa->ranksused[25]);
        $this->assertEquals([
            'Pipes' => 122,
            'PipeMidiFr' => 0,
            'PipeMidiTo' => '96',
            'PipeHz' => 0,
            'PipeHn' => 244,
            'SampleMidiFr' => 0,
            'SampleMidiTo' => 0,
            'SampleHz' => 0,
            'SampleHn' => 0,
            'Samples' => 244,         
        ], $s2);
        
        $s3=$hwa->rankSummary();
        $this->assertEquals(15, sizeof($s3));
    }
    
    public function xxtestSkrzatusz() {
        $hwa=self::HWAnalyser(self::Skrzatusz);
        $this->assertEquals(27, sizeof($hwa->ranksused));
        $this->assertEquals(1187, sizeof($hwa->pipesused));
        $this->assertEquals(3, sizeof($hwa->pipesused[101]));
        
        $s2=$hwa->pipesSummary($hwa->ranksused[21]);
        $this->assertEquals([
            'PipeMidiFr' => '36',
            'PipeMidiTo' => '82',
            'PipeHz' => 46,
            'PipeHn' => 0,
            'SampleMidiFr' => 0,
            'SampleMidiTo' => 0,
            'SampleHz' => 46,
            'SampleHn' => 0,
            'Samples' => 46,
            'Pipes' => 46            
        ], $s2);
        
        $s3=$hwa->rankSummary();
        $this->assertEquals(27, sizeof($s3));
    }
    
    public function xxtestBudaHills() {
        $hwa=new HWAnalyser();
        $hwa->analyse("/GrandOrgue/Organs/LutheranBuda/OrganDefinitions/Lutheran Buda_surround.Organ_Hauptwerk_xml");
        $this->assertEquals(54, sizeof($hwa->ranksused));
        $this->assertEquals(2909, sizeof($hwa->pipesused));
        //$hwa->showreport();
        
        $hwa->showPipe(47140);
        $hwa->showPipe(47150);
    }
    
    public function xxtestBillerbeck() {
        $hwa=new HWAnalyser();
        $hwa->analyse(getenv("HOME") . "/GrandOrgue/Organs/Billerbeck/OrganDefinitions/Billerbeck, Fleiter Surr.Demo.Organ_Hauptwerk_xml");
        $this->assertEquals(197, sizeof($hwa->ranksused));
        $this->assertEquals(12417, sizeof($hwa->pipesused));
        //$hwa->showreport();
        
        $hwa->showPipe(97044);
        $hwa->showPipe(96011);
    }
    
    public function xxtestTraceImageCaen() {
        $hwa=new HWAnalyser();
        $hwa->analyse(getenv("HOME") . "/GrandOrgue/Organs/Caen/OrganDefinitions/Caen St. Etienne, Cavaille-Coll, Demo.Organ_Hauptwerk_xml");
        $hwa->traceImage(":stopsw:");
        //$hwa->traceImage(":pozadi/dp:");
        //$hwa->traceImage(":jamb:");
        //$hwa->traceImage(":simple:");
        //$hwa->traceImage(":console:");
        //$hwa->traceImage(":positifv:");
        //$hwa->traceImage(":^stop:");
        //$hwa->traceImage(":trompette:");
        $this->assertTrue(TRUE);
    }

    public function xxtestTraceImageBillerbeck() {
        $hwa=new HWAnalyser();
        $hwa->analyse(getenv("HOME") . "/GrandOrgue/Organs/Billerbeck/OrganDefinitions/Billerbeck, Fleiter Surr.Demo.Organ_Hauptwerk_xml");
        $hwa->traceImage(":indik:");
        $this->assertTrue(TRUE);
    }
    
    private function listImagesByPage($xmlfile) {
        $this->assertTrue(TRUE);
        $hwd=new HWData(getenv("HOME") . "/GrandOrgue/Organs/" . $xmlfile);
        $pages=[];
        $instances=$hwd->imageSetInstances();
        asort($instances);
        foreach ($instances as $id=>$instance)
            $pages[$instance["DisplayPageID"]][$id]=$instance;
        
        asort($pages);
        foreach ($pages as $p=>$page) {
            foreach ($page as $id=>$instance)
                echo "$p $id ", $instance["Name"], "\r";
        }
    }
    
    public function xxtestImagesByPageCaen() {
        $this->listImagesByPage("/Caen/OrganDefinitions/Caen St. Etienne, Cavaille-Coll, Demo.Organ_Hauptwerk_xml");
    }

    public function xxtestImagesByPageBillerbeck() {
        $this->listImagesByPage("/Billerbeck/OrganDefinitions/Billerbeck, Fleiter Surr.Demo.Organ_Hauptwerk_xml");
    }
    
    public function testTraceEnclosures() {
        $hwd=new HWData(getenv("HOME") . "/GrandOrgue/Organs/Skinner497/OrganDefinitions/San Francisco, Skinner op. 497, Demo.Organ_Hauptwerk_xml");
        $this->assertTrue($hwd instanceof HWData);

        foreach ($hwd->enclosures() as $enclosure) {
            $dlink=$hwd->continuousControlLink($enclosure["ShutterPositionContinuousControlID"])["D"][0];
            print_r($dlink);
            $slinks=$hwd->continuousControlLink($dlink["SourceControlID"]);
            foreach($slinks["S"] as $slink) {
                $control=$hwd->continuousControl($slink["DestControlID"]);
                print_r($control);
            }
        }
    }
}