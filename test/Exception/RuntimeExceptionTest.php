<?php

declare(strict_types=1);

namespace DotTest\Navigation\Exception;

use Dot\Navigation\Exception\ExceptionInterface;
use Dot\Navigation\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

class RuntimeExceptionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testWillReturnCorrectInstances(): void
    {
        $exception = new RuntimeException('test');
        $this->assertInstanceOf(RuntimeException::class, $exception);
        $this->assertInstanceOf(ExceptionInterface::class, $exception);
    }
}
