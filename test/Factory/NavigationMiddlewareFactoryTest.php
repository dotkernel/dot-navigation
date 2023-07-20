<?php

declare(strict_types=1);

namespace DotTest\Navigation\Factory;

use Dot\Navigation\Factory\NavigationMiddlewareFactory;
use Dot\Navigation\NavigationMiddleware;
use Dot\Navigation\Service\NavigationInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class NavigationMiddlewareFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateMiddlewareWithoutNavigationInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('has')
            ->with(NavigationInterface::class)
            ->willReturn(false);

        $this->expectExceptionMessage(NavigationMiddlewareFactory::MESSAGE_MISSING_NAVIGATION);
        (new NavigationMiddlewareFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillCreateMiddleware(): void
    {
        $container  = $this->createMock(ContainerInterface::class);
        $navigation = $this->createMock(NavigationInterface::class);

        $container->expects($this->once())
            ->method('has')
            ->with(NavigationInterface::class)
            ->willReturn(true);

        $container->expects($this->once())
            ->method('get')
            ->with(NavigationInterface::class)
            ->willReturn($navigation);

        $middleware = (new NavigationMiddlewareFactory())($container);
        $this->assertInstanceOf(NavigationMiddleware::class, $middleware);
    }
}
