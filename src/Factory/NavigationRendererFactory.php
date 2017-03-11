<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
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
