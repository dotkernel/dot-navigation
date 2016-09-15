<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/7/2016
 * Time: 9:18 PM
 */

namespace Dot\Navigation\Factory;

use Dot\Navigation\NavigationMiddleware;
use Dot\Navigation\NavigationService;
use Interop\Container\ContainerInterface;

class NavigationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $navigation = $container->get(NavigationService::class);
        return new NavigationMiddleware($navigation);
    }
}