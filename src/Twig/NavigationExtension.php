<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 6/7/2016
 * Time: 3:34 PM
 */

namespace Dot\Navigation\Twig;

use Dot\Navigation\Helper\NavigationMenu;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;

class NavigationExtension extends \Twig_Extension
{
    /**
     * @var NavigationMenu
     */
    protected $navigationMenu;

    /**
     * NavigationExtension constructor.
     * @param NavigationMenu $navigationMenu
     */
    public function __construct(NavigationMenu $navigationMenu)
    {
        $this->navigationMenu = $navigationMenu;
    }

    public function getName()
    {
        return 'dk-navigation';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('navigationMenu', [$this, 'renderMenu'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('navigationPartial', [$this, 'renderPartial'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('navigationPageAttributes', [$this, 'htmlAttributes'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param Page $page
     * @return mixed
     */
    public function htmlAttributes(Page $page)
    {
        return $this->navigationMenu->htmlAttributes($page->getAttributes());
    }

    /**
     * @param null|string|NavigationContainer $container
     * @return string
     */
    public function renderMenu($container = null)
    {
        return $this->navigationMenu->renderMenu($container);
    }

    /**
     * @param null|string|NavigationContainer $container
     * @param string $partial
     * @param array $extra
     * @return string
     */
    public function renderPartial($container = null, $partial = null, array $extra = [])
    {
        return $this->navigationMenu->renderPartial($container, $partial, $extra);
    }

}