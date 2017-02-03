<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\NavigationMiddleware;
use Dot\Navigation\Service\NavigationInterface;
use Interop\Container\ContainerInterface;

/**
 * Class NavigationMiddlewareFactory
 * @package Dot\Navigation\Factory
 */
class NavigationMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return NavigationMiddleware
     */
    public function __invoke(ContainerInterface $container, $requestedName): NavigationMiddleware
    {
        $navigation = $container->get(NavigationInterface::class);
        return new $requestedName($navigation);
    }
}
