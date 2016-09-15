<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 6/5/2016
 * Time: 5:27 PM
 */

namespace Dot\Navigation\Factory;

use Dot\Navigation\Provider\ProviderPluginManager;
use Interop\Container\ContainerInterface;

class ProviderPluginManagerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config')['dk_navigation']['provider_manager'];

        return new ProviderPluginManager($container, $config);
    }
}