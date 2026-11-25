<?php

declare(strict_types=1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Options\NavigationOptions;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_key_exists;
use function is_array;

class NavigationOptionsFactory
{
    public const MESSAGE_MISSING_CONFIG         = 'Unable to find config in the container';
    public const MESSAGE_MISSING_PACKAGE_CONFIG = 'Unable to find dot-navigation config';

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RuntimeException
     */
    public function __invoke(ContainerInterface $container): NavigationOptions
    {
        if (! $container->has('config')) {
            throw new RuntimeException(self::MESSAGE_MISSING_CONFIG);
        }
        $config = $container->get('config');
        if (! is_array($config)) {
            throw new RuntimeException(self::MESSAGE_MISSING_CONFIG);
        }

        if (
            ! array_key_exists('dot_navigation', $config)
            || ! is_array($config['dot_navigation'])
            || empty($config['dot_navigation'])
        ) {
            throw new RuntimeException(self::MESSAGE_MISSING_PACKAGE_CONFIG);
        }

        return new NavigationOptions(
            $config['dot_navigation']
        );
    }
}
