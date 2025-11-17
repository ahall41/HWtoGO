<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/Drawstop.php");

/**
 * Representation of a GrandOrgue Switch
 *
 * @author andrew
 */
class Coupler extends Drawstop {
    protected string $section="Coupler";
    public static $DispLabelColour="Dark Green";
    private static $defaults=[
        "DestinationKeyshift"=>0,
        "CoupleToSubsequentUnisonIntermanualCouplers"=>"N",
        "CoupleToSubsequentUpwardIntermanualCouplers"=>"N",
        "CoupleToSubsequentDownwardIntermanualCouplers"=>"N",
        "CoupleToSubsequentUpwardIntramanualCouplers"=>"N",
        "CoupleToSubsequentDownwardIntramanualCouplers"=>"N"
    ];
    
    public function __construct(string $name) {
        parent::__construct($name);
        $this->DispLabelColour=self::$DispLabelColour;
        $this->UnisonOff="N";
        foreach(self::$defaults as $property=>$value)
            $this->set($property, $value);
    }
    
    public function set(string $property, ?string $value) : void {
        parent::set($property, $value);
        
        if ($property=="UnisonOff" && $value=="Y") {
            foreach(self::$defaults as $property=>$value) {
                $this->unset($property);
            }
        }
        
        if (($property=="CouplerType") && 
            ($value=="Melody" || $value=="Bass")) {
            foreach(self::$defaults as $property=>$value) {
                if ($property!="DestinationKeyshift") {
                    $this->unset($property);
                }
            }
        }
    }
    
    public function Destination(Manual $manual) {
        $this->DestinationManual=intval($manual->instance());
    }
}