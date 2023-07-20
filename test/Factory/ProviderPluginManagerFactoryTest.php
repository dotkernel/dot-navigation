<?php

declare(strict_types=1);

namespace DotTest\Navigation\Factory;

use Dot\Navigation\Factory\ProviderPluginManagerFactory;
use Dot\Navigation\Provider\ProviderPluginManager;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProviderPluginManagerFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateProviderPluginManagerWithoutConfig(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('has')
            ->with('config')
            ->willReturn(false);

        $this->expectExceptionMessage(ProviderPluginManagerFactory::MESSAGE_MISSING_CONFIG);
        (new ProviderPluginManagerFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateProviderPluginManagerWithoutPackageConfig(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('has')
            ->with('config')
            ->willReturn(true);

        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn([]);

        $this->expectExceptionMessage(ProviderPluginManagerFactory::MESSAGE_MISSING_PACKAGE_CONFIG);
        (new ProviderPluginManagerFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateProviderPluginManagerWithoutConfigProviderManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('has')
            ->with('config')
            ->willReturn(true);

        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn([
                'dot_navigation' => [
                    'active_recursion' => true,
                ],
            ]);

        $this->expectExceptionMessage(ProviderPluginManagerFactory::MESSAGE_MISSING_CONFIG_PROVIDER_MANAGER);
        (new ProviderPluginManagerFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillCreateProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('has')
            ->with('config')
            ->willReturn(true);

        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn([
                'dot_navigation' => [
                    'provider_manager' => [],
                ],
            ]);

        $manager = (new ProviderPluginManagerFactory())($container);
        $this->assertInstanceOf(ProviderPluginManager::class, $manager);
    }
}
