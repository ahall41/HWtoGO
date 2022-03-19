<?php
/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */
namespace GOClasses;
require_once(__DIR__ . "/GOBase.php");

/**
 * (Abstract) GrandOrgue Data Object
 *
 * @author andrew
 */
abstract class GOObject extends GOBase {
    protected static $Objects=[];
    protected static $Instances=[]; // Incidence Numbers by section
    protected string $section="";
    private   ?int   $instance=NULL;

    public function __construct(?string $name=NULL) {
        if (!empty($name)) $this->set("Name",$name);
        self::$Objects[]=$this;
        $this->instance=$this->nextInstance();
    }
    
    /**
     * Increment the instance number.
     * 
     * @return int|null
     */
    protected function nextInstance() : ?int {
        $section=$this->section;
        return 
            isset(self::$Instances[$section])
            ? ++self::$Instances[$section]
            : self::$Instances[$section]=1;
    }
    
    public function __toString(): string {
        return "[" . $this->section . $this->instance() . "]\n" . parent::__toString();
    }

    /**
     * Save to a file
     * @param type $file - name of file
     * @return bool - TRUE on success
     */
    public static function save(string $file, string $comments="") : bool {
        if (($handle=fopen($file, "w"))) {
            fputs($handle, hex2bin("EFBBBF")); // UTF-8 Marker
            fputs($handle, ";" . str_replace("\n", "\n;", $comments) . "\n");
            foreach (self::$Objects as $object) {
                fputs($handle, (string) $object);
                fputs($handle, "\n");
            }
            fclose($handle);
            return TRUE;
        }
        else
            return FALSE;
    }
    
    public function section() {
        return $this->section;
    }

    /**
     * Get the current instance (001, 002 ...)
     * @return string
     */
    public function instance() : string {
        if ($this->instance===NULL)
            return "";
        else
            return $this->int2str($this->instance);
    }
    
    /*
     * Reset everything
     */
    public static function reset() : void {
        self::$Objects=[];
        self::$Instances=[]; // Incidence Numbers by section
    }
    
    /**
     * Create a link to another object (eg Stop999=99)
     * @param string $type: Type of object ("Rank", "Stop" etc.)
     * @param string $numberof: NumberOfXXX 
     * @param GOObject $object: The object - must return instance()
     * @return void
     */
    protected function setObject(string $type, string $numberof, GOObject $object) : void {
        $this->set($numberof, $n=$this->get($numberof, 0) + 1);
        $number=GOBase::int2str($n);
        $this->set("$type$number",intval($object->instance()));
    }
}
