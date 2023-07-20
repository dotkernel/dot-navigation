<?php

declare(strict_types=1);

namespace Dot\Navigation\Factory;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Provider\Factory;
use Dot\Navigation\Provider\ProviderPluginManager;
use Dot\Navigation\Service\Navigation;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class NavigationServiceFactory
{
    public const MESSAGE_MISSING_PLUGIN_MANAGER     = 'Unable to find RouteHelper in the container';
    public const MESSAGE_MISSING_ROUTE_HELPER       = 'Unable to find ProviderPluginManager in the container';
    public const MESSAGE_MISSING_NAVIGATION_OPTIONS = 'Unable to find NavigationOptions in the container';

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function __invoke(ContainerInterface $container): Navigation
    {
        if (! $container->has(RouteHelper::class)) {
            throw new Exception(self::MESSAGE_MISSING_ROUTE_HELPER);
        }

        if (! $container->has(ProviderPluginManager::class)) {
            throw new Exception(self::MESSAGE_MISSING_PLUGIN_MANAGER);
        }

        if (! $container->has(NavigationOptions::class)) {
            throw new Exception(self::MESSAGE_MISSING_NAVIGATION_OPTIONS);
        }

        /** @var NavigationOptions $options */
        $options = $container->get(NavigationOptions::class);

        $service = new Navigation(
            new Factory($container, $container->get(ProviderPluginManager::class)),
            $container->get(RouteHelper::class),
            $options,
            $container->has(AuthorizationInterface::class)
                ? $container->get(AuthorizationInterface::class)
                : null
        );
        $service->setIsActiveRecursion($options->getActiveRecursion());

        return $service;
    }
}
