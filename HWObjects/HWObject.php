<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace HWClasses;

/**
 * (Abstract) Base Class for Hauptwerk data objects
 *
 *  Implements a simple collection of values in an array
 *
 * @author andrew
 */
abstract class HWObject {
    
    protected static array $map=[];    
    protected array $data=[];
    
    /**
     * Constructor
     * 
     * @param \DOMNode $node - XML node containing data
     */
    function __construct(\DOMNode $node=NULL) {
        if ($node) {
            $this->load($node);
            
        }
    }
    
    /**
     * Load row from XML
     * 
     * @param \DOMNode $node
     * @return void
     */
    protected function load(\DOMNode $node) : void  {
        foreach($node->childNodes as $cell) {
            if ($cell->nodeType==XML_ELEMENT_NODE) {
                if (array_key_exists($nodename=$cell->nodeName, $map)) {
                    $this->set($map[$nodename], $cell->nodeValue);
                }
                else {
                    $this->set($nodename,  $cell->nodeValue);
                }
            }
        }
    }
    
    /**
     * Set a property value in the array
     * 
     * @param string $property - Property to set
     * @param mixed $value - Value (must be scalar)
     * @return void
     * @throws \Exception  - Throws an exceptio for non-scalar values
     */
    public function set(string $property, mixed $value) : void  {
        if ($value===NULL) {
            unset($this->data[$property]);
        }
        elseif (!is_scalar($value)) {
            throw new \Exception ("HW value must be a scalar");
        }
        else {
            $this->data[$property]=$value;
        }
    }

    /**
     * Alias for set($property, $value)
     */
    public function __set(string $property, mixed $value) : void {
       $this->set($property, $value);
    }
    
    /**
     * Retrieve a value from the array
     * 
     * @param  string $property - Property to retrieve
     * @param  mixed $default - default value
     * @return mixed
     */
    public function get(string $property, mixed $default=NULL) : mixed {
        if (isset($this->data[$property]))
            return $this->data[$property];
        else
            return $default;
    }

    /**
     * Alias for get()
     * 
     */
    public function __get(string $property) : mixed {
        return $this->get($property);
    }
    
    /**
     * Test if property is set
     * @param string $property
     * @return bool
     */
    public function isset(string $property) : bool {
        return array_key_exists($property, $this->data);
    }

    /**
     * Alias for isset()
     */
    public function __isset(string $property) : bool {
        return $this->isset($property);
    }

    /**
     * Unset a property value
     * 
     * @param string $property
     * @return void
     */
    public function unset(string $property) : void {
        unset($this->data[$property]);
    }

    /**
     * Alias for unset()
     */
    public function __unset(string $property) : void {
        $this->unset($property);
    }
}