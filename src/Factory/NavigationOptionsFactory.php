<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 6/5/2016
 * Time: 4:20 PM
 */

namespace Dot\Navigation\Factory;

use Dot\Navigation\Options\NavigationOptions;
use Interop\Container\ContainerInterface;

class NavigationOptionsFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['dot_navigation'];
        return new NavigationOptions($config);
    }
}