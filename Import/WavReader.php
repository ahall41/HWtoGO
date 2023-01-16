<?php

/*
 * Copyright (C) 2023 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Based on WaveFileReader (C)2012 Rob Janssen / https://github.com/RobThree
 * Based on https://ccrma.stanford.edu/courses/422/projects/WaveFormat/
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Import;
class WavReader {
    
    private $handle=NULL; // File handle
    
    public function __construct(?string $file=NULL) {
        if (!empty($file)) $this->open($file);
    }
    
    public function open(string $file) : void {
        $this->close();
        $this->handle=fopen($file, "rb");
    }
    
    public function __destruct() {
        $this->close();
    }
    
    public function close() : void {
        if (!empty($this->handle)) fclose($this->handle);
        $this->handle=NULL;
            
    }

    public function isEof() : bool {
        return feof($this->handle);
    }

    public function header() : array {
        return [
            'chunkid'=>$this->readString(4),
            'chunksize'=>$this->readLong(),
            'format'=>$this->readString(4)];
    }
    
    public function chunk() : array {
        $id=$this->readString(4);
        $size=$this->readLong();
        $result=["id"=>$id, "size"=>$size];
        switch ($id) {
            case "smpl":
                $result["manufacturer"]=$this->readLong();
                $result["product"]=$this->readLong();
                $result["period"]=$this->readLong();
                $result["MIDINote"]=$this->readLong();
                $result["MIDICents"]=($this->readLong()*50.0)/0x80000000;
                $size-=20;
                $result["data"]=$this->readString($size);
                break;
            default:
                $result["data"]=$this->readString($size);
        }
        return $result;
    }
    
    /**
    * Reads a string
    * 
    * @param    int     $length     The number of bytes to read
    * 
    * @return   string              The string read from the file
    */
    private function readString($length) : ?string {
        return self::readUnpacked('a*', $length);
    }

    /**
    * Reads a 32bit unsigned integer
    * 
    * @return   int                 The 32bit unsigned integer read from the file
    */
    private function readLong() : ?int {
        return self::readUnpacked('V', 4);
    }

    /**
    * Reads a 16bit unsigned integer
    * 
    * @param    int     $handle     The filehandle to read the 16bit unsigned integer from
    * 
    * @return   int                 The 16bit unsigned integer read from the file
    */
    private function readShort() : ?int {
        return self::readUnpacked('v', 2);
    }
    
    /**
    * Reads the specified number of bytes from a specified file handle and 
    * unpacks it according to the specified type
    * 
    * @param    int     $type       The type of data being read (see PHP's Pack() documentation)
    * @param    int     $length     The number of bytes to read
    * 
    * @return   mixed               The unpacked data read from the file
    */
    private function readUnpacked($type, $length) {
        if ($this->isEof()) return NULL;
        $r = unpack($type, fread($this->handle, $length));
        return array_pop($r);
    }    
}
