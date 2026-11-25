<?php

declare(strict_types=1);

namespace DotTest\Navigation\Factory;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Factory\NavigationOptionsFactory;
use DotTest\Navigation\TestData\Container;
use PHPUnit\Framework\TestCase;

class NavigationOptionsFactoryTest extends TestCase
{
    public function testWillNotCreateNavigationOptionsWithoutConfig(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationOptionsFactory::MESSAGE_MISSING_CONFIG);
        (new NavigationOptionsFactory())(new Container());
    }

    public function testWillNotCreateNavigationOptionsWhenConfigIsNotAnArray(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationOptionsFactory::MESSAGE_MISSING_CONFIG);
        (new NavigationOptionsFactory())(new Container(['config' => 'invalid']));
    }

    public function testWillNotCreateNavigationOptionsWithoutPackageConfig(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationOptionsFactory::MESSAGE_MISSING_PACKAGE_CONFIG);
        (new NavigationOptionsFactory())(new Container(['config' => []]));
    }

    public function testWillCreateNavigationOptions(): void
    {
        $container = new Container([
            'config' => [
                'dot_navigation' => [
                    'active_recursion' => false,
                ],
            ],
        ]);

        $options = (new NavigationOptionsFactory())($container);
                $this->assertFalse($options->getActiveRecursion());
    }
}
