<?php

declare(strict_types=1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\Options\NavigationOptions;
use Exception;
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
     * @throws Exception
     */
    public function __invoke(ContainerInterface $container): NavigationOptions
    {
        if (! $container->has('config')) {
            throw new Exception(self::MESSAGE_MISSING_CONFIG);
        }
        $config = $container->get('config');

        if (
            ! array_key_exists('dot_navigation', $config)
            || ! is_array($config['dot_navigation'])
            || empty($config['dot_navigation'])
        ) {
            throw new Exception(self::MESSAGE_MISSING_PACKAGE_CONFIG);
        }

        return new NavigationOptions(
            $config['dot_navigation']
        );
    }
}
