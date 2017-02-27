<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation;

use Dot\Navigation\Service\NavigationInterface;
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
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ): ResponseInterface {
        $routeResult = $request->getAttribute(RouteResult::class, null);
        if ($routeResult) {
            $this->navigation->setRouteResult($routeResult);
        }
        
        return $next($request, $response);
    }
}
