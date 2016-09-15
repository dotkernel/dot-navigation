<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/7/2016
 * Time: 6:30 PM
 */

namespace Dot\Navigation\Factory;

use Dot\Navigation\Helper\NavigationMenu;
use Dot\Navigation\NavigationService;
use Dot\Navigation\Options\NavigationMenuOptions;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class NavigationMenuFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $options = $container->get(NavigationMenuOptions::class);
        $navigation = $container->get(NavigationService::class);
        $template = $container->get(TemplateRendererInterface::class);

        return new NavigationMenu($navigation, $template, $options);

    }
}