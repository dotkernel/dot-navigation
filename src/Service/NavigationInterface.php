<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 10/5/2016
 * Time: 8:17 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation\Service;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;
use Zend\Expressive\Router\RouteResult;

/**
 * Interface NavigationInterface
 * @package Dot\Navigation\Service
 */
interface NavigationInterface
{
    /**
     * @param RouteResult $routeResult
     * @return mixed
     */
    public function setRouteResult(RouteResult $routeResult);

    /**
     * @param $isActiveRecursion
     * @return mixed
     */
    public function setIsActiveRecursion($isActiveRecursion);

    /**
     * @param $name
     * @return NavigationContainer
     */
    public function getContainer($name);

    /**
     * @param Page $page
     * @return bool
     */
    public function isAllowed(Page $page);

    /**
     * @param Page $page
     * @return bool
     */
    public function isActive(Page $page);

    /**
     * @param Page $page
     * @return string
     */
    public function getHref(Page $page);
}
