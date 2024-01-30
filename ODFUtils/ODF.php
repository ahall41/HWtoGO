<?php

/* 
 * Read a .cmb file * 
 */

class ODF {
    
    protected array $buffer=[];
    protected array $index=[];
    protected string $sourcefile;

    public function __construct(string $filename=NULL) {
        if (!empty($filename)) {
            $this->read($filename);
        }
    }

    public function read(string $filename) : void {
        $this->sourcefile=$filename;
        $fp=fopen($filename, "r");
        $buffer=[];
        $index=[];

        $section=NULL;
        $lineno=0;
        while (!feof($fp)) {
            $line=trim(fgets($fp));
            if (feof($fp) && empty($line)) {break;};
            $buffer[$lineno]=$line;
            
            if (preg_match("/^\[.*\]/", $line)) {
                $section=trim($line, "[]");
                $index[$section]=[];
            }
            elseif (preg_match("/.*=.*/", $line)) {
                $split=explode("=", $line, 2);
                $buffer[$lineno]=[$split[0]=>$split[1]];
                $index[$section][$split[0]]=$lineno;
            }
            $lineno++;
        }
        fclose($fp);
        $this->buffer=$buffer;
        $this->index=$index;
    }
    
    public function write(string $filename) : void {
        $fp=fopen($filename, "w");
        foreach($this->buffer as $line) {
            if (is_array($line)) {
                foreach ($line as $name=>$value) {
                    fwrite($fp, "$name=$value\n");
                }
            }
            else {
                fwrite($fp, "$line\n");
            }
        }
        fclose($fp);
    }
    
    public function getIndex() : array {
        return $this->index;
    }
    
    public function getLine(int $lineno) : mixed {
        return $this->buffer[$lineno];
    }
    
    public function getSection(string $section) : array {
        return $this->index[$section];
    }
    
    public function getItem(string $section, string $key, mixed $default=NULL) : mixed {
        if (array_key_exists($section, $this->index) && 
            array_key_exists($key, $this->index[$section])) {
            $lineno=$this->index[$section][$key];
            return $this->buffer[$lineno][$key];
        }
        return $default;
    }
    
    public function setItem(string $section, string $key, mixed $value) : void {
        if (array_key_exists($section, $this->index) && 
            array_key_exists($key, $this->index[$section])) {
            $lineno=$this->index[$section][$key];
            $this->buffer[$lineno][$key]=$value;
        }
    }
    
    public function addItem(string $section, string $key, mixed $value) : void {
        if (array_key_exists($section, $this->index) && 
            array_key_exists($key, $this->index[$section])) {
            $lineno=$this->index[$section][$key];
            $this->buffer[$lineno][$key].="/n$value";
        }
    }

    public function hasItem(string $section, string $key) : bool {
        return  isset($this->index[$section][$key]);
    }
    
    public function setLine(int $lineno, mixed $data) {
        $this->buffer[$lineno]=$data;
    }

    public function newLine(mixed $data) : int {
        $lineno=sizeof($this->buffer);
        $this->setLine($lineno, $data);
        return $lineno;
    }
    
    public function newSection(string $section, mixed $data) {
        foreach($data as $key=>$value) {
            if (is_integer($key)) {
                $this->newLine($value);
            }
            else {
                $lineno=$this->newLine([$key=>$value]);
                $this->index[$section][$key]=$lineno;
            }
        }
    }
}