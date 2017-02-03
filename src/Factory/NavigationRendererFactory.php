<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\NavigationRenderer;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class NavigationRendererFactory
 * @package Dot\Navigation\Factory
 */
class NavigationRendererFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return NavigationRenderer
     */
    public function __invoke(ContainerInterface $container, $requestedName): NavigationRenderer
    {
        $options = $container->get(NavigationOptions::class);
        $navigation = $container->get(NavigationInterface::class);
        $template = $container->get(TemplateRendererInterface::class);

        return new $requestedName($navigation, $template, $options);
    }
}
