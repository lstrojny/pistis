<?php
namespace Pistis\Tests;

use OutOfBoundsException;
use Pistis\Clock;

/** @runTestsInSeparateProcesses */
class ClockTest extends AbstractTestCase
{
    public function testTimeWithoutSeeding()
    {
        $timestamp = Clock::unixTimestamp();
        self::assertInternalType('integer', $timestamp);
        self::assertGreaterThanOrEqual(0, $timestamp);
        self::assertLessThanOrEqual(2147483647, $timestamp);
    }

    public function testThrowsExceptionIfTimeIsSeededMultipleTimes()
    {
        Clock::seed(0);

        $this->expectException(OutOfBoundsException::class);
        Clock::seed(0);
    }

    public function testSeedIsReturned()
    {
        Clock::seed(1);
        self::assertSame(1, Clock::getSeed());
    }

    public function invalidTimestampProvider()
    {
        return [
            [-1],
            [2147483648],
        ];
    }

    /**
     * @dataProvider invalidTimestampProvider
     * @expectedException \InvalidArgumentException
     */
    public function testSeedWithInvalidTimestamp($invalidTimestamp)
    {
        Clock::seed($invalidTimestamp);
    }

    public function testGetSeedIsImplicit()
    {
        self::assertNotNull(Clock::getSeed());
    }

    public function testTimeWithSeeding()
    {
        Clock::seed(2147483647);

        self::assertSame(2147483647, Clock::unixTimestamp());
    }

    public function testSeedFromEnvironment()
    {
        putenv('PISTIS_TIME=0');

        self::assertSame(0, Clock::unixTimestamp());
    }
}
