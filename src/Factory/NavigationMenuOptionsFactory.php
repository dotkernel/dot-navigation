<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/7/2016
 * Time: 6:26 PM
 */

namespace Dot\Navigation\Factory;

use Dot\Navigation\Options\NavigationMenuOptions;
use Interop\Container\ContainerInterface;

class NavigationMenuOptionsFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['dot_navigation'];
        return new NavigationMenuOptions($config);
    }
}