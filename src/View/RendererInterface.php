<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 12/20/2016
 * Time: 1:24 AM
 */

namespace Dot\Navigation\View;

use Dot\Navigation\Page;

/**
 * Interface RendererInterface
 * @package Dot\Navigation\View
 */
interface RendererInterface
{
    /**
     * @param null $container
     * @return mixed
     */
    public function renderMenu($container = null);

    /**
     * @param null $container
     * @param null $partial
     * @param array $extra
     * @return mixed
     */
    public function renderPartial($container = null, $partial = null, array $extra = []);

    /**
     * @param null $container
     * @return mixed
     */
    public function render($container = null);

    /**
     * @param Page $page
     * @return mixed
     */
    public function htmlify(Page $page);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function htmlAttributes(array $attributes);
}
