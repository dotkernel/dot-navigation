<?php

declare(strict_types=1);

namespace DotTest\Navigation\Provider;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Provider\ArrayProvider;
use PHPUnit\Framework\TestCase;

class ArrayProviderTest extends TestCase
{
    public function testWillCreateArrayProvider(): void
    {
        $provider = new ArrayProvider();
        $this->assertSame(ArrayProvider::class, $provider::class);
    }

    public function testAccessors(): void
    {
        $provider = new ArrayProvider();
        $this->assertFalse($provider->hasItems());
        $provider->setItems(['test']);
        $this->assertTrue($provider->hasItems());
        $this->assertSame(['test'], $provider->getItems());
    }

    public function testGetContainer(): void
    {
        $pageSpecs = [
            [
                'pages' => [
                    ['attributes' => ['attr' => 'attribute #0'], 'options' => ['opt' => 'option #0']],
                    ['attributes' => ['attr' => 'attribute #1'], 'options' => ['opt' => 'option #1']],
                ],
            ],
        ];

        $provider  = new ArrayProvider([
            'items' => $pageSpecs,
        ]);
        $container = $provider->getContainer();
        $this->assertSame(NavigationContainer::class, $container::class);
        $this->assertCount(2, $container->getChildren());
    }
}
