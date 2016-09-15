<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/7/2016
 * Time: 7:50 PM
 */

namespace Dot\Navigation\Twig;

use Dot\Navigation\Helper\NavigationMenu;
use Interop\Container\ContainerInterface;

class NavigationExtensionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $navigationMenu = $container->get(NavigationMenu::class);
        return new NavigationExtension($navigationMenu);
    }
}