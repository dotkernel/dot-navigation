<?php

declare(strict_types=1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Service\NavigationInterface;
use Dot\Navigation\View\NavigationRenderer;
use Exception;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class NavigationRendererFactory
{
    public const MESSAGE_MISSING_NAVIGATION_INTERFACE = 'Unable to find NavigationInterface in the container';
    public const MESSAGE_MISSING_NAVIGATION_OPTIONS   = 'Unable to find NavigationOptions in the container';
    public const MESSAGE_MISSING_TEMPLATE_RENDERER    = 'Unable to find TemplateRendererInterface in the container';

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function __invoke(ContainerInterface $container): NavigationRenderer
    {
        if (! $container->has(NavigationInterface::class)) {
            throw new Exception(self::MESSAGE_MISSING_NAVIGATION_INTERFACE);
        }

        if (! $container->has(TemplateRendererInterface::class)) {
            throw new Exception(self::MESSAGE_MISSING_TEMPLATE_RENDERER);
        }

        if (! $container->has(NavigationOptions::class)) {
            throw new Exception(self::MESSAGE_MISSING_NAVIGATION_OPTIONS);
        }

        return new NavigationRenderer(
            $container->get(NavigationInterface::class),
            $container->get(TemplateRendererInterface::class),
            $container->get(NavigationOptions::class)
        );
    }
}
