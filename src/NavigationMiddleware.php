<?php

declare(strict_types=1);

namespace Dot\Navigation;

use Dot\Navigation\Service\NavigationInterface;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NavigationMiddleware implements MiddlewareInterface
{
    protected NavigationInterface $navigation;

    public function __construct(NavigationInterface $navigation)
    {
        $this->navigation = $navigation;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class);
        if ($routeResult instanceof RouteResult) {
            $this->navigation->setRouteResult($routeResult);
        }

        return $handler->handle($request);
    }
}
