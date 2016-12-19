<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation;

use Dot\Navigation\Factory\NavigationMiddlewareFactory;
use Dot\Navigation\Factory\NavigationOptionsFactory;
use Dot\Navigation\Factory\NavigationRendererFactory;
use Dot\Navigation\Factory\NavigationServiceFactory;
use Dot\Navigation\Factory\ProviderPluginManagerFactory;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Provider\ProviderPluginManager;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\RendererInterface;

/**
 * Class ConfigProvider
 * @package Dot\Navigation
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [

            'dependencies' => $this->getDependencyConfig(),

            'dot_navigation' => [

                'menu_options' => [

                    'min_depth' => -1,
                    'max_depth' => -1,

                    'active_class' => 'active',
                    'ul_class' => 'nav',
                ],

                'active_recursion' => true,

                'containers' => [

                ],

                'providers_map' => [

                ],

                'provider_manager' => [

                ]
            ],
        ];
    }

    /**
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'factories' => [
                NavigationOptions::class => NavigationOptionsFactory::class,

                ProviderPluginManager::class => ProviderPluginManagerFactory::class,

                NavigationInterface::class => NavigationServiceFactory::class,

                RendererInterface::class => NavigationRendererFactory::class,

                NavigationMiddleware::class => NavigationMiddlewareFactory::class,
            ],
        ];
    }
}