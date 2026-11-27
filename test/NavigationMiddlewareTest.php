<?php

declare(strict_types=1);

namespace DotTest\Navigation;

use Dot\Navigation\NavigationMiddleware;
use Dot\Navigation\Service\NavigationInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NavigationMiddlewareTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testWillCreateMiddleware(): void
    {
        $navigation = $this->createStub(NavigationInterface::class);

        $middleware = new NavigationMiddleware($navigation);
        $this->assertSame(NavigationMiddleware::class, $middleware::class);
    }

    /**
     * @throws Exception
     */
    public function testWillProcessRequest(): void
    {
        $navigation = $this->createStub(NavigationInterface::class);
        $request    = $this->createStub(ServerRequestInterface::class);
        $handler    = $this->createStub(RequestHandlerInterface::class);

        $middleware = new NavigationMiddleware($navigation);
        $response   = $middleware->process($request, $handler);
        $this->assertContainsOnlyInstancesOf(ResponseInterface::class, [$response]);
    }
}
