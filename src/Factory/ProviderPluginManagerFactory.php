<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\Provider\ProviderPluginManager;
use Psr\Container\ContainerInterface;

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
