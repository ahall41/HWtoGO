<?php

/*******************************************************************************
 * Unit tests for HWAnalyser
 ******************************************************************************/

namespace Import;
require_once (__DIR__ . "/../../Import/Images.php");

class ImagesTestClass extends Images {
    
    public function hwdata() : \HWClasses\HWData {
        return $this->hwdata;
    }    
}

class ImagesTest extends \PHPUnit\Framework\TestCase {
    
    const Skrzatusz="Skrzatusz";
    const Utrecht="Utrecht Dom, Surround DEMO";

    private static function Images($xmlfile, $new=FALSE) : Images {
        ini_set("memory_limit", "512M");
        new \GOClasses\Organ("Test");
        static $cache=[];
        if ($new || !isset($cache[$xmlfile])) 
            $cache[$xmlfile]=new ImagesTestClass(__DIR__ . "/../ODF/${xmlfile}.Organ_Hauptwerk_xml");
        return $cache[$xmlfile];
    }

    public function testGetImageData() {
        $skrzatusz=self::Images(self::Skrzatusz);
        $this->assertEquals(
                ["Images"=>[]], $skrzatusz->getImageData([]));

        $set=$skrzatusz->getImageData(["SetID"=>1]);
        $this->assertEquals([
            1 => 'OrganInstallationPackages/000693/Images/cons_e_vi16.png',
            2 => 'OrganInstallationPackages/000693/Images/cons_d_vi16.png'
        ], $set["Images"]);
        unset($set["Images"]);
        $this->assertEquals([
            'ImageWidthPixels' => '207',
            'ImageHeightPixels' => '222',
            'MouseRectLeft' => '0',
            'MouseRectWidth' => '207',
            'MouseRectTop' => '0',
            'MouseRectHeight' => '222'             
        ], $set);

        $utrecht=self::Images(self::Utrecht);
        $instance0=$utrecht->getImageData(["InstanceID"=>12001]);
        $this->assertEquals([
            1 => 'OrganInstallationPackages/000890/stops2/koppeling_pd_ml_in.bmp',
            2 => 'OrganInstallationPackages/000890/stops2/koppeling_pd_ml_out.bmp'
        ], $instance0["Images"]);
        unset($instance0["Images"]);
        $this->assertEquals([
            'PositionX' => '1029',
            'PositionY' => '870',
            'MouseRectWidth' => '298',
            'MouseRectHeight' => '90'            
        ], $instance0);
        
        $instance1=$utrecht->getImageData(["InstanceID"=>12001], 1);
        $this->assertEquals(1,sizeof($instance1));
        $instance2=$utrecht->getImageData(["InstanceID"=>12001], 2);
        $this->assertEquals(5,sizeof($instance2));
        $instance3=$utrecht->getImageData(["InstanceID"=>12001], 3);
        $this->assertEquals(1,sizeof($instance3));
        
        $this->assertEquals([
            1 => 'OrganInstallationPackages/000890/stops3/koppeling_pd_ml_in.bmp',
            2 => 'OrganInstallationPackages/000890/stops3/koppeling_pd_ml_out.bmp'
        ], $instance2["Images"]);
        unset($instance2["Images"]);
        $this->assertEquals([
            'PositionX' => '665',
            'PositionY' => '1640',
            'MouseRectWidth' => '265',
            'MouseRectHeight' => '120'            
        ], $instance2);
        
        $switch=$skrzatusz->getImageData(["SwitchID"=>11]);
        $this->assertEquals(2, sizeof($switch["Images"]));
        unset($switch["Images"]);
        $this->assertEquals([
            'IndexEngaged' => '1',
            'IndexDisengaged' => '2',
            'PositionX' => '412',
            'PositionY' => '615',
            'ImageWidthPixels' => '207',
            'ImageHeightPixels' => '222',
            'MouseRectLeft' => '0',
            'MouseRectWidth' => '207',
            'MouseRectTop' => '0',
            'MouseRectHeight' => '222'         
        ], $switch);
    }
    
    public function testConfigureImage() {
        $skrzatusz=self::Images(self::Skrzatusz);
        $set=new \GOClasses\PanelElement("Test");
        $skrzatusz->configureImage($set, ["SwitchID"=>11]);
        $this->assertEquals(
                "[Test001]\n" .
                "DispLabelText=\n" .
                "ImageOn=OrganInstallationPackages/000693/Images/cons_e_vi16.png\n" .
                "ImageOff=OrganInstallationPackages/000693/Images/cons_d_vi16.png\n" .
                "PositionX=412\n" .
                "PositionY=615\n" .
                "MouseRectLeft=0\n" .
                "MouseRectWidth=207\n" .
                "MouseRectTop=0\n" .
                "MouseRectHeight=222\n" .
                "MouseRadius=0\n", (string) $set);
    }
    
    public function testConfigureEnclosureImage() {
        $utrecht=self::Images(self::Utrecht);
        $pe1=new \GOClasses\PanelElement("Test");
        $utrecht->configureEnclosureImage($pe1, []);
        $this->assertEquals(
                "[Test001]\n" .
                "BitmapCount=0\n" .
                "DispLabelText=\n", (string) $pe1);

        $pe2=new \GOClasses\PanelElement("Test");
        $utrecht->configureEnclosureImage($pe2, ["SetID"=>996]);
        $this->assertFalse(isset($pe2->PositionX));
        $this->assertFalse(isset($pe2->PositionY));
        $this->assertEquals(64, $pe2->BitmapCount);
        $this->assertEquals(
                "OrganInstallationPackages/000890/swell2/0.bmp", $pe2->Bitmap001);
        $this->assertEquals(
                "OrganInstallationPackages/000890/swell2/63.bmp", $pe2->Bitmap064);

        $pe3=new \GOClasses\PanelElement("Test");
        $utrecht->configureEnclosureImage($pe3, ["InstanceID"=>996]);
        $this->assertEquals(766,$pe3->PositionX);
        $this->assertEquals(768,$pe3->PositionY);
        $this->assertEquals(64, $pe3->BitmapCount);
    }
    
    public function testConfigureKeyImage() {
        $skrkatusz=self::Images(self::Skrzatusz);
        $keyimageset=$skrkatusz->hwdata()->keyimageset(1);
        $manual=new \GOClasses\Manual("Test");
        $skrkatusz->configureKeyImage($manual, $keyimageset);
        $this->assertEquals(
                "[Manual000]\n" .
                "Name=Test\n" .
                "NumberOfLogicalKeys=30\n" .
                "FirstAccessibleKeyLogicalKeyNumber=1\n" .
                "FirstAccessibleKeyMIDINoteNumber=36\n" .
                "NumberOfAccessibleKeys=30\n" .
                "MIDIInputNumber=1\n" .
                "Displayed=Y\n" .
                "NumberOfStops=0\n" .
                "NumberOfCouplers=0\n" .
                "NumberOfDivisionals=0\n" .
                "NumberOfTremulants=0\n" .
                "NumberOfSwitches=0\n" .
                "ImageOn_C=OrganInstallationPackages/000693/KeyImages/manwdcf.png\n" .
                "ImageOff_C=OrganInstallationPackages/000693/KeyImages/manwucf.png\n" .
                "Width_C=12\n" .
                "ImageOn_Cis=OrganInstallationPackages/000693/KeyImages/manbd.png\n" .
                "ImageOff_Cis=OrganInstallationPackages/000693/KeyImages/manbu.png\n" .
                "Width_Cis=6\n" .
                "ImageOn_D=OrganInstallationPackages/000693/KeyImages/manwdda.png\n" .
                "ImageOff_D=OrganInstallationPackages/000693/KeyImages/manwuda.png\n" .
                "Width_D=12\n" .
                "ImageOn_Dis=OrganInstallationPackages/000693/KeyImages/manbd.png\n" .
                "ImageOff_Dis=OrganInstallationPackages/000693/KeyImages/manbu.png\n" .
                "Width_Dis=6\n" .
                "ImageOn_E=OrganInstallationPackages/000693/KeyImages/manwdeb.png\n" .
                "ImageOff_E=OrganInstallationPackages/000693/KeyImages/manwueb.png\n" .
                "Width_E=18\n" .
                "ImageOn_F=OrganInstallationPackages/000693/KeyImages/manwdcf.png\n" .
                "ImageOff_F=OrganInstallationPackages/000693/KeyImages/manwucf.png\n" .
                "Width_F=12\n" .
                "ImageOn_Fis=OrganInstallationPackages/000693/KeyImages/manbd.png\n" .
                "ImageOff_Fis=OrganInstallationPackages/000693/KeyImages/manbu.png\n" .
                "Width_Fis=6\n" .
                "ImageOn_G=OrganInstallationPackages/000693/KeyImages/manwdda.png\n" .
                "ImageOff_G=OrganInstallationPackages/000693/KeyImages/manwuda.png\n" .
                "Width_G=12\n" .
                "ImageOn_Gis=OrganInstallationPackages/000693/KeyImages/manbd.png\n" .
                "ImageOff_Gis=OrganInstallationPackages/000693/KeyImages/manbu.png\n" .
                "Width_Gis=6\n" .
                "ImageOn_A=OrganInstallationPackages/000693/KeyImages/manwdda.png\n" .
                "ImageOff_A=OrganInstallationPackages/000693/KeyImages/manwuda.png\n" .
                "Width_A=12\n" .
                "ImageOn_Ais=OrganInstallationPackages/000693/KeyImages/manbd.png\n" .
                "ImageOff_Ais=OrganInstallationPackages/000693/KeyImages/manbu.png\n" .
                "Width_Ais=6\n" .
                "ImageOn_B=OrganInstallationPackages/000693/KeyImages/manwdeb.png\n" .
                "ImageOff_B=OrganInstallationPackages/000693/KeyImages/manwueb.png\n" .
                "Width_B=18\n" .
                "ImageOn_FirstC=OrganInstallationPackages/000693/KeyImages/manwdcf.png\n" .
                "ImageOff_FirstC=OrganInstallationPackages/000693/KeyImages/manwucf.png\n" .
                "Width_FirstC=18\n" .
                "ImageOn_LastF=OrganInstallationPackages/000693/KeyImages/manwdcf.png\n" .
                "ImageOff_LastF=OrganInstallationPackages/000693/KeyImages/manwucf.png\n" .
                "Width_LastF=18\n", (string) $manual);
    }

    public function testConfigureKeyboardKey() {
        $utrecht=self::Images(self::Utrecht);
        $manual=new \GOClasses\Manual("Test");
        $utrecht->configureKeyboardKey($manual, 8036, 36);
        $this->assertEquals(1, $manual->DisplayKeys);
        $this->assertEquals(574, $manual->PositionX);
        $this->assertEquals(726, $manual->PositionY);
        $this->assertEquals(36, $manual->DisplayKey001);
        $this->assertEquals(
                "OrganInstallationPackages/000890/pedal/natshdown.bmp", $manual->Key001ImageOn);
        $this->assertEquals(
                "OrganInstallationPackages/000890/pedal/natshup.bmp", $manual->Key001ImageOff);
        $utrecht->configureKeyboardKey($manual, 8037, 99);
        $this->assertEquals(2, $manual->DisplayKeys);
        $this->assertEquals(99, $manual->DisplayKey002);
    }
    
    public function testCreatePanelImage() {
        $skrkatusz=self::Images(self::Skrzatusz);
        $panel=new \GOClasses\Panel("Test");
        $pe=$skrkatusz->createPanelImage($panel, ["InstanceID"=>1]);
        $this->assertEquals(
                "[Panel000Image001]\n" .
                "Image=OrganInstallationPackages/000693/Images/cons_e_vi16.png\n" .
                "PositionX=412\n" .
                "PositionY=615\n", (string) $pe);
    }
}