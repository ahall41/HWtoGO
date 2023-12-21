<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Analyser;

require_once (__DIR__ . "/../HWClasses/HWData.php");
require_once (__DIR__ . "/../GOClasses/Organ.php");
require_once (__DIR__ . "/../GOClasses/Pipe.php");
require_once (__DIR__ . "/../GOClasses/Panel.php");
require_once (__DIR__ . "/../GOClasses/PanelElement.php");


/**
 * Analyse a HW Organ Definition File
 * I am going to adopt a bottom up approach. First we will investigate
 * fundamental objects (pipes and images) and then work backwards to see how they 
 * fit in with the rest of the model 
 *
 * @author andrew
 */
class HWAnalyser {
    
    private \HWClasses\HWData $hwd;
    private $pipes=[];
    private $panels=[];
    private $organ=NULL;
    
    const ROOT="/GrandOrgue/Organs/";
    
    /**
     * Constructor
     */
    public function __construct(string $xml) {
        $source=getenv("HOME") . self::ROOT . $xml;
        $this->hwd=new \HWClasses\HWData($source);
        echo "Analysing $xml\n";
        //$general=$this->hwd->general();
        //$organ=new \GOClasses\Organ($general["Identification_Name"]);
        //$this->samples();
        //$this->buildpanels();
        //$this->switchImages();
        //$this->stops();
        //$this->tremulants();
        //$this->manuals();
        //$this->windcompartments();
        //$this->layers();
        //$this->enclosureImages();
        $this->imageSets();
    }
    
    /**
     * Build panels for later use
     */
    private function buildpanels() : void {
        $this->panels=[];
        foreach ($this->hwd->displayPages() as $pageid=>$page) {
            $this->panels[$pageid][0]=new \GOClasses\Panel($page["Name"]);
            foreach([1,2,3] as $l) {
                if (isset($page["AlternateConsoleScreenLayout${l}_Include"]) 
                       && $page["AlternateConsoleScreenLayout${l}_Include"]=="Y") {
                    $panel=$this->panels[$pageid][$l]=new \GOClasses\Panel($page["Name"]);
                    $panel->Group="Alternate Layout $l";
                }
            }
        }
    }


    /**
     * Extract image filename
     * 
     * @param int $setid
     * @param int $index
     * @return string
     */
    private function getImage(int $setid, int $index) : ? string {
        $set=$this->hwd->imageSet($setid, TRUE);
        if (empty($set)) {
            echo "Missing imageSet: $setid\n";
            return NULL;
        }
        $package=$set["InstallationPackageID"];
        $elements=$this->hwd->imageSetElement($setid, TRUE);
        if (empty($elements)) {;
            echo "Missing imageSetElements: $setid\n";
            return NULL;
        }
        if (isset($elements[$index]["BitmapFilename"]) 
                && !empty($elements[$index]["BitmapFilename"])) {
            $filename=$elements[$index]["BitmapFilename"];
            return "InstallationPackages/$package/$filename";
        }
        else {
            echo "Missing imageSetElement: ${setid}[${index}]\n";
            return NULL;
        }
    }

    
    /**
     * Helper function to copy HW Data to GO if set and not empty
     * @param \GOClasses\GOBase $object
     * @param array $data
     * @param string $source
     * @param string $dest
     * @return void
     */
    private function map (\GOClasses\GOBase $object, array $data, string $source, string $dest) : void {
        if (isset($data[$source]) && $data[$source]!=="") 
            $object->set($dest, $data[$source]);
    }
    
    /**
     * Find associated links for a switch
     * 
     * @param string $type
     * @param int $switchid
     * @param array $result
     * @return void
     */
    private function switchLinks(string $type, int $switchid, array &$result) : void {
        static $types=[
            "S"=>"DestSwitchID",
            "D"=>"SourceSwitchID"
        ];
        $attribute=$types[$type];
        $links=$this->hwd->switchLink($switchid);
        if (isset($links[$type])) {
            foreach($links[$type] as $link) {
                if (isset($link[$attribute])) {
                    if (!isset($result[$link[$attribute]])) {
                        $result[$link[$attribute]]=TRUE;
                        $this->switchLinks($type, $link[$attribute], $result);
                    }
                }
            }
        }
    }

    /**
     * Find associated continuous controls for a control
     * 
     * @param string $type
     * @param int $switchid
     * @param array $result
     * @return void
     */
    private function ccLinks(string $type, int $ccid, array &$result) : void {
        static $types=[
            "S"=>"DestControlID",
            "D"=>"SourceControlID"
        ];
        $attribute=$types[$type];
        $links=$this->hwd->continuousControlLink($ccid);
        if (isset($links[$type])) {
            foreach($links[$type] as $link) {
                if (isset($link[$attribute])) {
                    if (!isset($result[$link[$attribute]])) {
                        $result[$link[$attribute]]=TRUE;
                        $this->ccLinks($type, $link[$attribute], $result);
                    }
                }
            }
        }
    }
    
    /**
     * Analyse samples, and map where possible
     * - Percussive
     * - AmplitudeLevel (N/A - use Gain)
     * + Gain
     * - TrackerDelay
     * - LoadRelease (N/A - derived from presence of release samples)
     * - AttackVelocity
     * - MaxTimeSinceLastRelease
     * - IsTremulant 
     * - MaxKeyPressTime
     * + HarmonicNumber
     * + MIDIKeyNumber (in GO this will define its position in the rank)
     * - PitchCorrection
     * - AcceptsRetuning
     * - WindchestGroup
     * - MinVelocityVolume
     * - MaxVelocityVolume
     * + Attack999
     * - Attack999LoadRelease (N/A - derived from presence of release samples)
     * - Attack999AttackVelocity
     * - Attack999IsTremulant **
     * + Attack999MaxTimeSinceLastRelease
     * + Release999
     * - Release999IsTremulant **
     * + Release999MaxKeyPressTime
     * + LoopCrossfadeLength
     * - ReleaseCrossfadeLength
     * 
     * We will assume that start/end/cue point etc are defined correctly in each sample!
     */
    
    private function mapattack(\GOClasses\Pipe $pipe, array $attackdata, 
            array $layerdata, array $pipedata, array $sampledata ) : void {
        $paths=explode("/", $sampledata["SampleFilename"]);
            $midi=intval($paths[array_key_last($paths)]);
            if ($midi>=0 && $midi<=127) $pipe->MIDIKeyNumber=$midi;
        $pipe->Attack="OrganInstallationPackages/"
                . $sampledata["InstallationPackageID"] . "/"
                . $sampledata["SampleFilename"];
        $this->map($pipe, $attackdata, "LoopCrossfadeLengthInSrcSampleMs", "LoopCrossfadeLength");
        $this->map($pipe, $layerdata, "AmpLvl_LevelAdjustDecibels", "Gain");
        $this->map($pipe, $layerdata, "AttackSelCriteria_MinTimeSincePrevPipeCloseMs", "AttackMaxTimeSinceLastRelease");
        $this->map($pipe, $pipedata, "NormalMIDINoteNumber", "MIDIKeyNumber");
        $this->map($pipe, $sampledata, "Pitch_NormalMIDINoteNumber", "MIDIKeyNumber");
        $this->map($pipe, $pipedata, "Pitch_Tempered_RankBasePitch64ftHarmonicNum", "HarmonicNumber");
        $this->map($pipe, $sampledata, "Pitch_RankBasePitch64ftHarmonicNum", "HarmonicNumber");
    }

    private function maprelease(\GOClasses\Pipe $pipe, array $releasedata, 
            array $layerdata, array $pipedata, array $sampledata ) : void {
        $pipe->Release="OrganInstallationPackages/"
                . $sampledata["InstallationPackageID"] . "/"
                . $sampledata["SampleFilename"];
        $this->map($pipe, $releasedata, "ReleaseCrossfadeLengthMs", "ReleaseCrossfadeLength");
        $this->map($pipe, $releasedata, "ReleaseSelCriteria_LatestKeyReleaseTimeMs", "ReleaseMaxKeyPressTime");
    }
    
    private function dosample(array $atrdata, bool $isAttack) : void {
        $layerdata=$this->hwd->layer($atrdata["LayerID"]);
        
        $pipedata=$this->hwd->pipe($pipeid=$layerdata["PipeID"]);
        $sampledata=$this->hwd->sample($atrdata["SampleID"]);
        
        $pipe=isset($this->pipes[$pipeid])
                ? $this->pipes[$pipeid]
                : $this->pipes[$pipeid]=new \GOClasses\Pipe();

        $isAttack
            ? $this->mapattack($pipe, $atrdata, $layerdata, $pipedata, $sampledata)
            : $this->maprelease($pipe, $atrdata, $layerdata, $pipedata, $sampledata);
    }

    private function samples() {
        foreach($this->hwd->attacks() as $attack) {
            $this->dosample($attack, TRUE);
        }
        foreach($this->hwd->releases() as $release) {
            $this->dosample($release, FALSE);
        }
        
        // Each pipe needs a midi note (apart from ambient noises?) and a filename
        foreach($this->pipes as $pipeid=>$pipe) {
            $attack=$pipe->Attack;
            if ($pipe->MIDIKeyNumber==0)
                echo "Missing MIDI Key in pipe $pipeid ($attack)\n";
            if ($pipe->AttackCount<0)
                echo "Missing Attack in pipe $pipeid ($attack)\n";
        }
    }
    
    /**
     * Analyse switch images.
     */
    private function switchImages() {
        $instances=[];
        foreach($this->hwd->switches() as $switch) {
            if (isset($switch["Disp_ImageSetInstanceID"]) &&
                    !empty($switch["Disp_ImageSetInstanceID"])) {
                $instanceid=$switch["Disp_ImageSetInstanceID"];
                $instances[$instanceid]=TRUE;
                $instance=$this->hwd->imageSetInstance($instanceid);
                foreach([0=>"",
                         1=>"AlternateScreenLayout1_",
                         2=>"AlternateScreenLayout2_",
                         3=>"AlternateScreenLayout3_"] as $l=>$lv) {
                    if (isset($instance["${lv}ImageSetID"]) &&
                            !empty($instance["${lv}ImageSetID"])) {
                        $setid=$instance["${lv}ImageSetID"];
                        $pageid=$instance["DisplayPageID"];
                        if (isset($this->panels[$pageid][$l])) {
                            $panel=$this->panels[$pageid][$l];
                            $pi=$panel->instance();
                            $pe=new \GOClasses\PanelElement("Panel${pi}Element");
                            $pe->Type="Switch";
                            $this->map($pe, $instance, "${lv}LeftXPosPixels", "PositionX");
                            if (!isset($pe->PositionX))
                                echo "Missing PositionX in instance ${instanceid}[${l}]\n";
                            $this->map($pe, $instance, "${lv}TopYPosPixels", "PositionY");
                            if (!isset($pe->PositionY))
                                echo "Missing PositionY in instance ${instanceid}[${l}]\n";
                            $pe->ImageOn=$this->getImage($setid, $switch["Disp_ImageSetIndexEngaged"]);
                            $pe->ImageOff=$this->getImage($setid, $switch["Disp_ImageSetIndexDisengaged"]);
                        } else {
                            echo "Missing panel ${pageid}[${l}]\n";
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Analyse tremulants. In HW tremulants are allocated to each pipe.
     * In GO, they are allocated to Windchests, however we can set the
     * windchest explicitly for each pipe?
     * 
     * @todo
     */
    private function tremulants() {
        
    }
    
    /**
     * Analyse manuals (aka Keyboards)
     */
    private function manuals() {
        $keyboardkeys=$this->hwd->keyboardKeys();
        foreach($this->hwd->keyboards() as $kbdid=>$keyboard) {
            foreach(
                [0=>"KeyGen_",
                 1=>"KeyGen_AlternateScreenLayout1_",
                 2=>"KeyGen_AlternateScreenLayout2_",
                 3=>"KeyGen_AlternateScreenLayout3_"] as $l=>$lv) {
                $k = $lv . "KeyImageSetID";
                if (isset($keyboard[$k]) && !empty($keyboard[$k])) {
                    foreach($keyboardkeys as $keyboardkey) {
                        if ($keyboardkey["KeyboardID"]==$kbdid) {
                                echo "Keyboard $kbdid layout $l has key image set and keyboard keys\n";
                                break;
                        }
                    }
                    $keyimages=$this->hwd->keyImageSet($keyboard[$k], TRUE);
                    if (sizeof($keyimages)==0)
                        echo "Keyboard $kbdid layout $l has missing key image set\n";
                }
                else {
                    $keycount=0;
                    foreach($keyboardkeys as $keyboardkey) {
                        if ($keyboardkey["KeyboardID"]==$kbdid) {
                            $keycount++;
                            $switch=$this->hwd->switch($keyboardkey["SwitchID"], TRUE);
                            if ($switch) {
                                $id=intval($switch["Disp_ImageSetInstanceID"]);
                                $imageset=$this->hwd->imageSetInstance($id, TRUE);
                                if ($imageset==NULL) {
                                    echo "Missing image set for switch ", $keyboardkey["SwitchID"], " in keyboard $kbdid\n";
                                }
                            }
                            else {
                                echo "Missing switch", $keyboardkey["SwitchID"], " in keyboard $kbdid\n";
                            }
                        }
                    }
                    if ($keycount==0) {
                        echo "Missing key images for keyboard $kbdid\n";
                    }
                }
            }
            if (!isset($keyboard["Hint_PrimaryAssociatedDivisionID"]) || 
                    empty($keyboard["Hint_PrimaryAssociatedDivisionID"])) {
                echo "Missing primary division hint keyboard $kbdid\n";
            }
        }
    }

    /**
     * Analyse switches by destination index
     * @param array $stop
     * @return void
     */
    private function destSwitches(array $stop) : void {
        $switchid=$stop["ControllingSwitchID"];
        $links=[];
        $this->switchLinks("D", $switchid, $links);
        if (sizeof($links) > 0) {
            $links[$switchid]=TRUE;
            foreach($links as $linkid=>$dummy) {
                $switch=$this->hwd->switch($linkid);
                // If it has an image we can then display it ...
            }
        }
    }

    /**
     * Analyse stops. 
     */
    private function stops() {
        foreach($this->hwd->stops() as $stopid=>$stop) {
            $this->destSwitches($stop);
            $ranks=$this->hwd->stopRank($stopid);
            if (sizeof($ranks)==0) {
                echo "No ranks for stop $stopid ($stop[Name])\n";
            }
        }
    }

    /**
     * Analyse wind compartments. 
     */
    private function windcompartments() {
        // SELECT DISTINCT WindSupply_SourceWindCompartmentID, EnclosureID
        // FROM Pipe_SoundEngine01
        // LEFT JOIN EcnlosurePipe ON PipeID
        $source=[];
        foreach ($this->hwd->pipes() as $pipeid=>$pipe) {
            foreach($this->hwd->enclosurePipe($pipeid, TRUE) as $ep) {
                if (isset($ep["EnclosureID"]) && !empty($ep["EnclosureID"]))
                    $eid=$ep["EnclosureID"];
                else 
                    $eid=0;
                $source[$pipe["WindSupply_SourceWindCompartmentID"]][$eid]=$eid;
            }
        }
        
        foreach ($source as $id=>$enclosures) {
            $compartment=$this->hwd->windCompartment($id);
            echo $compartment["Name"], ":";
            foreach ($enclosures as $eid) {
                if ($eid>0) {
                    echo " ", $this->hwd->enclosure($eid)["Name"];
                }
            }
            echo "\n";
        }
    }
    
    /**
     * Analyse layers
     */
    private function layers() {
        $layers=[];
        foreach ($this->hwd->layers() as $layer) {
            if (isset($layer["PipeLayerNumber"])) {
                $ln=$layer["PipeLayerNumber"];
                if (!empty($ln)) $layers[$ln]=$ln;
            }
        }
        if (sizeof($layers)>0) {
            echo "Layers used:";
            foreach ($layers as $ln)
                echo " $ln";
            echo "\n";
        }
    }
    
    /**
     * Analyse enclosure [images]
     */
    private function enclosureImages() {
        foreach ($this->hwd->enclosures() as $enclosure) {
            $ctrlid=$enclosure["ShutterPositionContinuousControlID"];
            $links=[];
            $this->ccLinks("D", $ctrlid, $links);
            $hasimage=FALSE;
            $links[$ctrlid]=TRUE;
            foreach($links as $linkid=>$dummy) {
                $cc=$this->hwd->continuousControl($linkid);
                if (isset($cc["ImageSetInstanceID"]) &&
                        !empty($cc["ImageSetInstanceID"])) {
                    $hasimage=TRUE;
                    break;
                }
            }
            if (!$hasimage) {
                echo "No image[s] for enclosure ", $enclosure["Name"], "\n";
            }
        }
    }
    
    /**
     * Analyse images - MouseRect vs image size
     */
    private function imageSets() {
        foreach ($this->hwd->imageSets() as $imageSet) {
            if (array_key_exists("ImageWidthPixels", $imageSet) &&
                array_key_exists("ClickableAreaRightRelativeXPosPixels", $imageSet)) {
                $width=intval($imageSet["ClickableAreaRightRelativeXPosPixels"]);
                if (array_key_exists("ClickableAreaLeftRelativeXPosPixels", $imageSet)) {
                        $width-=intval($imageSet["ClickableAreaLeftRelativeXPosPixels"]);
                }
                if ($width!=$imageSet["ImageWidthPixels"]) {
                    echo $imageSet["ImageSetID"], "\twidth ",$width, "!=", $imageSet["ImageWidthPixels"], "\t",
                            $imageSet["Name"], "\n";
                }
            }
            
            if (array_key_exists("ImageHeightPixels", $imageSet) &&
                array_key_exists("ClickableAreaRightRelativeYPosPixels", $imageSet)) {
                $height=intval($imageSet["ClickableAreaRightRelativeYPosPixels"]);
                if (array_key_exists("ClickableAreaLeftRelativeYPosPixels", $imageSet)) {
                        $height-=intval($imageSet["ClickableAreaLeftRelativeYPosPixels"]);
                }
                if ($height!=$imageSet["ImageHeightPixels"]) {
                    echo $imageSet["ImageSetID"], "\theight ", $height, "!=", $imageSet["ImageHeightPixels"], "\t",
                            $imageSet["Name"], "\n";
                }
            }
        }
    }
}

new HWAnalyser("AV/Bakats/OrganDefinitions/Bakats semidry_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Bakats/OrganDefinitions/Bakats surround_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Bakats/OrganDefinitions/Bakats wet_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Boszormeny/OrganDefinitions/Boszormeny far_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Boszormeny/OrganDefinitions/Boszormeny near_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Boszormeny/OrganDefinitions/Boszormeny surround_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/BudaHills/OrganDefinitions/Lutheran Buda_far.Organ_Hauptwerk_xml");
new HWAnalyser("AV/BudaHills/OrganDefinitions/Lutheran Buda_near.Organ_Hauptwerk_xml");
new HWAnalyser("AV/BudaHills/OrganDefinitions/Lutheran Buda_surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Budapest_Castilian/OrganDefinitions/Castilian Budapest semidry_extended_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Budapest_Castilian/OrganDefinitions/Castilian Budapest semidry orig_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Budapest_Castilian/OrganDefinitions/Castilian Budapest surround extended_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Budapest_Castilian/OrganDefinitions/Castilian Budapest surround original_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Budapest_Castilian/OrganDefinitions/Castilian Budapest wet_extended_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Budapest_Castilian/OrganDefinitions/Castilian Budapest wet orig_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Budapest_HS/OrganDefinitions/Holy Spirit Budapest_v1_2_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Csaszar/OrganDefinitions/Luber of Csaszar extend v2.0_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Csaszar/OrganDefinitions/Luber of Csaszar orig v2.0_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Dreischor/OrganDefinitions/Dreischor far.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Dreischor/OrganDefinitions/Dreischor near.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Dreischor/OrganDefinitions/Dreischor surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Esztergom/OrganDefinitions/Rieger-Esztergom dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Esztergom/OrganDefinitions/Rieger-Esztergom surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Esztergom/OrganDefinitions/Rieger-Esztergom wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Farkasret/OrganDefinitions/Italian Baroque Organ Budapest far_native_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Farkasret/OrganDefinitions/Italian Baroque Organ Budapest far reverb_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Farkasret/OrganDefinitions/Italian Baroque Organ Budapest near_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Farkasret/OrganDefinitions/Italian Baroque Organ Budapest sur native_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Farkasret/OrganDefinitions/Italian Baroque Organ Budapest sur reverb_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Fehervar/OrganDefinitions/Angster Cistercian extended demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Fehervar/OrganDefinitions/Angster Cistercian orig demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Gelence/OrganDefinitions/Gelence extended.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Gelence/OrganDefinitions/Gelence original.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Geneva/OrganDefinitions/Grenzing-Geneva four-channels.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Geneva/OrganDefinitions/Grenzing-Geneva orig.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Hajos/OrganDefinitions/Hajos_extend_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Hajos/OrganDefinitions/Hajos_orig_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Harmonium/OrganDefinitions/Dutch Harmonium extend.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Harmonium/OrganDefinitions/Dutch Harmonium orig.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Hermance/OrganDefinitions/Mascioni-Hermance_extend_semidry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Hermance/OrganDefinitions/Mascioni-Hermance_extend_surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Hermance/OrganDefinitions/Mascioni-Hermance_extend_wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Hermance/OrganDefinitions/Mascioni-Hermance_original_semidry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Hermance/OrganDefinitions/Mascioni-Hermance_original_surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Hermance/OrganDefinitions/Mascioni-Hermance_original_wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Jak/OrganDefinitions/Jak-Angster.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Kanta/OrganDefinitions/Kanta_dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Kanta/OrganDefinitions/Kanta_surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Kanta/OrganDefinitions/Kanta_wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Kolonics_Brasov/OrganDefinitions/Kolonics Brasov far.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Kolonics_Brasov/OrganDefinitions/Kolonics Brasov lite.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Kolonics_Brasov/OrganDefinitions/Kolonics Brasov near.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Kolonics_Brasov/OrganDefinitions/Kolonics Brasov Surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Komarom/OrganDefinitions/Bells from Komarom.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Kormend_Catholic/OrganDefinitions/Kormend_Catholic_Angster_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Kormend_Lutheran/OrganDefinitions/Angster Lutheran Kormend_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Lemmer/OrganDefinitions/Lemmer far (lite).Organ_Hauptwerk_xml");
new HWAnalyser("AV/Lemmer/OrganDefinitions/Lemmer far.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Lemmer/OrganDefinitions/Lemmer near (lite).Organ_Hauptwerk_xml");
new HWAnalyser("AV/Lemmer/OrganDefinitions/Lemmer near.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Lemmer/OrganDefinitions/Lemmer surround (lite).Organ_Hauptwerk_xml");
new HWAnalyser("AV/Lemmer/OrganDefinitions/Lemmer surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Lorris/OrganDefinitions/Lorris dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Lorris/OrganDefinitions/Lorris_wet_and_dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Lorris/OrganDefinitions/Lorris_wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Luins/OrganDefinitions/Luins_dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Luins/OrganDefinitions/Luins_surround .Organ_Hauptwerk_xml");
new HWAnalyser("AV/Luins/OrganDefinitions/Luins_wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Mauracher/OrganDefinitions/Mauracher demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Monor/OrganDefinitions/Rieger Organ of Monor ext. demo v2.0.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Monor/OrganDefinitions/Rieger Organ of Monor orig. demo V2.0 .Organ_Hauptwerk_xml");
new HWAnalyser("AV/Nagyrakos/OrganDefinitions/Nagyrakos dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Nagyrakos/OrganDefinitions/Nagyrakos wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/New Haven/OrganDefinitions/GhentCarillon.Organ_Hauptwerk_xml");
new HWAnalyser("AV/New Haven/OrganDefinitions/Redemeer Aeolian-Skinner_surround extend.Organ_Hauptwerk_xml");
new HWAnalyser("AV/New Haven/OrganDefinitions/Redemeer Aeolian-Skinner_surround orig.Organ_Hauptwerk_xml");
new HWAnalyser("AV/New Haven/OrganDefinitions/Redemeer Aeolian-Skinner_wet_r extend.Organ_Hauptwerk_xml");
new HWAnalyser("AV/New Haven/OrganDefinitions/Redemeer Aeolian-Skinner_wet_r orig.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Rumpt/OrganDefinitions/Rumpt_demo far.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Rumpt/OrganDefinitions/Rumpt_demo near.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Rumpt/OrganDefinitions/Rumpt_demo surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Savaria/OrganDefinitions/Savaria-semidry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Savaria/OrganDefinitions/Savaria-surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Savaria/OrganDefinitions/Savaria-wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Sepsiszentgyorgy/OrganDefinitions/Ziegler dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Sepsiszentgyorgy/OrganDefinitions/Ziegler surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Sepsiszentgyorgy/OrganDefinitions/Ziegler wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Solignac/OrganDefinitions/Solignac extend.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Solignac/OrganDefinitions/Solignac orig.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Stahlhuth/OrganDefinitions/Stahlhuth_positive_ext_dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Stahlhuth/OrganDefinitions/Stahlhuth_positive_ext_four_channels.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Stahlhuth/OrganDefinitions/Stahlhuth_positive_ext_wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Stahlhuth/OrganDefinitions/Stahlhuth_positive_orig_dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Stahlhuth/OrganDefinitions/Stahlhuth_positive_orig_wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szentgotthard/OrganDefinitions/Szentgotthard dry_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szentgotthard/OrganDefinitions/Szentgotthard surround_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szentgotthard/OrganDefinitions/Szentgotthard wet_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szikszo/OrganDefinitions/Klais v2 surround_extend.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szikszo/OrganDefinitions/Klais v2 surround original.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szikszo/OrganDefinitions/Klais v2 wet_extend.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szikszo/OrganDefinitions/Klais v2 wet original.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szikszo/OrganDefinitions/Klais w2 semidry_extend.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szikszo/OrganDefinitions/Klais w2 semidry_orig.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szombathely_Eule/OrganDefinitions/Eule_Szombathely_dry_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szombathely_Eule/OrganDefinitions/Eule_Szombathely_limited_wet_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szombathely_Eule/OrganDefinitions/Eule_Szombathely_wet and surround1_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szombathely_Eule/OrganDefinitions/Eule_Szombathely_wet and surround2_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szombathely_Rieger/OrganDefinitions/Rieger-Szhely_demo_dry.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szombathely_Rieger/OrganDefinitions/Rieger-Szhely_demo_surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Szombathely_Rieger/OrganDefinitions/Rieger-Szhely_demo_wet.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Ujpest/OrganDefinitions/Ujpest semidry_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Ujpest/OrganDefinitions/Ujpest surround_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Ujpest/OrganDefinitions/Ujpest wet_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Urk/OrganDefinitions/Urk - Kerk aan de Zee-far.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Urk/OrganDefinitions/Urk - Kerk aan de Zee-near.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Urk/OrganDefinitions/Urk - Kerk aan de Zee-surround.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Vasvar/OrganDefinitions/Vasvar.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Zalalov/OrganDefinitions/Zalalovo extended_demo.Organ_Hauptwerk_xml");
new HWAnalyser("AV/Zalalov/OrganDefinitions/Zalalovo orig_demo.Organ_Hauptwerk_xml");
new HWAnalyser("PG/Alessandria/OrganDefinitions/Alessandria (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/Begard/OrganDefinitions/Begard (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/Chapelet/OrganDefinitions/Chapelet Spanish Collection DEMO.Organ_Hauptwerk_xml");
new HWAnalyser("PG/Chorzow/OrganDefinitions/Chorzow, sw Jadwiga Slaska (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/DurenFull/OrganDefinitions/Duren.Organ_Hauptwerk_xml");
new HWAnalyser("PG/Duren/OrganDefinitions/Duren (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/Erfurt Bussleben/OrganDefinitions/Erfurt Bussleben (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/Erfurt Predigerkirche/OrganDefinitions/Erfurt Predigerkirche (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/ErmeloFull/OrganDefinitions/Ermelo.Organ_Hauptwerk_xml");
new HWAnalyser("PG/Ermelo/OrganDefinitions/Ermelo (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/Friesach/OrganDefinitions/Friesach.Organ_Hauptwerk_xml");
new HWAnalyser("PG/Goch/OrganDefinitions/Goch (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/Nancy/OrganDefinitions/Nancy (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/NitraFull/OrganDefinitions/Nitra.Organ_Hauptwerk_xml");
new HWAnalyser("PG/Nitra/OrganDefinitions/Nitra (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/Obervellach/OrganDefinitions/Obervellach (demo).Organ_Hauptwerk_xml");
new HWAnalyser("PG/Oloron/OrganDefinitions/Oloron-Sainte-Marie.Organ_Hauptwerk_xml");
new HWAnalyser("PG/SwietaLipka/OrganDefinitions/Swieta Lipka (demo).Organ_Hauptwerk_xml");
new HWAnalyser("SP/Billerbeck/OrganDefinitions/Billerbeck, Fleiter Surr.Demo.Organ_Hauptwerk_xml");
new HWAnalyser("SP/Buckeburg/OrganDefinitions/Buckeburg, Janke Organ, Surround Demo.Organ_Hauptwerk_xml");
new HWAnalyser("SP/BurtonBerlinFull/OrganDefinitions/Burton-Berlin Hill Surround.Organ_Hauptwerk_xml");
new HWAnalyser("SP/Casavant/OrganDefinitions/Bellevue, Casavant Demo.Organ_Hauptwerk_xml");
new HWAnalyser("SP/Doesburg/OrganDefinitions/Doesburg, St. Martini, Walcker, DEMO.Organ_Hauptwerk_xml");
new HWAnalyser("SP/LuedingworthDemo/OrganDefinitions/Luedingworth Demo.Organ_Hauptwerk_xml");
new HWAnalyser("SP/LuedingworthDemo/OrganDefinitions/Luedingworth Surround.Organ_Hauptwerk_xml");
new HWAnalyser("SP/LuedingworthFull/OrganDefinitions/Luedingworth Demo.Organ_Hauptwerk_xml");
new HWAnalyser("SP/LuedingworthFull/OrganDefinitions/Luedingworth Surround.Organ_Hauptwerk_xml");
new HWAnalyser("SP/Rotterdam/OrganDefinitions/Rotterdam - Laurenskerk, Hoofdorgel DEMO.Organ_Hauptwerk_xml");
new HWAnalyser("SP/Segovia/OrganDefinitions/Segovia, Echevarria Organ, Demo.Organ_Hauptwerk_xml");
new HWAnalyser("SP/Skinner497/OrganDefinitions/San Francisco, Skinner op. 497, Demo.Organ_Hauptwerk_xml");
new HWAnalyser("VN/Wildervank/OrganDefinitions/Wildervank.Organ.Hauptwerk.xml");
