<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/7/2016
 * Time: 9:15 PM
 */

namespace Dot\Navigation;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;

class NavigationMiddleware
{
    /**
     * @var NavigationService
     */
    protected $navigation;

    /**
     * NavigationMiddleware constructor.
     * @param NavigationService $navigation
     */
    public function __construct(NavigationService $navigation)
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