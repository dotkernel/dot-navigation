<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 6/5/2016
 * Time: 5:01 PM
 */

namespace Dot\Navigation;

use Dot\Navigation\Factory\NavigationOptionsFactory;
use Dot\Navigation\Factory\NavigationMenuFactory;
use Dot\Navigation\Factory\NavigationMenuOptionsFactory;
use Dot\Navigation\Factory\NavigationMiddlewareFactory;
use Dot\Navigation\Factory\NavigationServiceFactory;
use Dot\Navigation\Factory\ProviderPluginManagerFactory;
use Dot\Navigation\Helper\NavigationMenu;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Options\NavigationMenuOptions;
use Dot\Navigation\Provider\ProviderPluginManager;
use Dot\Navigation\Twig\NavigationExtension;
use Dot\Navigation\Twig\NavigationExtensionFactory;
use Zend\ServiceManager\Proxy\LazyServiceFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [

            'dependencies' => [
                'factories' => [
                    NavigationOptions::class => NavigationOptionsFactory::class,

                    NavigationMenuOptions::class => NavigationMenuOptionsFactory::class,

                    ProviderPluginManager::class => ProviderPluginManagerFactory::class,

                    NavigationService::class => NavigationServiceFactory::class,

                    NavigationMenu::class => NavigationMenuFactory::class,

                    NavigationExtension::class => NavigationExtensionFactory::class,

                    NavigationMiddleware::class => NavigationMiddlewareFactory::class,
                ],

                'delegators' => [
                    NavigationMenu::class => [
                        LazyServiceFactory::class,
                    ]
                ],

                'lazy_services' => [
                    'class_map' => [
                        NavigationMenu::class => NavigationMenu::class,
                    ]
                ]
            ],

            'dk_navigation' => [

                'active_recursion' => true,

                'min_depth' => -1,
                'max_depth' => -1,
                'active_class' => 'active',
                'ul_class' => 'nav',

                'containers' => [

                ],

                'providers_map' => [

                ],

                'provider_manager' => [

                ]
            ],

            'twig' => [
                'extensions' => [
                    NavigationExtension::class,
                ]
            ]
        ];
    }
}