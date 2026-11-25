<?php

declare(strict_types=1);

namespace Dot\Navigation\View;

use Dot\Navigation\Exception\InvalidArgumentException;
use Dot\Navigation\NavigationContainer;

interface RendererInterface
{
    /**
     * @param array<non-empty-string, mixed> $params
     * @throws InvalidArgumentException
     */
    public function render(string|NavigationContainer $container, string $template, array $params = []): string;

    /**
     * @param array<non-empty-string, mixed> $params
     * @throws InvalidArgumentException
     */
    public function renderPartial(string|NavigationContainer $container, string $partial, array $params = []): string;

    /**
     * Returns the attributes as HTML attribute text, with a leading space before each attribute.
     *
     * Each value must be a string or a list of strings. A list is joined with spaces.
     * For a key that starts with "on" and for "constraints", an array is encoded as JSON.
     * Any other value type throws a TypeError.
     *
     * @param array<string, mixed> $attributes
     */
    public function htmlAttributes(array $attributes): string;
}
