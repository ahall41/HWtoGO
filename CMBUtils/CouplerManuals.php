<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

require_once __DIR__ . "/../GOClasses/Organ.php";
require_once __DIR__ . "/../GOClasses/Panel.php";
require_once __DIR__ . "/../GOClasses/Manual.php";
require_once __DIR__ . "/../GOClasses/Coupler.php";
require_once __DIR__ . "/../GOClasses/Sw1tch.php";
require_once __DIR__ . "/ODF.php";

/**
 * Description of CouplerManuals
 *
 * @author andrew
 */
class CouplerManuals extends ODF {
    
    private int $mpanels=0;
    private int $mmanuals=0;
    private int $mcouplers=0;
    private int $mswitches=0;

    public static function main() {
        $p="/home/andrew/GrandOrgue/Organs";
        (new CouplerManuals("${p}/PG/Friesach/Friesach.goodf.organ"))->textBreakWidth(0);
        (new CouplerManuals("${p}/PG/Friesach/Friesach.goodf.organ"))->run(2, [1,2,3], [1,2]);
        (new CouplerManuals("${p}/PG/Cracov st John Cantius/Cracov st John Cantius.goodf.organ"))->textBreakWidth(1);
        (new CouplerManuals("${p}/PG/Cracov st John Cantius/Cracov st John Cantius.goodf.organ"))->run(2, [1,2,3], [1,3]);
        (new CouplerManuals("${p}/LP/NorrfjardenChurch/NorrfjardenChurch.organ"))->run(2, [5,6,7], [6,7]);
        (new CouplerManuals("${p}/LP/BureaChurch/BureaChurch.organ"))->run(2, [1,2,3], [1,2]);
        (new CouplerManuals("${p}/LP/BureaChurch/BureaChurchExtended.organ"))->run(2, [1,2,3], [1,3]);
    }
    
    public function run(int $manuals, array $targets,  array $defaults) {
        $organ=new GOClasses\Organ("CM");
        $organ->HasPedals="N";
        $this->scanIndex();
        $this->addCouplerManuals($manuals, $targets, $defaults);
        $this->write(str_replace(".organ", ".cm.organ", $this->sourcefile));
    }
    
    private function scanIndex() {
        foreach($this->index as $section=>$data) {
            if (substr($section,0,5)=="Panel") {
                $this->mpanels=max($this->mpanels, intval(substr($section,5)));
            }
            elseif (substr($section,0,6)=="Manual") {
                $this->mmanuals=max($this->mmanuals, intval(substr($section,6)));
            }
            elseif (substr($section,0,7)=="Coupler") {
                $this->mcouplers=max($this->mcouplers, intval(substr($section,7)));
            }
            elseif (substr($section,0,6)=="Switch") {
                $this->mswitches=max($this->mswitches, intval(substr($section,6)));
            }
        }
        echo 
            "Panels=", $this->mpanels,
            "\tManuals=", $this->mmanuals,
            "\tCouplers=", $this->mcouplers,
            "\tSwitches=", $this->mswitches,
            "\n";
    }
    
    public function addCouplerManuals(int $manuals, array $targets,  array $defaults) : void {
        $nt=sizeof($targets);
        $panel=new GOClasses\Panel("Coupler Manuals");
        $panel->instance($this->mpanels+1);
        $panel->HasPedals="N";
        $panel->DispDrawstopRows=1;
        $panel->DispDrawstopCols=2;
        $panel->DispExtraDrawstopCols=$nt;
        $panel->DispExtraDrawstopRows=$manuals;
        $panel->DispScreenSizeHoriz="Small";
        $panel->DispScreenSizeVert="Small";
        $panel->DispDrawstopBackgroundImageNum=12;
        $panel->DispConsoleBackgroundImageNum=12;
        $panel->DispKeyHorizBackgroundImageNum=12;
        $panel->DispKeyVertBackgroundImageNum=12;
        
        $couplerid=$this->mcouplers;
        $switchid=$this->mswitches;
        
        for ($mn=1; $mn<=$manuals; $mn++) {
            $manual=new GOClasses\Manual("Coupler Manual $mn");
            $manual->instance($this->mmanuals+$mn);
            $manual->Displayed="N";
            $me=$panel->GUIElement($manual);
            foreach($targets as $tn) {
                $coupler=new \GOClasses\Coupler("CM $mn to $tn");
                $coupler->instance(++$couplerid);
                $manual->Coupler($coupler);
                $coupler->DestinationManual=$tn;
                $coupler->CoupleToSubsequentUnisonIntermanualCouplers="Y";
                $coupler->CoupleToSubsequentUpwardIntermanualCouplers="Y";
                $coupler->CoupleToSubsequentDownwardIntermanualCouplers="Y";
                $coupler->CoupleToSubsequentUpwardIntramanualCouplers="Y";
                $coupler->CoupleToSubsequentDownwardIntramanualCouplers="Y";

                $switch=new \GOClasses\Sw1tch("CM $mn to $tn");
                $switch->instance(++$switchid);
                $coupler->Switch($switch);
                if ($tn==$defaults[$mn-1]) {
                    $switch->DefaultToEngaged="Y";
                    $switch->GCState=1;
                }
                $switch->StoreInDivisional="N";
                $switch->StoreInGeneral="N";
                
                $pe=$panel->GUIElement($switch);
                $pe->DispDrawstopRow=100+$manuals-$mn;
                $pe->DispDrawstopCol=$tn;
                $this->newLine("\n$coupler");
                $this->newLine("\n$switch");
                $this->newLine("\n$pe");
                $this->increment("Organ", "NumberOfSwitches", 1);
            }
            $this->newLine("\n$manual");
            $this->newLine("\n$me");
            $this->increment("Organ", "NumberOfManuals", 1);
        }
        $this->newLine("\n$panel");
        $this->increment("Organ", "NumberOfPanels", 1);
    }
    
    private function increment(string $section, string $key, int $increment) {
        $lineno=$this->index[$section][$key];
        $this->buffer[$lineno][$key]+=$increment;
    }
    
    /**
     * Fix TextBreakWidth after ODFEdit
     */
    public function textBreakWidth($panelid=0) {
        $pe=sprintf("Panel%03dElement", $panelid);
        foreach($this->index as $section=>$data) {
            if (substr($section, 0, 15)==$pe &&    
                ($this->getItem($section, "Type")=="Switch" ||
                 $this->getItem($section, "Type")=="Enclosure")  &&
                !isset($data["TextBreakWidth"])) {
                    $lineno=$this->index[$section]["Type"];
                    $this->buffer[$lineno]["TextBreakWidth"]=0;
                    echo $section, "\n"; 
                    print_r($this->buffer[$lineno]);
            }
        }
        $this->write($this->sourcefile);
    }
}

CouplerManuals::main();