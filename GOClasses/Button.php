<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/GOObject.php");

/**
 * Representation of a GrandOrgue Button
 *
 * @author andrew
 */
class Button extends GOObject {
    protected string $section="Button";

    public function posRC(int $row, int $col) : void {
        $this->Displayed="Y";
        $this->DispButtonCol=$col;
        $this->DispButtonRow=$row;
    }
    
    public function posXY(int $x, int $y) {
        $this->Displayed="Y";
        $this->PositionX=$x;
        $this->PositionY=$y;
    }

    public function set(string $name, ?string $value): void {
        // According to Lars Palo, we should do this!
        if ($name=="DispLabelText" && empty($value)) {
            unset($this->DispLabelText);
            $this->TextBreakWidth=0;
            return;
        }

        parent::set($name, $value);
        if ($name=="Displayed" && $value="N") {
            unset($this->DispLabelText);
            unset($this->DispLabelColour);
        }
    }
}