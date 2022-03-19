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
 * Representation of a GrandOrgue Manual, setting suitable default properties
 * 
 * @author andrew
 */
class Enclosure extends GOObject {
    protected string $section="Enclosure";
    
    public function __construct(string $name) {
        parent::__construct($name);
        $this->AmpMinimumLevel=20;
        Organ::Organ()->NumberOfEnclosures++;
    }  

    public function set(string $name, ?string $value): void {
        switch ($name) {
            case "MouseRectLeft":
            case "MouseRectTop":
            if (empty($value)) {
                parent::set($name, 0);
                return;
            }
        }
        parent::set($name, $value);
    }
}