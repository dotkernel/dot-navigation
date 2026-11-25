<?php

declare(strict_types=1);

namespace DotTest\Navigation\Provider;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Provider\ArrayProvider;
use Dot\Navigation\Provider\Factory;
use Dot\Navigation\Provider\ProviderPluginManager;
use DotTest\Navigation\TestData\Container;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testFactoryWillNotCreateProviderWithoutProviderType(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Undefined navigation provider type');
        (new Factory(new Container()))->create([]);
    }

    public function testFactoryWillNotCreateProviderWithInvalidProviderType(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Navigation provider "test" is not registered');
        (new Factory(new Container()))->create([
            'type' => 'test',
        ]);
    }

    public function testFactoryWillNotCreateProviderWithInvalidOptions(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The navigation provider options must be an array');
        (new Factory(new Container()))->create([
            'type'    => ArrayProvider::class,
            'options' => 'invalid',
        ]);
    }

    public function testFactoryWillCreateProviderWithValidProviderTypeAndNoOptions(): void
    {
        $provider = (new Factory(new Container()))->create([
            'type' => ArrayProvider::class,
        ]);
        $this->assertInstanceOf(ArrayProvider::class, $provider);
    }

    public function testFactoryWillCreateProviderWithValidProviderTypeAndOptions(): void
    {
        $provider = (new Factory(new Container()))->create([
            'type'    => 'Array',
            'options' => [
                'items' => [
                    ['options' => ['label' => 'Home']],
                ],
            ],
        ]);
        $this->assertInstanceOf(ArrayProvider::class, $provider);
        $this->assertTrue($provider->getContainer()->hasPages());
    }

    public function testFactoryWillCreateProviderPluginManagerWhenNoneIsGiven(): void
    {
        $factory = new Factory(new Container());
        $this->assertSame(ProviderPluginManager::class, $factory->getProviderPluginManager()::class);
    }

    public function testFactoryWillUseTheGivenProviderPluginManager(): void
    {
        $manager = new ProviderPluginManager(new Container());

        $factory = new Factory(new Container(), $manager);
        $this->assertSame($manager, $factory->getProviderPluginManager());
    }
}
