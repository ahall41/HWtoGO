<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Noise
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Noise.php");

class NoiseTest extends \PHPUnit\Framework\TestCase {
    
    public function testNoise() {
        Noise::$blankloop="Blankloop.wav";
        $noise=new Noise();
        $this->assertEquals(
                "Pipe001AttackCount=0\n" .
                "Pipe001ReleaseCount=0\n" .
                "Pipe001Percussive=Y\n" .
                "Pipe001=Blankloop.wav\n" .
                "Pipe001LoadRelease=N\n", (string) $noise);
        $noise->Attack="Attack/1";
        $noise->Pipe=1;
        $this->assertEquals(
                "Pipe001AttackCount=0\n"
              . "Pipe001ReleaseCount=0\n"
              . "Pipe001Percussive=Y\n"
              . "Pipe001=Attack/1\n"
              . "Pipe001LoadRelease=N\n", (string) $noise);
        $noise->Release="Release/1";
        $this->assertEquals(
                "Pipe001AttackCount=0\n"
              . "Pipe001ReleaseCount=1\n"
              . "Pipe001Percussive=N\n"
              . "Pipe001=Attack/1\n"
              . "Pipe001LoadRelease=N\n"
              . "Pipe001Release001=Release/1\n"
              . "Pipe001Release001MaxKeyPressTime=-1\n", (string) $noise);
        $noise->AttackStart=123;
        $this->assertEquals(123, $noise->AttackStart);
        $noise->AttackCuePoint=456;
        $this->assertEquals(456, $noise->CuePoint);
        $noise->AttackVelocity=789;
        $this->assertEquals(789, $noise->AttackVelocity);
        $noise->AttackLoadRelease=532;
        $this->assertEquals(532, $noise->LoadRelease);
        $noise->AttackMaxTimeSinceLastRelease=245;
        $this->assertEquals(245, $noise->MaxTimeSinceLastRelease);
        $noise->AttackMaxKeyPressTime=321;
        $this->assertEquals(321, $noise->MaxKeyPressTime);
        $noise->AttackIsTremulant="N";
        $this->assertEquals("N", $noise->IsTremulant);
        $noise->AttackReleaseEnd=498;
        $this->assertEquals(498, $noise->ReleaseEnd);
        $noise->LoopStart=111;
        $this->assertEquals(1, $noise->LoopCount);
        $this->assertEquals(111, $noise->Loop001Start);
        $noise->LoopEnd=222;
        $this->assertEquals(222, $noise->Loop001End);
        
        $noise->Attack="Attack/2";
        $this->assertEquals("Attack/2", $noise->Attack001);
        $noise->AttackStart=234;
        $this->assertEquals(234, $noise->Attack001AttackStart);
        $noise->AttackCuePoint=567;
        $this->assertEquals(567, $noise->Attack001CuePoint);
        $noise->AttackVelocity=890;
        $this->assertEquals(890, $noise->Attack001AttackVelocity);
        $noise->AttackLoadRelease=235;
        $noise->Attack="Attack/3";
        $this->assertEquals(235, $noise->Attack001LoadRelease);
        $noise->AttackMaxTimeSinceLastRelease=542;
        $this->assertEquals(542, $noise->Attack002MaxTimeSinceLastRelease);
        $noise->AttackMaxKeyPressTime=798;
        $this->assertEquals(798, $noise->Attack002MaxKeyPressTime);
        $noise->AttackIsTremulant="Y";
        $this->assertEquals("Y", $noise->Attack002IsTremulant);
        $noise->AttackReleaseEnd=501;
        $this->assertEquals(501, $noise->Attack002ReleaseEnd);
        $noise->LoopStart=333;
        $noise->LoopEnd=444;
        $noise->LoopStart=555;
        $noise->LoopEnd=666;
        $this->assertEquals(2, $noise->Attack002LoopCount);
        $this->assertEquals(333, $noise->Attack002Loop001Start);
        $this->assertEquals(666, $noise->Attack002Loop002End);
        
        $noise->ReleaseIsTremulant="N";
        $this->assertEquals("N", $noise->Release001IsTremulant);
        $noise->Release="Release/2";
        $noise->ReleaseMaxKeyPressTime=12;
        $this->assertEquals(12, $noise->Release002MaxKeyPressTime);
        $noise->ReleaseCuePoint=32;
        $this->assertEquals(32, $noise->Release002CuePoint);
        $noise->ReleaseMaxKeyPressTime=43;
        $this->assertEquals(43, $noise->Release002MaxKeyPressTime);
        $noise->ReleaseCuePoint=54;
        $this->assertEquals(54, $noise->Release002CuePoint);
        
        $noise=new Noise();
        $noise->Release="Release/1";
        
        $noise->Pipe=2;
        $this->assertEquals(
                "Pipe002AttackCount=0\n" .
                "Pipe002ReleaseCount=1\n" .
                "Pipe002Percussive=N\n" .
                "Pipe002Release001=Release/1\n" .
                "Pipe002LoadRelease=N\n" .
                "Pipe002Release001MaxKeyPressTime=-1\n" .
                "Pipe002=Blankloop.wav\n", (string) $noise);
   }
}