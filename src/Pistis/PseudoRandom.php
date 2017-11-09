<?php
declare(strict_types=1);

namespace Pistis;

use mersenne_twister\twister;
use OutOfBoundsException;

final class PseudoRandom
{
    /** @var twister */
    private static $twister;

    /** @var int */
    private static $seed;

    /**
     * Manually seed the PRNG
     *
     * Pass an integer to manually seed the pseudo-random number generator. Given the same seed, the same sequence of
     * random values will be returned
     *
     * @throws OutOfBoundsException When method is called more than once
     */
    public static function seed(int $seed): void
    {
        if (self::$seed !== null) {
            throw new OutOfBoundsException('Pseudo-random number generator was already seeded');
        }

        self::$seed = $seed;
    }

    /**
     * Returns seed
     *
     * Will return the seed that is currently used to generate pseudo-random data. If no seed exists yet, it will
     * be automatically set up.
     * Use this method to access the automatically generated seed for build reproduction.
     */
    public static function getSeed(): int
    {
        self::init();

        return self::$seed;
    }

    /**
     * Generates pseudo-random integer
     *
     * Returns integer in between minimum and maximum.
     */
    public static function integer(int $min = 0, int $max = PHP_INT_MAX): int
    {
        return self::twister()->rangeint($min, $max);
    }

    /**
     * Generate pseudo-random hexadecimal string
     *
     * Returns hexadecimal string of a given length.
     */
    public static function hex(int $length = 8): string
    {
        $hex = '';

        while (strlen($hex) < $length) {
            $hex .= dechex(self::integer());
        }

        return substr($hex, 0, $length);
    }

    private static function twister(): twister
    {
        self::init();

        return self::$twister ?: (self::$twister = new twister(self::$seed));
    }

    private static function init(): void
    {
        if (self::$seed !== null) {
            return;
        }

        $seed = filter_var(getenv('PISTIS_SEED'), FILTER_VALIDATE_INT);
        if (is_int($seed)) {
            self::$seed = $seed;

            return;
        }

        self::$seed = self::getRandomSeed();
    }

    private static function getRandomSeed(): int
    {
        return random_int(0, PHP_INT_MAX);
    }
}
