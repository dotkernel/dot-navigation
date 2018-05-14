<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation;

use Dot\Navigation\Service\NavigationInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Expressive\Router\RouteResult;

/**
 * Class NavigationMiddleware
 * @package Dot\Navigation
 */
class NavigationMiddleware implements MiddlewareInterface
{
    /**
     * @var NavigationInterface
     */
    protected $navigation;

    /**
     * NavigationMiddleware constructor.
     * @param NavigationInterface $navigation
     */
    public function __construct(NavigationInterface $navigation)
    {
        $this->navigation = $navigation;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class, null);
        if ($routeResult) {
            $this->navigation->setRouteResult($routeResult);
        }

        return $delegate->process($request);
    }
}
