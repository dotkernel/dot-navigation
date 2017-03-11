<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\View;

use Dot\Navigation\NavigationContainer;

/**
 * Interface RendererInterface
 * @package Dot\Navigation\View
 */
interface RendererInterface
{
    /**
     * @param string|NavigationContainer $container
     * @return string
     */
    public function render($container): string;

    /**
     * @param $partial
     * @param array $params
     * @param string|NavigationContainer $container
     * @return string
     */
    public function renderPartial($container, string $partial, array $params = []): string;

    /**
     * @param array $attributes
     * @return string
     */
    public function htmlAttributes(array $attributes): string;
}
