<?php
namespace Pistis;

use Exception;
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
     * @param int $seed
     * @throws OutOfBoundsException If seed is already initialized
     */
    public static function seed($seed)
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
     *
     * @throws Exception If seed could not be initialized
     * @return int
     */
    public static function getSeed()
    {
        self::init();

        return self::$seed;
    }

    /**
     * Generates pseudo-random integer
     *
     * Returns integer in between minimum and maximum.
     *
     * @param int $min Lower bound of the random number
     * @param int $max Upper bound of the random number
     * @throws Exception If seed could not be initialized
     * @return int
     */
    public static function integer($min = 0, $max = PHP_INT_MAX)
    {
        return self::twister()->rangeint($min, $max);
    }

    /**
     * Generate pseudo-random hexadecimal string
     *
     * Returns hexadecimal string of a given length.
     *
     * @param int $length Length of the hexadecimal string
     * @return string
     */
    public static function hex($length = 8)
    {
        $hex = '';

        while (strlen($hex) < $length) {
            $hex .= dechex(self::integer());
        }

        return substr($hex, 0, $length);
    }

    /** @return twister
     * @throws Exception
     */
    private static function twister()
    {
        self::init();

        return self::$twister ?: (self::$twister = new twister(self::$seed));
    }

    /**
     * Ensure seed is initialized
     *
     * Initializes the seed if not yet initialized. Attempts to read seed from environment variable.
     *
     * @throws Exception
     * @return void
     */
    private static function init()
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

    /**
     * @return int
     * @throws Exception
     */
    private static function getRandomSeed()
    {
        return random_int(0, PHP_INT_MAX);
    }
}
