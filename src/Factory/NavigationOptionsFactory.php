<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation\Factory;

use Dot\Navigation\Options\NavigationOptions;
use Interop\Container\ContainerInterface;

/**
 * Class NavigationOptionsFactory
 * @package Dot\Navigation\Factory
 */
class NavigationOptionsFactory
{
    /**
     * @param ContainerInterface $container
     * @return NavigationOptions
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['dot_navigation'];
        return new NavigationOptions($config);
    }
}