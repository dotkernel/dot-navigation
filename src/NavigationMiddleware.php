<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation;

use Dot\Navigation\Service\Navigation;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;

/**
 * Class NavigationMiddleware
 * @package Dot\Navigation
 */
class NavigationMiddleware
{
    /**
     * @var Navigation
     */
    protected $navigation;

    /**
     * NavigationMiddleware constructor.
     * @param Navigation $navigation
     */
    public function __construct(Navigation $navigation)
    {
        $this->navigation = $navigation;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $routeResult = $request->getAttribute(RouteResult::class, null);

        $this->navigation->setRouteResult($routeResult);

        return $next($request, $response);
    }
}