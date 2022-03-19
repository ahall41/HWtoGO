<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace HWClasses;
require_once (__DIR__ . "/HWData.php");


/**
 * Analyse a HW Organ Definition File
 *
 * @author andrew
 */
class HWAnalyser {
    
    public HWData $hwdata;
    public $pipesused=[];
    public $ranksused=[];
    
    public static function HWAnalyser(array $args=[]) {
        global $argv;
        if (sizeof($args)==0) {
            $args=$argv;
            unset($args[0]);
        }
        foreach($args as $arg) {
            $analyser=new HWAnalyser();
            $analyser->analyse($arg);
            $analyser->showreport();
        }
    }
    
    public function analyse(string $xmlfile) {
        $this->hwdata=new HWData($xmlfile);
        $this->processSamples($this->hwdata->attacks());
        $this->processSamples($this->hwdata->releases());
    }
    
    public function showreport() : void {
        $heading=TRUE;
        foreach($this->rankSummary() as $data) {
            $this->output($data, $heading);
            $heading=FALSE;
        }
        
        $heading=TRUE;
        asort($this->pipesused);
        foreach($this->pipesused as $pipeid=>$samples) {
            foreach($samples as $sample) {
                $this->output($sample, $heading);
                $heading=FALSE;
            }
        }
    }
    
    private function processSamples(array $atrels) {
        foreach($atrels as $atrel) {
            $layer=$this->hwdata->layer($atrel["LayerID"]);
            $pipe=$this->hwdata->pipe(intval($pipeid=$layer["PipeID"]));
            $sample=$this->hwdata->sample($atrel["SampleID"]);
            $rankid=intval($pipe["RankID"]);
            
            $result=[];
            $result["PipeID"]=$pipeid;
            $result["RankID"]=$rankid;
            $result["SampleID"]=$sample["SampleID"];
            $result["PipeMidi"]=
                    (isset($pipe["NormalMIDINoteNumber"]) 
                        && !empty(trim($pipe["NormalMIDINoteNumber"])))
                    ? $pipe["NormalMIDINoteNumber"] : 0;
            $result["PipeHn"]=
                    (isset($pipe["Pitch_Tempered_RankBasePitch64ftHarmonicNum"]) 
                        && !empty(trim($pipe["Pitch_Tempered_RankBasePitch64ftHarmonicNum"])))
                    ? $pipe["Pitch_Tempered_RankBasePitch64ftHarmonicNum"] : NULL;
            $result["PipeHz"]=
                    (isset($pipe["Pitch_OriginalOrgan_PitchHz"]) 
                        && !empty(trim($pipe["Pitch_OriginalOrgan_PitchHz"])))
                    ? $pipe["Pitch_OriginalOrgan_PitchHz"] : NULL;

            $result["SampleMidi"]=
                    (isset($sample["Pitch_NormalMIDINoteNumber"]) 
                        && !empty(trim($sample["Pitch_NormalMIDINoteNumber"])))
                    ? $sample["Pitch_NormalMIDINoteNumber"] : 0;
            $result["SampleHn"]=
                    (isset($sample["Pitch_RankBasePitch64ftHarmonicNum"]) 
                        && !empty(trim($sample["Pitch_RankBasePitch64ftHarmonicNum"])))
                    ? $sample["Pitch_RankBasePitch64ftHarmonicNum"] : NULL;
            $result["SampleHz"]=
                    (isset($sample["Pitch_ExactSamplePitch"]) 
                        && !empty(trim($sample["Pitch_ExactSamplePitch"])))
                    ? $sample["Pitch_ExactSamplePitch"] : NULL;
            
            if (!array_key_exists($pipeid, $this->pipesused))
                $this->pipesused[$pipeid]=[];
            if (!array_key_exists($rankid, $this->ranksused))
                $this->ranksused[$rankid]=[];

            $this->pipesused[$pipeid][]=$result;
            $this->ranksused[$rankid][]=$pipeid;
        }
    }
    
    public function rankSummary() {
        $ranks=$this->hwdata->ranks();
        asort($ranks);
        $result=[];
        foreach($ranks as $rankid=>$rank) {
            $result[$rankid]["RankID"]=$rankid;
            $result[$rankid]["Name"]=$rank["Name"];
            if (array_key_exists($rankid, $this->ranksused)) {
                $pipes=$this->ranksused[$rankid];
                $result[$rankid]=array_merge($result[$rankid], $this->pipesSummary($pipes));
            }
        }
        return $result;
    }
    
    public function pipesSummary(array $pipes) {
        $result=["PipeMidiFr"=>999, "PipeMidiTo"=>0, "PipeHz"=>0, "PipeHn"=>0, 
            "SampleMidiFr"=>999, "SampleMidiTo"=>0, "SampleHz"=>0, "SampleHn"=>0, "Samples"=>0];
        $result["Pipes"]=sizeof($pipes);

        foreach ($pipes as $pipeid) {
            foreach($this->pipesused[$pipeid] as $sample) {
                $result["PipeMidiFr"]=min($result["PipeMidiFr"], $sample["PipeMidi"]);
                $result["PipeMidiTo"]=max($result["PipeMidiTo"], $sample["PipeMidi"]);
                $result["PipeHz"]+=(empty($sample["PipeHz"]) ? 0 : 1);
                $result["PipeHn"]+=(empty($sample["PipeHn"]) ? 0 : 1);
                $result["SampleMidiFr"]=min($result["SampleMidiFr"], $sample["SampleMidi"]);
                $result["SampleMidiTo"]=max($result["SampleMidiTo"], $sample["SampleMidi"]);
                $result["SampleHz"]+=(empty($sample["SampleHz"]) ? 0 : 1);
                $result["SampleHn"]+=(empty($sample["SampleHn"]) ? 0 : 1);
                $result["Samples"]++;
            }
        }
        return $result;
    }
    
    public function showPipe(int $pipeid) : void {
        $hwd=$this->hwdata;
        $pipe=$hwd->pipe($pipeid);
        $rank=$hwd->rank($rankid=$pipe["RankID"]);
        echo "Pipe $pipeid, rank $rankid (",  $rank["Name"], ")\n";
        foreach (array_merge($hwd->rankStop($rankid), $hwd->altRankStop($rankid)) as $rankstop) {
            $stop=$hwd->stop($rankstop["StopID"]);
            echo "\tStop ", $stop["StopID"], " (", $stop["Name"], ")\n";
        }
        foreach ([TRUE, FALSE] as $attack) {
            foreach(($attack ? $hwd->attacks() : $hwd->releases()) as $atrel) {
                $layer=$hwd->layer($atrel["LayerID"]);
                if ($layer["PipeID"]==$pipeid) {
                    $sample=$hwd->sample($atrel["SampleID"]);
                    echo "\t\tLayer ", $atrel["LayerID"], " file ", $sample["SampleFilename"], "\n";
                }
            }
        }
    }

    private function output(array $data, bool $heading, string $separator="\t") {
        if ($heading) {
            foreach($data as $property=>$value) 
                echo $property, $separator;
            echo "\n";
        }
        foreach($data as $property=>$value) 
            echo $value, $separator;
        echo "\n";
    }
    
    /**
     * Trace image file usage
     * 
     * @param string $filter: File name filter - a regular expression 
     */
    public function traceImage(string $filter) : void {
        $hwd=$this->hwdata;
        $layouts=["ImageSetInstanceID", "AlternateScreenLayout1_ImageSetID",
            "AlternateScreenLayout2_ImageSetID", "AlternateScreenLayout3_ImageSetID"];
        foreach ($hwd->imageSetElements() as $setid=>$elements) {
            foreach ($elements as $element) {
                if (preg_match($filter, strtolower($element["BitmapFilename"]))==1) {
                    $set=$hwd->imageSet($setid);
                    echo $setid;
                    foreach ($hwd->imageSetInstances() as $instanceid=>$instance) {
                        if ($instance["ImageSetID"]==$setid) {
                            echo " | ", $instance["ImageSetInstanceID"], " $setid ", 
                                 $instance["DisplayPageID"], " ",
                                 isset($instance["AlternateScreenLayout2_ImageSetID"]) ? $instance["AlternateScreenLayout2_ImageSetID"] : "-", " ",
                                 isset($instance["AlternateScreenLayout2_ImageSetID"]) ? $instance["AlternateScreenLayout2_ImageSetID"] : "-", " ",
                                 isset($instance["AlternateScreenLayout3_ImageSetID"]) ? $instance["AlternateScreenLayout3_ImageSetID"] : "-", " ";
                            foreach($hwd->switches() as $switch) {
                                if (isset($switch["Disp_ImageSetInstanceID"])
                                        && $switch["Disp_ImageSetInstanceID"]==$instanceid)
                                    echo $switch["Disp_ImageSetInstanceID"], " ";
                            }
                            echo $instance["Name"];
                        }
                    }
                    echo " ", $set["Name"], " ",
                         $element["BitmapFilename"], "\n";
                    break;
                }
            }
        }
    }
}