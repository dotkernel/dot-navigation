<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation;

use Dot\Navigation\Factory\NavigationMiddlewareFactory;
use Dot\Navigation\Factory\NavigationOptionsFactory;
use Dot\Navigation\Factory\NavigationRendererFactory;
use Dot\Navigation\Factory\NavigationServiceFactory;
use Dot\Navigation\Factory\ProviderPluginManagerFactory;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Provider\ProviderPluginManager;
use Dot\Navigation\Service\Navigation;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\NavigationRenderer;
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
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),

            'dot_navigation' => [

                'active_recursion' => true,

                'containers' => [],

                'provider_manager' => []
            ],
        ];
    }

    /**
     * @return array
     */
    public function getDependencyConfig(): array
    {
        return [
            'factories' => [
                NavigationOptions::class => NavigationOptionsFactory::class,
                ProviderPluginManager::class => ProviderPluginManagerFactory::class,
                Navigation::class => NavigationServiceFactory::class,
                NavigationRenderer::class => NavigationRendererFactory::class,
                NavigationMiddleware::class => NavigationMiddlewareFactory::class,
            ],
            'aliases' => [
                NavigationInterface::class => Navigation::class,
                RendererInterface::class => NavigationRenderer::class,
            ]
        ];
    }
}
