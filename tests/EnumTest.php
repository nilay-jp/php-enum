<?php

namespace jp\nilay\enum\tests;

use jp\nilay\enum\tests\model\Height;
use jp\nilay\enum\tests\model\HeightEx;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function test_name(): void
    {
        $this->assertSame("EXTRA_HIGH", HeightEx::EXTRA_HIGH()->name());
        $this->assertSame("HIGH", HeightEx::HIGH()->name());
        $this->assertSame("MEDIUM", HeightEx::MEDIUM()->name());
        $this->assertSame("LOW", HeightEx::LOW()->name());
        $this->assertSame("EXTRA_LOW", HeightEx::EXTRA_LOW()->name());
    }

    public function test_equals(): void
    {
        $this->assertTrue(HeightEx::EXTRA_HIGH()->equals(HeightEx::EXTRA_HIGH()));
        $this->assertFalse(HeightEx::EXTRA_HIGH()->equals(HeightEx::HIGH()));
        $this->assertFalse(HeightEx::HIGH()->equals(Height::HIGH()));
    }

    public function test_values(): void
    {
        $actual = HeightEx::values();
        $expected = [
            HeightEx::EXTRA_HIGH(),
            HeightEx::HIGH(),
            HeightEx::MEDIUM(),
            HeightEx::LOW(),
            HeightEx::EXTRA_LOW(),
        ];

        sort($actual);
        sort($expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_valueOf(): void
    {
        $this->assertEquals(HeightEx::HIGH(), HeightEx::valueOf("HIGH"));
        $this->assertNotEquals(HeightEx::HIGH(), HeightEx::valueOf("MEDIUM"));
        $this->assertNotEquals(HeightEx::HIGH(), HeightEx::valueOf("LOW"));

        $this->assertEquals(HeightEx::MEDIUM(), HeightEx::valueOf("MEDIUM"));
        $this->assertNotEquals(HeightEx::MEDIUM(), HeightEx::valueOf("HIGH"));
        $this->assertNotEquals(HeightEx::MEDIUM(), HeightEx::valueOf("LOW"));

        $this->assertEquals(HeightEx::LOW(), HeightEx::valueOf("LOW"));
        $this->assertNotEquals(HeightEx::LOW(), HeightEx::valueOf("HIGH"));
        $this->assertNotEquals(HeightEx::LOW(), HeightEx::valueOf("MEDIUM"));
    }

    public function test_exeption_badMethod(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $h = HeightEx::BAD_METHOD();
    }

    public function test_exception_valueOf(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $h = HeightEx::valueOf("INVALID_NAME");
    }
}
