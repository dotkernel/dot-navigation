<?php

declare(strict_types=1);

namespace DotTest\Navigation\Options;

use Dot\Navigation\Options\NavigationOptions;
use PHPUnit\Framework\TestCase;

class NavigationOptionsTest extends TestCase
{
    public function testWillCreateNavigationOptions(): void
    {
        $options = new NavigationOptions();
        $this->assertInstanceOf(NavigationOptions::class, $options);
    }

    public function testAccessors(): void
    {
        $options = new NavigationOptions();
        $this->assertIsArray($options->getContainers());
        $this->assertEmpty($options->getContainers());
        $options->setContainers(['test']);
        $this->assertIsArray($options->getContainers());
        $this->assertCount(1, $options->getContainers());
        $this->assertTrue($options->getActiveRecursion());
        $options->setActiveRecursion(false);
        $this->assertFalse($options->getActiveRecursion());
    }
}
