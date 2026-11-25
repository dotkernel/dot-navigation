<?php

declare(strict_types=1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\NavigationMiddleware;
use Dot\Navigation\Service\NavigationInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class NavigationMiddlewareFactory
{
    public const MESSAGE_MISSING_NAVIGATION = 'Unable to find NavigationInterface in the container';

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RuntimeException
     */
    public function __invoke(ContainerInterface $container): NavigationMiddleware
    {
        if (! $container->has(NavigationInterface::class)) {
            throw new RuntimeException(self::MESSAGE_MISSING_NAVIGATION);
        }

        return new NavigationMiddleware(
            $container->get(NavigationInterface::class)
        );
    }
}
