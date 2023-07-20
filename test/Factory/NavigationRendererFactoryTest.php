<?php

declare(strict_types=1);

namespace DotTest\Navigation\Factory;

use Dot\Navigation\Factory\NavigationRendererFactory;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\NavigationRenderer;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class NavigationRendererFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateNavigationRendererWithoutNavigationInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('has')
            ->with(NavigationInterface::class)
            ->willReturn(false);

        $this->expectExceptionMessage(NavigationRendererFactory::MESSAGE_MISSING_NAVIGATION_INTERFACE);
        (new NavigationRendererFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateNavigationRendererWithoutTemplateRendererInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->method('has')->willReturnMap([
            [NavigationInterface::class, true],
            [TemplateRendererInterface::class, false],
        ]);

        $this->expectExceptionMessage(NavigationRendererFactory::MESSAGE_MISSING_TEMPLATE_RENDERER);
        (new NavigationRendererFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateNavigationRendererWithoutNavigationOptions(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->method('has')->willReturnMap([
            [NavigationInterface::class, true],
            [TemplateRendererInterface::class, true],
            [NavigationOptions::class, false],
        ]);

        $this->expectExceptionMessage(NavigationRendererFactory::MESSAGE_MISSING_NAVIGATION_OPTIONS);
        (new NavigationRendererFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateNavigationRenderer(): void
    {
        $container  = $this->createMock(ContainerInterface::class);
        $navigation = $this->createMock(NavigationInterface::class);
        $renderer   = $this->createMock(TemplateRendererInterface::class);
        $options    = $this->createMock(NavigationOptions::class);

        $container->method('has')->willReturnMap([
            [NavigationInterface::class, true],
            [TemplateRendererInterface::class, true],
            [NavigationOptions::class, true],
        ]);

        $container->method('get')->willReturnMap([
            [NavigationInterface::class, $navigation],
            [TemplateRendererInterface::class, $renderer],
            [NavigationOptions::class, $options],
        ]);

        $renderer = (new NavigationRendererFactory())($container);
        $this->assertInstanceOf(NavigationRenderer::class, $renderer);
    }
}
