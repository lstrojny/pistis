<?php
namespace Pistis;

use InvalidArgumentException;

final class Time
{
    private static $timestamp;

    public static function seed($timestamp)
    {
        if ($timestamp < 0 || $timestamp > 2147483647) {
            throw new InvalidArgumentException('Timestamp must be between 0 and 2147483647');
        }

        self::$timestamp = $timestamp;
    }

    public static function unixTimestamp()
    {
        self::init();

        return self::$timestamp;
    }

    private static function init()
    {
        if (self::$timestamp !== null) {
            return;
        }

        self::$timestamp = time();
    }
}
