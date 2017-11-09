<?php
namespace Pistis;

use InvalidArgumentException;

final class Clock
{
    private static $seed;

    /**
     * Manually seed the clock
     *
     * Set the clock to the unix timestamp passed to the method. Double seeding is forbidden
     *
     * @param int $timestamp
     */
    public static function seed($timestamp)
    {
        if (self::$seed !== null) {
            throw new \OutOfBoundsException('Timestamp seed already set');
        }

        if ($timestamp < 0 || $timestamp > 2147483647) {
            throw new InvalidArgumentException('Timestamp must be between 0 and 2147483647');
        }

        self::$seed = $timestamp;
    }

    /**
     * Returns clock seed
     *
     * @return int
     */
    public static function getSeed()
    {
        self::init();

        return self::$seed;
    }

    /**
     * Returns UNIX timestamp
     *
     * Return unix timestamp as integer
     *
     * @return int
     */
    public static function unixTimestamp()
    {
        self::init();

        return self::$seed;
    }

    /**
     * @return void
     */
    private static function init()
    {
        if (self::$seed !== null) {
            return;
        }

        $timestamp = filter_var(getenv('PISTIS_TIME'), FILTER_VALIDATE_INT);
        if ($timestamp !== false) {
            self::seed($timestamp);

            return;
        }


        self::$seed = time();
    }
}
