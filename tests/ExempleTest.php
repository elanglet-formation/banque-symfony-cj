<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class ExempleTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        echo "Avant toutes les tests...";
    }
    
    public static function tearDownAfterClass(): void
    {
        echo "\nAprès toutes les tests...";
    }
    
    public function setUp(): void
    {
        echo "\nAvant chaque test...";
    }

    public function tearDown(): void
    {
        echo "\nAprès chaque test...";
    }

    public function test1(): void
    {
        echo "\nTest1...";
        $this->assertTrue(true);
    }
    
    public function test2(): void
    {
        echo "\nTest2...";
        $this->assertTrue(true);
    }
}
