<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

namespace Import;
require_once(__DIR__ . "/Configure.php");
require_once(__DIR__ . "/../HWClasses/HWData.php"); 

/**
 * HW Import Imaging layer
 *
 * @author andrew
 */
abstract class Images extends Configure {
    
    protected \HWClasses\HWData $hwdata;
    protected static $keymap=
        [ // Note, Image, Width, FirstImage, LastImage
            ["C",   "KeyShapeImageSetID_CF", "HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF", "KeyShapeImageSetID_WholeNatural", "KeyShapeImageSetID_WholeNatural"],
            ["Cis", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp", NULL, NULL],
            ["D",   "KeyShapeImageSetID_D", "HorizSpacingPixels_LeftOfDASharpFromLeftOfDA", "KeyShapeImageSetID_FirstKeyDA", "KeyShapeImageSetID_LastKeyDG"],
            ["Dis", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfEBFromLeftOfDASharp", NULL, NULL],
            ["E",   "KeyShapeImageSetID_EB", "HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural", "KeyShapeImageSetID_WholeNatural", NULL],
            ["F",   "KeyShapeImageSetID_CF", "HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF", "KeyShapeImageSetID_WholeNatural", "KeyShapeImageSetID_WholeNatural"],
            ["Fis", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp", NULL, NULL],
            ["G",   "KeyShapeImageSetID_G", "HorizSpacingPixels_LeftOfGSharpFromLeftOfG", "KeyShapeImageSetID_FirstKeyG", "KeyShapeImageSetID_LastKeyDG"],
            ["Gis", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfAFromLeftOfGSharp", NULL, NULL],
            ["A",   "KeyShapeImageSetID_A", "HorizSpacingPixels_LeftOfDASharpFromLeftOfDA", "KeyShapeImageSetID_FirstKeyDA", "KeyShapeImageSetID_LastKeyA"],
            ["Ais", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfEBFromLeftOfDASharp", NULL, NULL],
            ["B",   "KeyShapeImageSetID_EB", "HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural", NULL,  NULL],
        ];

    protected static $akeymap=
        [ // Note, Image, Width, FirstImage, LastImage
            ["C",   "KeyShapeImageSetID_CF", "HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural", "KeyShapeImageSetID_WholeNatural", "KeyShapeImageSetID_WholeNatural"],
            ["Cis", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF", NULL, NULL],
            ["D",   "KeyShapeImageSetID_D", "HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp", "KeyShapeImageSetID_FirstKeyDA", "KeyShapeImageSetID_LastKeyDG"],
            ["Dis", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfDASharpFromLeftOfDA", NULL, NULL],
            ["E",   "KeyShapeImageSetID_EB", "HorizSpacingPixels_LeftOfEBFromLeftOfDASharp", "KeyShapeImageSetID_WholeNatural", NULL],
            ["F",   "KeyShapeImageSetID_CF", "HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural", NULL, NULL],
            ["Fis", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfCFSharpFromLeftOfCF", NULL, NULL],
            ["G",   "KeyShapeImageSetID_G", "HorizSpacingPixels_LeftOfDGFromLeftOfCFSharp", "KeyShapeImageSetID_FirstKeyG", NULL],
            ["Gis", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfGSharpFromLeftOfG", NULL, "KeyShapeImageSetID_LastKeyDG"],
            ["A",   "KeyShapeImageSetID_A", "HorizSpacingPixels_LeftOfAFromLeftOfGSharp", "KeyShapeImageSetID_FirstKeyDA", "KeyShapeImageSetID_LastKeyA"],
            ["Ais", "KeyShapeImageSetID_Sharp", "HorizSpacingPixels_LeftOfEBFromLeftOfDASharp", NULL, NULL],
            ["B",   "KeyShapeImageSetID_EB", "HorizSpacingPixels_LeftOfNaturalFromLeftOfNatural", NULL],
        ];
    
    public function __construct($xmlfile) {
        if (!file_exists($xmlfile))
            $xmlfile=getenv("HOME") . $xmlfile;
        $this->hwdata = new \HWClasses\HWData($xmlfile);
    }
    
/**
     * 
     * 
     * @param array $data: HW data, may contain, by priority, InstanceID, SwitchID or SetID
     * @param type $layout
     * @return array: Image data
     *      ImageWidthPixels
     *      ImageHeightPixels
     *      MouseRectLeft
     *      MouseRectWidth
     *      MouseRectTop
     *      MouseRectHeight
     *      LeftXPosPixels
     *      TopYPosPixels
     *      Images[Filenames]
     */
    public function getImageData(array $data, int $layout=0) : array {
        static $map=[ /** @@todo not quite right ! */
            "ImageWidthPixels"=>"ImageWidthPixels",
            "ImageHeightPixels"=>"ImageHeightPixels",
            "ClickableAreaLeftRelativeXPosPixels"=>"MouseRectLeft",
            "ClickableAreaRightRelativeXPosPixels"=>"MouseRectWidth",
            "ClickableAreaTopRelativeYPosPixels"=>"MouseRectTop",
            "ClickableAreaBottomRelativeYPosPixels"=>"MouseRectHeight",
        ];
        static $layouts=[0=>"", 1=>"AlternateScreenLayout1_",
            2=>"AlternateScreenLayout2_", 3=>"AlternateScreenLayout3_"];

        $result=[];
        if (isset($data["InstanceID"])) {
            $instanceid=$data["InstanceID"];
        } elseif (isset($data["SwitchID"])) {
            $switch=$this->hwdata->switch($data["SwitchID"]);
            $instanceid=$switch["Disp_ImageSetInstanceID"];
            $result["IndexEngaged"]=$switch["Disp_ImageSetIndexEngaged"];
            $result["IndexDisengaged"]=$switch["Disp_ImageSetIndexDisengaged"];
        }
        else {
            $instanceid=FALSE;
        }

        if ($instanceid) {
            $layout=$layouts[$layout];
            $instance=$this->hwdata->imageSetInstance($instanceid);
            foreach(["PositionX"=>"${layout}LeftXPosPixels",
                     "PositionY"=>"${layout}TopYPosPixels"] as $to=>$fr) {
                if (isset($instance[$fr])) {  
                    $result[$to]=$instance[$fr];
                }
                else {
                    $result[$to]=0;
                }
            }
            if (isset($instance["${layout}ImageSetID"])
                && !empty($instance["${layout}ImageSetID"]))
                $setid=$instance["${layout}ImageSetID"];
            else
                $setid=FALSE;
        }
        elseif (isset($data["SetID"])) 
            $setid=$data["SetID"];
        else
            $setid=FALSE;
  
        $images=[];
        if ($setid) {
            $set=$this->hwdata->imageSet($setid);
            if (!$set) error_log("No set $setid");
            
            foreach ($map as $fr=>$to) {
                if (isset($set[$fr])) $result[$to]=$set[$fr];
            }
            
            foreach ($this->hwdata->imageSetElement($setid) as $index=>$element) {
                $filename=
                    sprintf("OrganInstallationPackages/%06d/%s",
                                $set["InstallationPackageID"], 
                                $element["BitmapFilename"]);
                $images[$index]=$this->correctFileName($filename);
            }
        }
        $result["Images"]=$images;
        return $result;
    }
    
    /**
     * Create image data in GO Object
     * @param GOObject $object: GO object
     * @param array $data (containing SwitchID - see getImageData
     * @param int $layout: Layout (See getImageData)
     */
    public function configureImage(\GOClasses\GOObject $object, array $data, int $layout=0) : void {
        static $map=[
            ["PositionX","PositionX"],
            ["PositionY","PositionY"],
            ["MouseRectLeft","MouseRectLeft"],
            ["MouseRectWidth","MouseRectWidth"],
            ["MouseRectTop","MouseRectTop"],
            ["MouseRectHeight","MouseRectHeight"],
        ];
        $imagedata=$this->getImageData($data, $layout);
        if (!isset($imagedata["Images"]))
            throw new \Exception ("No image for switch $switchid");

        $images=$imagedata["Images"];
        $engaged=$images[$imagedata["IndexEngaged"]];
        $disengaged=$images[$imagedata["IndexDisengaged"]];
        unset($object->Displayed);
        unset($object->DispLabelColour);
        $object->DispLabelText="";
        $object->ImageOn=$engaged;
        $object->ImageOff=$disengaged;
        $this->map($map, $imagedata, $object);
        $object->MouseRadius=0;
    }
    
    /**
     * Create images for an enclose
     * @param array $data: see getImageData()
     * @param Enclosure $enclosure: The enclosure
     * @param int $layout: Layout (See ImageData)
     */
    public function configureEnclosureImage(\GOClasses\GOObject $object, array $data, int $altlayout=0) : void {
        static $map=[
            ["PositionX","PositionX"],
            ["PositionY","PositionY"],
        //  ["MouseRectLeft","MouseRectLeft"],    
        //  ["MouseRectWidth","MouseRectWidth"],    
        //  ["MouseRectTop","MouseRectTop"],    
        //  ["MouseRectHeight","MouseRectHeight"],    
        ];
        $imagedata=$this->getImageData($data, $altlayout);
        //$object->DispLabelText="";
        $this->map($map, $imagedata, $object);

        $images=$imagedata["Images"];
        $object->BitmapCount=sizeof($images);
        foreach ($images as $id=>$image) {
            $object->set(
                    "Bitmap" . $object->int2str($id), $image);
        }
        $object->DispLabelText="";
    }

    /**
     * Add base images to the panels
     * 
     * @param \GOClasses\Panel $panel - Panel to be configured
     * @param array $hwdata - HW general data
     * @return void
     * @throws \Exception
     */
    public function configurePanelImage(\GOClasses\Panel $panel, array $data, int $layout=0): ? \GOClasses\PanelElement {
        static $map=[
            //["ImageWidthPixels","ImageWidthPixels"],
            //["ImageHeightPixels","ImageHeightPixels"],
            ["DispScreenSizeHoriz","ImageWidthPixels"],
            ["DispScreenSizeVert","ImageHeightPixels"],
        ];
        if (isset($data["SetID"])) {
            unset($data["SwitchID"]);
            $imagedata=$this->getImageData($data, $layout);
            $this->map($map, $imagedata, $panel);
            return $panel->Image(reset($imagedata["Images"]));
        }
        return NULL;
    }
    
    /**
     * Create single key image for a manual
     * @param Manual $manual: Manual to add image to
     * @param array $data: see getImageData()
     * @param int $midikey: Midi key for image
     */
    public function configureKeyboardKey(\GOClasses\Manual $manual, int $switchid, int $midikey) : void {
        $manual->DisplayKeys++;
        $key="Key" . $manual->int2str($manual->DisplayKeys);
        if ($manual->FirstAccessibleKeyMIDINoteNumber>$midikey)
            $manual->FirstAccessibleKeyMIDINoteNumber=$midikey;
        $switch=$this->hwdata->switch($switchid);
        $imagedata=$this->getImageData(["SwitchID"=>$switchid]);
        $engaged=$imagedata["Images"][$switch["Disp_ImageSetIndexEngaged"]];
        $disengaged=$imagedata["Images"][$switch["Disp_ImageSetIndexDisengaged"]];
        if (!isset($manual->PositionX)) $manual->PositionX=$imagedata["PositionX"];
        if (!isset($manual->PositionY)) $manual->PositionY=$imagedata["PositionY"];
        $manual->KeyWidth($imagedata["PositionX"]);
        $manual->set("Display${key}", $midikey);
        $manual->set("${key}ImageOn", $engaged);
        $manual->set("${key}ImageOff", $disengaged);
       
        /* This doesn't work well!
        if (isset($imagedata["ImageWidthPixels"])
                    && !empty($imagedata["ImageWidthPixels"])) {
            $manual->set("${key}Width", $imagedata["ImageWidthPixels"]);
            $manual->KeyOffset($imagedata["PositionX"], $imagedata["PositionY"]);
        } */
        /** @todo bounds can't exceed image size in GO?
        if (isset($imagedata["MouseRectLeft"]) && !empty($imagedata["MouseRectLeft"]))
            $manual->set("${key}MouseRectLeft", $imagedata["MouseRectLeft"]);
        if (isset($imagedata["MouseRectWidth"]) && !empty($imagedata["MouseRectWidth"]))
            $manual->set("${key}MouseRectWidth", $imagedata["MouseRectWidth"]);
        if (isset($imagedata["MouseRectTop"]) && !empty($imagedata["MouseRectTop"]))
            $manual->set("${key}MouseRectTop", $imagedata["MouseRectTop"]);
        if (isset($imagedata["MouseRectHeight"]) && !empty($imagedata["MouseRectHeight"]))
            $manual->set("${key}MouseRectHeight", $imagedata["MouseRectHeight"]); */
    }

    /**
     * Create key images for a manual
     * @param Object $object object to add to (a Manual or PanelElement)
     * @param type $keyImageset: HW keyImageSet record
     */
    public function configureKeyImage(?\GOClasses\GOObject $object, array $keyImageset) : void {
        if (isset($keyImageset["ManualID"]))
            $manual=$this->getManual($keyImageset["ManualID"]);
        else
            return;
        if ($object===NULL) $object=$manual;
        
        static $map=[
            ["PositionX", "KeyGen_DispKeyboardLeftXPos"],
            ["PositionX", "PositionX"],
            ["PositionY", "KeyGen_DispKeyboardTopYPos"],
            ["PositionY", "PositionY"],
        ];
        $this->map($map, $keyImageset, $object);
        
        $keymap=self::$keymap;

        $engidx=array_key_exists("ImageIndexWithinImageSets_Engaged", $keyImageset)
                ? $keyImageset["ImageIndexWithinImageSets_Engaged"] : FALSE;
        $disidx=array_key_exists("ImageIndexWithinImageSets_Disengaged", $keyImageset)
                ? $keyImageset["ImageIndexWithinImageSets_Disengaged"] : FALSE;
        foreach ($keymap as $kmap) {
            $key=$kmap[0];
            $image=$kmap[1];
            $width=$kmap[2];
            $imagedata=$this->getImageData(["SetID"=>$keyImageset[$image]]);
            $images=$imagedata["Images"];

            $idx=$engidx===FALSE ? array_key_last($images) : $engidx;
            $on=$images[$idx];

            $idx=$disidx===FALSE ? array_key_first($images) : $disidx;
            $off=$images[$idx];
            unset($images[$idx]);
            $object->set("ImageOn_${key}", $on);                
            $object->set("ImageOff_${key}", $off);                
            $object->set("Width_${key}", $keyImageset[$width]);
        }

        $firstnote=$manual->FirstAccessibleKeyMIDINoteNumber;
        $lastnote=$firstnote + (isset($object->DisplayKeys) ? $object->DisplayKeys-1 : $manual->NumberOfLogicalKeys-1);
        foreach ([$firstnote, $lastnote] as $id=>$midi) {
            $kmap=$keymap[$midi % 12];
            $key=["First", "Last"][$id] . $kmap[0];
            $width=$kmap[2];
            $image=$kmap[3+$id];
            if (empty($image)) $image=$kmap[1];
            $imagedata=$this->getImageData(["SetID"=>$keyImageset[$image]]);
            $images=$imagedata["Images"];

            $idx=$engidx===FALSE ? array_key_last($images) : $engidx;
            $on=$images[$idx];

            $idx=$disidx===FALSE ? array_key_first($images) : $disidx;
            $off=$images[$idx];
            unset($images[$idx]);
            $object->set("ImageOn_${key}", $on);                
            $object->set("ImageOff_${key}", $off);                
            $object->set("Width_${key}", $keyImageset[$width]);
        }
        if (!isset($manual->DisplayKeys))
            $manual->DisplayKeys=$manual->NumberOfAccessibleKeys;
    }
    
    /**
     * Add an image to a panel
     * @param array $data: HW data (see ImageData). also includes optional
     * @param int $imageidx: Index of image to use
     * @param int $panelid: Index of panel to use
     * @param int $altlayout: Layout to use (see ImageData)
     * @return void
     */
    public function createPanelImage(\GOClasses\Panel $panel, array $data, int $layout=0) : \GOClasses\PanelElement {
        $imageidx=isset($data["ImageIDX"]) ? $data["ImageIDX"] : 1;
        $imagedata=$this->getImageData($data, $layout);
        return $panel->Image(
                $imagedata["Images"][$imageidx], 
                $imagedata["PositionX"], 
                $imagedata["PositionY"]);
    }
}