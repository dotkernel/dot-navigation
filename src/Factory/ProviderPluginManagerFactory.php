<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation\Factory;

use Dot\Navigation\Provider\ProviderPluginManager;
use Interop\Container\ContainerInterface;

/**
 * Class ProviderPluginManagerFactory
 * @package Dot\Navigation\Factory
 */
class ProviderPluginManagerFactory
{
    /**
     * @param ContainerInterface $container
     * @return ProviderPluginManager
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['dot_navigation']['provider_manager'];
        return new ProviderPluginManager($container, $config);
    }
}