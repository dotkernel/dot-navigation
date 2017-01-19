<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

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
     * @return NavigationMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        $navigation = $container->get(NavigationInterface::class);
        return new NavigationMiddleware($navigation);
    }
}
