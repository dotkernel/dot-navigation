<?php

declare(strict_types=1);

namespace Dot\Navigation\View;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Service\NavigationInterface;
use Mezzio\Template\TemplateRendererInterface;

use function array_merge;

class NavigationRenderer extends AbstractNavigationRenderer
{
    protected NavigationOptions $options;

    public function __construct(
        NavigationInterface $navigation,
        TemplateRendererInterface $template,
        NavigationOptions $options
    ) {
        $this->options = $options;
        parent::__construct($navigation, $template);
    }

    public function renderPartial(string|NavigationContainer $container, string $partial, array $params = []): string
    {
        $container = $this->getContainer($container);

        return $this->template->render(
            $partial,
            array_merge(
                ['container' => $container, 'navigation' => $this->navigation],
                $params
            )
        );
    }

    public function render(string|NavigationContainer $container, string $template, array $params = []): string
    {
        $container = $this->getContainer($container);

        return $this->template->render(
            $template,
            array_merge(
                ['container' => $container, 'navigation' => $this->navigation],
                $params
            )
        );
    }
}
