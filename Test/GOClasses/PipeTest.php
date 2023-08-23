<?php

/*
 * Copyright (C) 2022 Andrew E Hall (ahall.ahall.41@gmail.com)
 * 
 * Released under the Creative Commons Non-Commercial 4.0 licence
 * (https://creativecommons.org/licenses/by-nc/4.0/)
 * 
 */

/*******************************************************************************
 * Unit tests for Pipe
 ******************************************************************************/

namespace GOClasses;
require_once (__DIR__ . "/../../GOClasses/Pipe.php");

class PipeTest extends \PHPUnit\Framework\TestCase {
    
    public function testPipe() {
        $pipe=new Pipe();
        $this->assertEquals(
                "Pipe000AttackCount=-1\n"
              . "Pipe000ReleaseCount=0\n", (string) $pipe);
        $pipe->Attack="Attack/1";
        $pipe->Pipe=1;
        $this->assertEquals(
                "Pipe001AttackCount=0\n"
              . "Pipe001ReleaseCount=0\n"
              . "Pipe001=Attack/1\n", (string) $pipe);
        $pipe->Release="Release/1";
        $this->assertEquals(
                "Pipe001AttackCount=0\n"
              . "Pipe001ReleaseCount=1\n"
              . "Pipe001=Attack/1\n"
              . "Pipe001Release001=Release/1\n"
              . "Pipe001LoadRelease=N\n"
              . "Pipe001Release001MaxKeyPressTime=-1\n", (string) $pipe);
        $pipe->AttackStart=123;
        $this->assertEquals(123, $pipe->AttackStart);
        $pipe->AttackCuePoint=456;
        $this->assertEquals(456, $pipe->CuePoint);
        $pipe->AttackVelocity=789;
        $this->assertEquals(789, $pipe->AttackVelocity);
        $pipe->AttackLoadRelease=532;
        $this->assertEquals(532, $pipe->LoadRelease);
        $pipe->AttackMaxTimeSinceLastRelease=245;
        $this->assertEquals(245, $pipe->MaxTimeSinceLastRelease);
        $pipe->AttackMaxKeyPressTime=321;
        $this->assertEquals(321, $pipe->MaxKeyPressTime);
        $pipe->AttackIsTremulant=0;
        $this->assertEquals(0, $pipe->IsTremulant);
        $pipe->AttackReleaseEnd=498;
        $this->assertEquals(498, $pipe->ReleaseEnd);
        $pipe->LoopStart=111;
        $this->assertEquals(1, $pipe->LoopCount);
        $this->assertEquals(111, $pipe->Loop001Start);
        $pipe->LoopEnd=222;
        $this->assertEquals(222, $pipe->Loop001End);
        
        $pipe->Attack="Attack/2";
        $this->assertEquals("Attack/2", $pipe->Attack001);
        $pipe->AttackStart=234;
        $this->assertEquals(234, $pipe->Attack001AttackStart);
        $pipe->AttackCuePoint=567;
        $this->assertEquals(567, $pipe->Attack001CuePoint);
        $pipe->AttackVelocity=890;
        $this->assertEquals(890, $pipe->Attack001AttackVelocity);
        $pipe->AttackLoadRelease=235;
        $pipe->Attack="Attack/3";
        $this->assertEquals(235, $pipe->Attack001LoadRelease);
        $pipe->AttackMaxTimeSinceLastRelease=542;
        $this->assertEquals(542, $pipe->Attack002MaxTimeSinceLastRelease);
        $pipe->AttackMaxKeyPressTime=798;
        $this->assertEquals(798, $pipe->Attack002MaxKeyPressTime);
        $pipe->AttackIsTremulant=1;
        $this->assertEquals(1, $pipe->Attack002IsTremulant);
        $pipe->AttackReleaseEnd=501;
        $this->assertEquals(501, $pipe->Attack002ReleaseEnd);
        $pipe->LoopStart=333;
        $pipe->LoopEnd=444;
        $pipe->LoopStart=555;
        $pipe->LoopEnd=666;
        $this->assertEquals(2, $pipe->Attack002LoopCount);
        $this->assertEquals(333, $pipe->Attack002Loop001Start);
        $this->assertEquals(666, $pipe->Attack002Loop002End);
        
        $pipe->ReleaseIsTremulant=0;
        $this->assertEquals(0, $pipe->Release001IsTremulant);
        $pipe->Release="Release/2";
        $pipe->ReleaseMaxKeyPressTime=12;
        $this->assertEquals(12, $pipe->Release002MaxKeyPressTime);
        $pipe->ReleaseCuePoint=32;
        $this->assertEquals(32, $pipe->Release002CuePoint);
        $pipe->ReleaseMaxKeyPressTime=43;
        $this->assertEquals(43, $pipe->Release002MaxKeyPressTime);
        $pipe->ReleaseCuePoint=54;
        $this->assertEquals(54, $pipe->Release002CuePoint);
        $pipe->Dummy();
        $this->assertEquals("Pipe001=DUMMY\n", (string) $pipe);
    }
    
    public function testSameRelease() {
        $pipe=new Pipe();
        $pipe->Attack="Attack1.wav";
        $pipe->Release="Attack1.wav";
        $this->assertEquals(
                "Pipe000AttackCount=0\n"
              . "Pipe000ReleaseCount=0\n"
              . "Pipe000=Attack1.wav\n"
              . "Pipe000LoadRelease=Y\n", (string) $pipe);
        
        $pipe->ReleaseMaxKeyPressTime=123;
        $pipe->ReleaseIsTremulant=TRUE;
        $pipe->ReleaseEnd=456;
        $pipe->ReleaseCuePoint=789;
        $this->assertEquals(
                "Pipe000AttackCount=0\n"
              . "Pipe000ReleaseCount=0\n"
              . "Pipe000=Attack1.wav\n"
              . "Pipe000LoadRelease=Y\n", (string) $pipe);
   }
}