<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 12/20/2016
 * Time: 1:24 AM
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
