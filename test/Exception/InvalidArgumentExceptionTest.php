<?php

declare(strict_types=1);

namespace DotTest\Navigation\Exception;

use Dot\Navigation\Exception\ExceptionInterface;
use Dot\Navigation\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class InvalidArgumentExceptionTest extends TestCase
{
    public function testWillReturnCorrectInstances(): void
    {
        $exception = new InvalidArgumentException('test');
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
