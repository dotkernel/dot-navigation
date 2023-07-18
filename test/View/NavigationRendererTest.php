<?php

declare(strict_types=1);

namespace DotTest\Navigation\View;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\NavigationRenderer;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class NavigationRendererTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testWillCreateNavigationRenderer(): void
    {
        $navigation = $this->createMock(NavigationInterface::class);
        $template   = $this->createMock(TemplateRendererInterface::class);
        $options    = $this->createMock(NavigationOptions::class);

        $renderer = new NavigationRenderer($navigation, $template, $options);
        $this->assertInstanceOf(NavigationRenderer::class, $renderer);
    }

    /**
     * @throws Exception
     */
    public function testWillRenderPartial(): void
    {
        $navigation = $this->createMock(NavigationInterface::class);
        $template   = $this->createMock(TemplateRendererInterface::class);
        $options    = $this->createMock(NavigationOptions::class);

        $renderer = new NavigationRenderer($navigation, $template, $options);
        $html     = $renderer->renderPartial(new NavigationContainer(), 'partial');
        $this->assertIsString($html);
    }

    /**
     * @throws Exception
     */
    public function testWillRenderTemplate(): void
    {
        $navigation = $this->createMock(NavigationInterface::class);
        $template   = $this->createMock(TemplateRendererInterface::class);
        $options    = $this->createMock(NavigationOptions::class);

        $renderer = new NavigationRenderer($navigation, $template, $options);
        $html     = $renderer->render(new NavigationContainer(), 'template');
        $this->assertIsString($html);
    }
}
