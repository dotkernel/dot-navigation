<?php

declare(strict_types=1);

namespace DotTest\Navigation\Provider;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Provider\ArrayProvider;
use Dot\Navigation\Provider\InvokableProviderFactory;
use Dot\Navigation\Provider\ProviderInterface;
use Dot\Navigation\Provider\ProviderPluginManager;
use DotTest\Navigation\TestData\Container;
use DotTest\Navigation\TestData\CustomProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use stdClass;

class ProviderPluginManagerTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function builtInNameProvider(): array
    {
        return [
            'class name'    => [ArrayProvider::class],
            'arrayprovider' => ['arrayprovider'],
            'arrayProvider' => ['arrayProvider'],
            'ArrayProvider' => ['ArrayProvider'],
            'array'         => ['array'],
            'Array'         => ['Array'],
        ];
    }

    #[DataProvider('builtInNameProvider')]
    public function testWillBuildArrayProviderFromBuiltInName(string $name): void
    {
        $manager = new ProviderPluginManager(new Container());

        $this->assertTrue($manager->has($name));
        $this->assertInstanceOf(ArrayProvider::class, $manager->build($name, ['items' => []]));
    }

    public function testWillBuildNewInstanceForEachCall(): void
    {
        $manager = new ProviderPluginManager(new Container());

        $this->assertNotSame($manager->build('array'), $manager->build('array'));
    }

    public function testWillBuildProviderWithFactoryClassAndAlias(): void
    {
        $manager = new ProviderPluginManager(new Container(), [
            'factories' => [
                CustomProvider::class => InvokableProviderFactory::class,
            ],
            'aliases'   => [
                'custom' => CustomProvider::class,
            ],
        ]);

        $this->assertInstanceOf(CustomProvider::class, $manager->build('custom'));
    }

    public function testWillPassContainerNameAndOptionsToCallableFactory(): void
    {
        $container = new Container();
        $received  = [];
        $manager   = new ProviderPluginManager($container, [
            'factories' => [
                'closure' => static function (
                    ContainerInterface $container,
                    string $requestedName,
                    ?array $options = null
                ) use (&$received): ProviderInterface {
                    $received = [$container, $requestedName, $options];
                    return new CustomProvider();
                },
            ],
        ]);

        $manager->build('closure', ['key' => 'value']);
        $this->assertSame([$container, 'closure', ['key' => 'value']], $received);
    }

    public function testWillNotBuildUnknownProvider(): void
    {
        $manager = new ProviderPluginManager(new Container());

        $this->assertFalse($manager->has(CustomProvider::class));
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Navigation provider "' . CustomProvider::class . '" is not registered');
        $manager->build(CustomProvider::class);
    }

    public function testWillNotBuildWhenFactoryReturnsWrongObject(): void
    {
        $manager = new ProviderPluginManager(new Container(), [
            'factories' => [
                'wrong' => static fn (): stdClass => new stdClass(),
            ],
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'can only create instances of ' . ProviderInterface::class . '; stdClass is invalid'
        );
        $manager->build('wrong');
    }

    public function testInvokableProviderFactoryWillNotCreateClassThatIsNotProvider(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('"' . NavigationContainer::class . '" is not a navigation provider class');
        (new InvokableProviderFactory())(new Container(), NavigationContainer::class);
    }
}
