<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace GOClasses;
require_once __DIR__ . "/GOObject.php";

/**
 * Representation of a GrandOrgue Panel Element
 * Usually initiated in conjunction with Panel->GUIElement() etc
 * 
 * @author andrew
 */
class PanelElement extends GOObject {
    protected string $section="PanelElement";
    
    public function __construct(string $section) {
        $this->section=$section;
        parent::__construct(); 
    }
    
    public function set(string $name, ?string $value): void {
        // According to Lars Palo, we should do this!
        if ($name=="DispLabelText" && empty($value)) {
            unset($this->DispLabelText);
            $this->TextBreakWidth=0;
            return;
        }   
        parent::set($name, $value);
    }
}