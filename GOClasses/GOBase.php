<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace GOClasses;

/**
 * (Abstract) Base Class for GrandOrgue data objects
 *
 *  Implements a simple collection of values in an array
 *
 * @author andrew
 */
abstract class GOBase {
    
    protected $data=[]; // 
    
    /**
     * Set a property value in the array
     * 
     * @param string $property - Property to set
     * @param mixed $value - Value (must be scalar)
     * @return void
     * @throws \Exception  - Throws an exceptio for non-scalar values
     */
    public function set(string $property, ?string $value) : void  {
        if ($value===NULL)
            unset($this->data[$property]);
        elseif (!is_scalar($value))
            throw new \Exception ("GO value must be a scalar");
        else
            $this->data[$property]=$value;
    }

    /**
     * Alias for __set($property, $value)
     */
    public function __set(string $property, $value) : void {
       $this->set($property, $value);
    }
    
    /**
     * Retrieve a value from the array
     * 
     * @param  string $property - Property to retrieve
     * @param  mixed $default - default value
     * @return mixed
     */
    public function get(string $property, ?string $default=NULL) : ? string {
        if (isset($this->data[$property]))
            return $this->data[$property];
        else
            return $default;
    }

    /**
     * Alias for get()
     * 
     */
    public function __get(string $property) {
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
    
    /**
     * Concatenate properties as "Property=Value"
     * 
     * @return string
     */
    public function __toString() : string {
        $result="";
        foreach ($this->data as $property => $value) 
            $result .= "$property=$value\n";
        return $result;
    }
    
    /**
     * Integer to string conversion, with leading zeroes
     * 
     * @param int $integer
     * @param string $format
     * @return string
     */
    public static function int2str(int $integer, string $format="%03d") : string {
        return sprintf($format, $integer);
    }
}
