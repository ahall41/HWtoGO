<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once __DIR__ . "/Button.php";

/**
 * Organ Properties
 *
 * @author andrew
 */
class Tremulant extends Button {
    protected string $section="Tremulant";
    public static $DispLabelColour="Blue";
   
    public function __construct(string $name, bool $wave=FALSE) {
        parent::__construct($name); 
        Organ::Organ()->NumberOfTremulants++;
        $this->DispLabelColour=self::$DispLabelColour;

        // Default properties
        $this->Name=$name;
        if ($wave) 
            $this->TremulantType="Wave";
        else {
            $this->Period=200;
            $this->AmpModDepth=10;
            $this->StartRate=30;
            $this->StopRate=30;
        }
    }
}
