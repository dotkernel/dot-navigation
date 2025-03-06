<?php

declare(strict_types=1);

namespace DotTest\Navigation\Provider;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Provider\ArrayProvider;
use Dot\Navigation\Provider\Factory;
use Dot\Navigation\Provider\ProviderInterface;
use Dot\Navigation\Provider\ProviderPluginManager;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class FactoryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testWillCreateFactoryWithoutProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $factory = new Factory($container);
        $this->assertSame(Factory::class, $factory::class);
    }

    /**
     * @throws Exception
     */
    public function testWillCreateFactoryWithProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $manager   = $this->createMock(ProviderPluginManager::class);

        $factory = new Factory($container, $manager);
        $this->assertSame(Factory::class, $factory::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function testFactoryWillNotCreateProviderWithoutProviderType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Undefined navigation provider type');
        $factory = new Factory($container);
        $factory->create([]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function testFactoryWillNotCreateProviderWithInvalidProviderType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage(
            'Unable to resolve service "test" to a factory; are you certain you provided it during configuration?'
        );
        $factory = new Factory($container);
        $factory->create([
            'type' => 'test',
        ]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function testFactoryWillCreateProviderWithValidProviderTypeAndNoOptions(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $factory  = new Factory($container);
        $provider = $factory->create([
            'type' => ArrayProvider::class,
        ]);
        $this->assertContainsOnlyInstancesOf(ProviderInterface::class, [$provider]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function testFactoryWillCreateProviderWithValidProviderTypeAndOptions(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $factory  = new Factory($container);
        $provider = $factory->create([
            'type'    => ArrayProvider::class,
            'options' => [],
        ]);
        $this->assertContainsOnlyInstancesOf(ProviderInterface::class, [$provider]);
    }

    /**
     * @throws Exception
     */
    public function testFactoryWillGetProviderPluginManagerWithoutInitialProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $factory = new Factory($container);
        $this->assertSame(ProviderPluginManager::class, $factory->getProviderPluginManager()::class);
    }

    /**
     * @throws Exception
     */
    public function testFactoryWillGetProviderPluginManagerWithInitialProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $manager   = $this->createMock(ProviderPluginManager::class);

        $factory = new Factory($container, $manager);
        $this->assertContainsOnlyInstancesOf(ProviderPluginManager::class, [$factory->getProviderPluginManager()]);
    }
}
