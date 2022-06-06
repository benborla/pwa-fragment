<?php

namespace App\Tests\DataSource;

use PHPUnit\Framework\TestCase;
use App\DataSource\Random;

class RandomTest extends TestCase
{
    public function testShouldReturnRandomInRange()
    {
        $rand = new Random();

        for ($i = 0; $i < 10; $i++) {
            $this->assertGreaterThan(10, $rand->get(11, 20));
            $this->assertLessThan(10, $rand->get(0, 9));
        }
    }

    public function testShouldReturnPredictable()
    {
        $rand = new Random();

        $this->assertEquals(1, $rand->get(1, 1));
    }
}
