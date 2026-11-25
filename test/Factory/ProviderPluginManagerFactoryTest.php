<?php

declare(strict_types=1);

namespace DotTest\Navigation\Factory;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Factory\ProviderPluginManagerFactory;
use Dot\Navigation\Provider\InvokableProviderFactory;
use DotTest\Navigation\TestData\Container;
use DotTest\Navigation\TestData\CustomProvider;
use PHPUnit\Framework\TestCase;

class ProviderPluginManagerFactoryTest extends TestCase
{
    public function testWillNotCreateProviderPluginManagerWithoutConfig(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(ProviderPluginManagerFactory::MESSAGE_MISSING_CONFIG);
        (new ProviderPluginManagerFactory())(new Container());
    }

    public function testWillNotCreateProviderPluginManagerWhenConfigIsNotAnArray(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(ProviderPluginManagerFactory::MESSAGE_MISSING_CONFIG);
        (new ProviderPluginManagerFactory())(new Container(['config' => 'invalid']));
    }

    public function testWillNotCreateProviderPluginManagerWithoutPackageConfig(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(ProviderPluginManagerFactory::MESSAGE_MISSING_PACKAGE_CONFIG);
        (new ProviderPluginManagerFactory())(new Container(['config' => []]));
    }

    public function testWillNotCreateProviderPluginManagerWithoutConfigProviderManager(): void
    {
        $container = new Container([
            'config' => [
                'dot_navigation' => [
                    'active_recursion' => true,
                ],
            ],
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(ProviderPluginManagerFactory::MESSAGE_MISSING_CONFIG_PROVIDER_MANAGER);
        (new ProviderPluginManagerFactory())($container);
    }

    public function testWillCreateProviderPluginManagerWithConfig(): void
    {
        $container = new Container([
            'config' => [
                'dot_navigation' => [
                    'provider_manager' => [
                        'factories' => [
                            CustomProvider::class => InvokableProviderFactory::class,
                        ],
                    ],
                ],
            ],
        ]);

        $manager = (new ProviderPluginManagerFactory())($container);
                $this->assertTrue($manager->has(CustomProvider::class));
    }
}
