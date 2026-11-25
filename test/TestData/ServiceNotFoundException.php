<?php

declare(strict_types=1);

namespace DotTest\Navigation\TestData;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class ServiceNotFoundException extends RuntimeException implements NotFoundExceptionInterface
{
}
