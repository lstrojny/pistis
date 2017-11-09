<?php
namespace Pistis\Tests;

use PHPUnit\Framework\TestCase;

/** @method void expectException(string $exception) */
abstract class AbstractTestCase extends TestCase
{
    public function __call($method, array $arguments)
    {
        if ($method === 'expectException') {
            $this->setExpectedException($arguments[0]);
        }
    }
}
