<?php

/*******************************************************************************
 * Unit tests for Import\AubioPitch
 ******************************************************************************/

namespace Import;
require_once (__DIR__ . "/../../Import/AubioPitch.php");

class AubioPitchTest extends \PHPUnit\Framework\TestCase {
    
    public function testAubioPitch() {
        $this->assertEquals(36.09600582608696, AubioPitch::getPitch(__DIR__ . "/../WAV/036-c.wav"));
        $this->assertEquals(47.99804364285713, AubioPitch::getPitch(__DIR__ . "/../WAV/048-c.wav"));
        $this->assertEquals(55.00645842857143, AubioPitch::getPitch(__DIR__ . "/../WAV/055-g.wav"));
        $this->assertEquals(-2.0, AubioPitch::getPitch(__DIR__ . "/../WAV/xxx"));
    }s
}