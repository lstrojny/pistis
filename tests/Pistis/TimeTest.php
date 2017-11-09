<?php
namespace Pistis\Tests;

use Pistis\Time;

/** @runTestsInSeparateProcesses */
class TimeTest extends AbstractTestCase
{
    public function testTimeWithoutSeeding()
    {
        $timestamp = Time::unixTimestamp();
        $this->assertInternalType('integer', $timestamp);
        $this->assertGreaterThanOrEqual(0, $timestamp);
        $this->assertLessThanOrEqual(2147483647, $timestamp);
    }

    public function testTimeWithSeeding()
    {
        Time::seed(2147483647);

        $this->assertSame(2147483647, Time::unixTimestamp());
    }
}
