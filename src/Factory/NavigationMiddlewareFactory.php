<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\NavigationMiddleware;
use Dot\Navigation\Service\NavigationInterface;
use Psr\Container\ContainerInterface;

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
