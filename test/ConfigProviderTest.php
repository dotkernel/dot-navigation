<?php

declare(strict_types=1);

namespace DotTest\Navigation;

use Dot\Navigation\ConfigProvider;
use Dot\Navigation\Factory\NavigationMiddlewareFactory;
use Dot\Navigation\Factory\NavigationOptionsFactory;
use Dot\Navigation\Factory\NavigationRendererFactory;
use Dot\Navigation\Factory\NavigationServiceFactory;
use Dot\Navigation\Factory\ProviderPluginManagerFactory;
use Dot\Navigation\NavigationMiddleware;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Provider\Factory;
use Dot\Navigation\Provider\FactoryInterface;
use Dot\Navigation\Provider\ProviderPluginManager;
use Dot\Navigation\Service\Navigation;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\NavigationRenderer;
use Dot\Navigation\View\RendererInterface;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    protected array $config;

    protected function setup(): void
    {
        $this->config = (new ConfigProvider())();
    }

    public function testHasDependencies(): void
    {
        $this->assertArrayHasKey('dependencies', $this->config);
    }

    public function testDependenciesHasFactories(): void
    {
        $this->assertArrayHasKey('factories', $this->config['dependencies']);

        $factories = $this->config['dependencies']['factories'];
        $this->assertArrayHasKey(Navigation::class, $factories);
        $this->assertSame(NavigationServiceFactory::class, $factories[Navigation::class]);
        $this->assertArrayHasKey(NavigationMiddleware::class, $factories);
        $this->assertSame(NavigationMiddlewareFactory::class, $factories[NavigationMiddleware::class]);
        $this->assertArrayHasKey(NavigationOptions::class, $factories);
        $this->assertSame(NavigationOptionsFactory::class, $factories[NavigationOptions::class]);
        $this->assertArrayHasKey(NavigationRenderer::class, $factories);
        $this->assertSame(NavigationRendererFactory::class, $factories[NavigationRenderer::class]);
        $this->assertArrayHasKey(ProviderPluginManager::class, $factories);
        $this->assertSame(ProviderPluginManagerFactory::class, $factories[ProviderPluginManager::class]);
    }

    public function testDependenciesHasAliases(): void
    {
        $this->assertArrayHasKey('aliases', $this->config['dependencies']);

        $aliases = $this->config['dependencies']['aliases'];
        $this->assertArrayHasKey(FactoryInterface::class, $aliases);
        $this->assertSame(Factory::class, $aliases[FactoryInterface::class]);
        $this->assertArrayHasKey(NavigationInterface::class, $aliases);
        $this->assertSame(Navigation::class, $aliases[NavigationInterface::class]);
        $this->assertArrayHasKey(RendererInterface::class, $aliases);
        $this->assertSame(NavigationRenderer::class, $aliases[RendererInterface::class]);
    }
}
