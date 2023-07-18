<?php

declare(strict_types=1);

namespace Dot\Navigation\View;

use Dot\Navigation\NavigationContainer;

interface RendererInterface
{
    public function render(string|NavigationContainer $container, string $template, array $params = []): string;

    public function renderPartial(string|NavigationContainer $container, string $partial, array $params = []): string;

    public function htmlAttributes(array $attributes): string;
}
