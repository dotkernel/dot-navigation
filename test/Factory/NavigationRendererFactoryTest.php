<?php

declare(strict_types=1);

namespace DotTest\Navigation\Factory;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Factory\NavigationRendererFactory;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\NavigationRenderer;
use DotTest\Navigation\TestData\Container;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;

class NavigationRendererFactoryTest extends TestCase
{
    public function testWillNotCreateNavigationRendererWithoutNavigationInterface(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationRendererFactory::MESSAGE_MISSING_NAVIGATION_INTERFACE);
        (new NavigationRendererFactory())(new Container());
    }

    public function testWillNotCreateNavigationRendererWithoutTemplateRendererInterface(): void
    {
        $container = new Container([
            NavigationInterface::class => $this->createStub(NavigationInterface::class),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationRendererFactory::MESSAGE_MISSING_TEMPLATE_RENDERER);
        (new NavigationRendererFactory())($container);
    }

    public function testWillNotCreateNavigationRendererWithoutNavigationOptions(): void
    {
        $container = new Container([
            NavigationInterface::class       => $this->createStub(NavigationInterface::class),
            TemplateRendererInterface::class => $this->createStub(TemplateRendererInterface::class),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationRendererFactory::MESSAGE_MISSING_NAVIGATION_OPTIONS);
        (new NavigationRendererFactory())($container);
    }

    public function testWillCreateNavigationRenderer(): void
    {
        $container = new Container([
            NavigationInterface::class       => $this->createStub(NavigationInterface::class),
            TemplateRendererInterface::class => $this->createStub(TemplateRendererInterface::class),
            NavigationOptions::class         => new NavigationOptions(),
        ]);

        $renderer = (new NavigationRendererFactory())($container);
        $this->assertSame(NavigationRenderer::class, $renderer::class);
    }
}
