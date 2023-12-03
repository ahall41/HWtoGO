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
require_once __DIR__ . "/PanelElement.php";

/**
 * Representation of a GrandOrgue Panel, setting suitable default properties
 * 
 * @author andrew
 */
class Panel extends GOObject {
    protected string $section="Panel";

    public function __construct($name) {
        if (!array_key_exists("Panel", self::$Instances)) 
            self::$Instances["Panel"]=-1;
        parent::__construct($name); 
        if (intval($this->instance())>0)
            Organ::Organ()->NumberOfPanels++;

        $this->HasPedals=intval($this->instance())>1 ? "N" : Organ::Organ()->HasPedals;
        $this->DispButtonCols=10;
        $this->DispButtonsAboveManuals="N";
        $this->DispConsoleBackgroundImageNum=19;
        $this->DispControlLabelFont="Arial";
        $this->DispDrawstopBackgroundImageNum=1;
        $this->DispDrawstopBackgroundImageNum=17;
        $this->DispDrawstopCols=4;
        $this->DispDrawstopColsOffset="Y";
        $this->DispDrawstopInsetBackgroundImageNum=19;
        $this->DispDrawstopOuterColOffsetUp="N";
        $this->DispDrawstopRows=7;
        $this->DispExtraButtonRows=1;
        $this->DispExtraDrawstopCols=3;
        $this->DispExtraDrawstopRows=4;
        $this->DispExtraDrawstopRowsAboveExtraButtonRows="Y";
        $this->DispExtraPedalButtonRow="N";
        $this->DispExtraPedalButtonRowOffset="Y";
        $this->DispExtraPedalButtonRowOffsetRight="Y";
        $this->DispGroupLabelFont="Arial";
        $this->DispKeyHorizBackgroundImageNum=18;
        $this->DispKeyVertBackgroundImageNum=13;
        $this->DispPairDrawstopCols="N";
        $this->DispScreenSizeHoriz="Medium";
        $this->DispScreenSizeVert="Medium";
        $this->DispShortcutKeyLabelColour="Yellow";
        $this->DispShortcutKeyLabelFont="Arial";
        $this->DispTrimAboveExtraRows="N";
        $this->DispTrimAboveManuals="N";
        $this->DispTrimBelowManuals="N";
        $this->NumberOfGUIElements=0;
        $this->NumberOfImages=0;
    }
    
    /**
     * Create a panel element linked to this panel
     * @param string|null $name
     * @param string|null $label
     * @return PanelElement
     */
    private function Element(?string $name=NULL, ?string $label=NULL) : PanelElement {
        $instance=$this->instance();
        $this->NumberOfGUIElements++;
        $element=new PanelElement("Panel${instance}Element");
        if (!empty($name)) $element->Name=$name;
        if (!empty($label)) $element->DispLabelText=$label;
        return $element;
    }
    
    /**
     * Create a label element
     * @param string $label
     * @return PanelElement
     */
    public function Label(string $label) : PanelElement {
        $element=$this->Element($label);
        $element->Type="Label";
        $element->DispImageNum=1;
        return $element;
    }
    
    /**
     * Create a GUI element (typically a Switch)
     * @param GOObject $object
     * @return PanelElement
     */
    public function GUIElement(GOObject $object) : PanelElement {
        $element=$this->Element();
        $class=str_replace(
                "Sw1tch", "Switch", 
                str_replace("GOClasses\\","", get_class($object)));
        $element->Type=$class;
        $element->$class=$object->instance();
        return $element;
    }

    /**
     * Create an image element
     * 
     * @param string $image
     * @param int|null $x
     * @param int|null $y
     * @return PanelElement
     */
    public function Image(string $image, ?int $x=0, ?int $y=0) : PanelElement {
        $instance=$this->instance();
        $this->NumberOfImages++;
        $element=new PanelElement("Panel${instance}Image");
        $element->Image=$image;
        $element->PositionX=$x;
        $element->PositionY=$y;
        return $element;
    }
    
}