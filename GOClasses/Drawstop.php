<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/Button.php");
/**
 * Representation of a GrandOrgue Drawstop
 *
 * @author andrew
 */
class Drawstop extends Button {
    protected string $section="Drawstop";

    public function __construct(string $name) {
        parent::__construct($name);
        $this->DefaultToEngaged="N";
    } 
    
    public function posRC(int $row, int $col) : void {
        $this->Displayed="Y";
        $this->DispDrawstopCol=$col;
        $this->DispDrawstopRow=$row;
    }
    
}
