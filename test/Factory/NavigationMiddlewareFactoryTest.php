<?php

declare(strict_types=1);

namespace DotTest\Navigation\Factory;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Factory\NavigationMiddlewareFactory;
use Dot\Navigation\NavigationMiddleware;
use Dot\Navigation\Service\NavigationInterface;
use DotTest\Navigation\TestData\Container;
use PHPUnit\Framework\TestCase;

class NavigationMiddlewareFactoryTest extends TestCase
{
    public function testWillNotCreateMiddlewareWithoutNavigationInterface(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationMiddlewareFactory::MESSAGE_MISSING_NAVIGATION);
        (new NavigationMiddlewareFactory())(new Container());
    }

    public function testWillCreateMiddleware(): void
    {
        $container = new Container([
            NavigationInterface::class => $this->createStub(NavigationInterface::class),
        ]);

        $middleware = (new NavigationMiddlewareFactory())($container);
        $this->assertSame(NavigationMiddleware::class, $middleware::class);
    }
}
