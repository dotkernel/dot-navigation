<?php

declare(strict_types=1);

namespace DotTest\Navigation\View;

use Dot\Navigation\Exception\InvalidArgumentException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\NavigationRenderer;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NavigationRendererTest extends TestCase
{
    public function testWillRenderPartialWithContainerNavigationAndParams(): void
    {
        $navigation = $this->createStub(NavigationInterface::class);
        $container  = new NavigationContainer();

        $template = $this->createMock(TemplateRendererInterface::class);
        $template->expects($this->once())
            ->method('render')
            ->with('partial::menu', ['container' => $container, 'navigation' => $navigation, 'extra' => 'value'])
            ->willReturn('<ul></ul>');

        $renderer = new NavigationRenderer($navigation, $template, new NavigationOptions());
        $this->assertSame('<ul></ul>', $renderer->renderPartial($container, 'partial::menu', ['extra' => 'value']));
    }

    public function testWillRenderTemplateWithContainerFromNavigation(): void
    {
        $container  = new NavigationContainer();
        $navigation = $this->createStub(NavigationInterface::class);
        $navigation->method('getContainer')->willReturn($container);

        $template = $this->createMock(TemplateRendererInterface::class);
        $template->expects($this->once())
            ->method('render')
            ->with('app::menu', ['container' => $container, 'navigation' => $navigation])
            ->willReturn('<nav></nav>');

        $renderer = new NavigationRenderer($navigation, $template, new NavigationOptions());
        $this->assertSame('<nav></nav>', $renderer->render('default', 'app::menu'));
    }

    public function testWillNotRenderEmptyTemplateName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The template name must not be empty');
        $this->getRenderer()->render(new NavigationContainer(), '');
    }

    public function testWillNotRenderEmptyPartialName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The partial name must not be empty');
        $this->getRenderer()->renderPartial(new NavigationContainer(), '');
    }

    public function testPartialAccessors(): void
    {
        $renderer = $this->getRenderer();
        $this->assertNull($renderer->getPartial());
        $renderer->setPartial('partial::menu');
        $this->assertSame('partial::menu', $renderer->getPartial());
    }

    /**
     * @return array<string, array{array<string, mixed>, string}>
     */
    public static function htmlAttributesProvider(): array
    {
        return [
            'no attributes'   => [[], ''],
            'string'          => [['class' => 'nav-link', 'target' => '_blank'], ' class="nav-link" target="_blank"'],
            'escaped string'  => [['href' => '#', 'title' => 'a"b'], ' href="&#x23;" title="a&quot;b"'],
            'list of strings' => [['class' => ['nav-link', 'active']], ' class="nav-link&#x20;active"'],
            'event as JSON'   => [['onclick' => ['a' => 1]], ' onclick="&#x7B;&quot;a&quot;&#x3A;1&#x7D;"'],
            'boolean as name' => [['hidden' => 'hidden'], ' hidden="hidden"'],
        ];
    }

    /**
     * @param array<string, mixed> $attributes
     */
    #[DataProvider('htmlAttributesProvider')]
    public function testHtmlAttributes(array $attributes, string $expected): void
    {
        $this->assertSame($expected, $this->getRenderer()->htmlAttributes($attributes));
    }

    private function getRenderer(): NavigationRenderer
    {
        return new NavigationRenderer(
            $this->createStub(NavigationInterface::class),
            $this->createStub(TemplateRendererInterface::class),
            new NavigationOptions()
        );
    }
}
