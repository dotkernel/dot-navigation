<?php

declare(strict_types=1);

namespace DotTest\Navigation;

use Dot\Navigation\NavigationMiddleware;
use Dot\Navigation\Service\NavigationInterface;
use Mezzio\Router\RouteResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NavigationMiddlewareTest extends TestCase
{
    public function testWillPassRouteResultAndQueryParamsToNavigation(): void
    {
        $routeResult = RouteResult::fromRouteFailure([]);
        $response    = $this->createStub(ResponseInterface::class);

        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getAttribute')->willReturnMap([[RouteResult::class, null, $routeResult]]);
        $request->method('getQueryParams')->willReturn(['page' => '2']);

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response);

        $navigation = $this->createMock(NavigationInterface::class);
        $navigation->expects($this->once())->method('setRouteResult')->with($routeResult);
        $navigation->expects($this->once())->method('setQueryParams')->with(['page' => '2']);

        $middleware = new NavigationMiddleware($navigation);
        $this->assertSame($response, $middleware->process($request, $handler));
    }

    public function testWillNotSetRouteResultWhenRequestHasNone(): void
    {
        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn([]);

        $navigation = $this->createMock(NavigationInterface::class);
        $navigation->expects($this->never())->method('setRouteResult');
        $navigation->expects($this->once())->method('setQueryParams')->with([]);

        $middleware = new NavigationMiddleware($navigation);
        $middleware->process($request, $this->createStub(RequestHandlerInterface::class));
    }
}
