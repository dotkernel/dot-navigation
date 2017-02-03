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

/**
 * Interface RendererInterface
 * @package Dot\Navigation\View
 */
interface RendererInterface
{
    /**
     * @param string $container
     * @return string
     */
    public function render(string $container): string;

    /**
     * @param $partial
     * @param array $params
     * @param string $container
     * @return string
     */
    public function renderPartial(string $container, string $partial, array $params = []): string;

    /**
     * @param array $attributes
     * @return string
     */
    public function htmlAttributes(array $attributes): string;
}
