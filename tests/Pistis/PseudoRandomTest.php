<?php
namespace Pistis\Tests;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use Pistis\PseudoRandom;

/** @runTestsInSeparateProcesses  */
class PseudoRandomTest extends TestCase
{
    public function testThrowsExceptionIfAlreadySeeded(): void
    {
        PseudoRandom::seed(0);

        $this->expectException(OutOfBoundsException::class);
        PseudoRandom::seed(0);
    }

    public function testAutomaticallySeeded(): void
    {
        self::assertInternalType('integer', PseudoRandom::getSeed());
    }

    public function testReproducibleInteger(): void
    {
        PseudoRandom::seed(0xDEADBEEF);

        self::assertSame(7279275878918617725, PseudoRandom::integer());
        self::assertSame(7916426415558122606, PseudoRandom::integer());
        self::assertSame(2559059685097008492, PseudoRandom::integer());
        self::assertSame(7199328709112801378, PseudoRandom::integer());
    }

    public function testReproducibleHex(): void
    {
        PseudoRandom::seed(0xDEADBEEF);

        self::assertSame('65052ed8', PseudoRandom::hex());
        self::assertSame('6ddccbe1c5dc5c6e', PseudoRandom::hex(16));
        self::assertSame('23839b39a13aed6c63e9275137f0a862', PseudoRandom::hex(32));
        self::assertSame('6c6c241dc001604214a7dc558f283c125ec675b12dbdac596b813ae4a4eb5ff7', PseudoRandom::hex(64));
    }

    public function testSeedCanBeSetFromEnvironmentVariable(): void
    {
        putenv('PISTIS_SEED=10');

        self::assertSame('4c7b9c7d', PseudoRandom::hex());
    }

    public function testTrivialCollisions(): void
    {
        $found = [];

        for ($a = 0; $a < 1000000000; $a++) {
            $random = PseudoRandom::integer();

            if (in_array($random, $found, true)) {
                $this->fail(sprintf('%d generated twice', $random));
            }

            $found[] = $random;

            if ($a % 1000000 === 0) {
                echo "Round $a\n";
            }
        }
    }
}
