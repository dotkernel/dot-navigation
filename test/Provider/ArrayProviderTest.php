<?php

declare(strict_types=1);

namespace DotTest\Navigation\Provider;

use Dot\Navigation\Exception\InvalidArgumentException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Provider\ArrayProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ArrayProviderTest extends TestCase
{
    public function testAccessors(): void
    {
        $provider = new ArrayProvider();
        $this->assertFalse($provider->hasItems());
        $provider->setItems([['options' => ['label' => 'test']]]);
        $this->assertTrue($provider->hasItems());
        $this->assertSame([['options' => ['label' => 'test']]], $provider->getItems());
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
        $this->assertSame($container, $provider->getContainer());
    }

    /**
     * @return array<string, array{string}>
     */
    public static function pageKeyProvider(): array
    {
        return [
            'attributes' => ['attributes'],
            'options'    => ['options'],
            'pages'      => ['pages'],
        ];
    }

    #[DataProvider('pageKeyProvider')]
    public function testWillNotCreatePageWhenKeyIsNotAnArray(string $key): void
    {
        $provider = new ArrayProvider([
            'items' => [
                [$key => 'invalid'],
            ],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The navigation page key "' . $key . '" must be an array');
        $provider->getContainer();
    }

    public function testWillNotCreatePageWhenSubPageIsNotAnArray(): void
    {
        $provider = new ArrayProvider([
            'items' => [
                ['pages' => ['invalid']],
            ],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $provider->getContainer();
    }
}
