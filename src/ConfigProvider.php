<?php

declare(strict_types=1);

namespace Dot\Navigation;

use Dot\Navigation\Factory\NavigationMiddlewareFactory;
use Dot\Navigation\Factory\NavigationOptionsFactory;
use Dot\Navigation\Factory\NavigationRendererFactory;
use Dot\Navigation\Factory\NavigationServiceFactory;
use Dot\Navigation\Factory\ProviderPluginManagerFactory;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Provider\Factory;
use Dot\Navigation\Provider\FactoryInterface;
use Dot\Navigation\Provider\ProviderPluginManager;
use Dot\Navigation\Service\Navigation;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\NavigationRenderer;
use Dot\Navigation\View\RendererInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies'   => $this->getDependencyConfig(),
            'dot_navigation' => [
                'active_recursion' => true,
                'containers'       => [],
                'provider_manager' => [],
            ],
        ];
    }

    public function getDependencyConfig(): array
    {
        return [
            'factories' => [
                Navigation::class            => NavigationServiceFactory::class,
                NavigationMiddleware::class  => NavigationMiddlewareFactory::class,
                NavigationOptions::class     => NavigationOptionsFactory::class,
                NavigationRenderer::class    => NavigationRendererFactory::class,
                ProviderPluginManager::class => ProviderPluginManagerFactory::class,
            ],
            'aliases'   => [
                FactoryInterface::class    => Factory::class,
                NavigationInterface::class => Navigation::class,
                RendererInterface::class   => NavigationRenderer::class,
            ],
        ];
    }
}
