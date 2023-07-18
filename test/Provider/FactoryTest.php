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
use Psr\Container\ContainerInterface;

use function sprintf;

class FactoryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testWillCreateFactoryWithoutProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $factory = new Factory($container);
        $this->assertInstanceOf(Factory::class, $factory);
    }

    /**
     * @throws Exception
     */
    public function testWillCreateFactoryWithProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $manager   = $this->createMock(ProviderPluginManager::class);

        $factory = new Factory($container, $manager);
        $this->assertInstanceOf(Factory::class, $factory);
    }

    /**
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
     * @throws Exception
     */
    public function testFactoryWillNotCreateProviderWithInvalidProviderType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage(
            sprintf(
                'A plugin by the name "test" was not found in the plugin manager %s',
                ProviderPluginManager::class
            )
        );
        $factory = new Factory($container);
        $factory->create([
            'type' => 'test',
        ]);
    }

    /**
     * @throws Exception
     */
    public function testFactoryWillCreateProviderWithValidProviderTypeAndNoOptions(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $factory  = new Factory($container);
        $provider = $factory->create([
            'type' => ArrayProvider::class,
        ]);
        $this->assertInstanceOf(ProviderInterface::class, $provider);
    }

    /**
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
        $this->assertInstanceOf(ProviderInterface::class, $provider);
    }

    /**
     * @throws Exception
     */
    public function testFactoryWillGetProviderPluginManagerWithoutInitialProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $factory = new Factory($container);
        $this->assertInstanceOf(ProviderPluginManager::class, $factory->getProviderPluginManager());
    }

    /**
     * @throws Exception
     */
    public function testFactoryWillGetProviderPluginManagerWithInitialProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $manager   = $this->createMock(ProviderPluginManager::class);

        $factory = new Factory($container, $manager);
        $this->assertInstanceOf(ProviderPluginManager::class, $factory->getProviderPluginManager());
    }
}
