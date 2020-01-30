<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Service;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;
use Mezzio\Router\RouteResult;

/**
 * Interface NavigationInterface
 * @package Dot\Navigation\Service
 */
interface NavigationInterface
{
    /**
     * @param RouteResult $routeResult
     */
    public function setRouteResult(RouteResult $routeResult);

    /**
     * @param $isActiveRecursion
     */
    public function setIsActiveRecursion(bool $isActiveRecursion);

    /**
     * @param $name
     * @return NavigationContainer
     */
    public function getContainer(string $name): NavigationContainer;

    /**
     * @param Page $page
     * @return bool
     */
    public function isAllowed(Page $page): bool;

    /**
     * @param Page $page
     * @return bool
     */
    public function isActive(Page $page): bool;

    /**
     * @param Page $page
     * @return string
     */
    public function getHref(Page $page): string;
}
