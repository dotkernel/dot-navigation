<?php

declare(strict_types=1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\Provider\ProviderPluginManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_key_exists;
use function is_array;

class ProviderPluginManagerFactory
{
    public const MESSAGE_MISSING_CONFIG                  = 'Unable to find config in the container';
    public const MESSAGE_MISSING_PACKAGE_CONFIG          = 'Unable to find dot-navigation config';
    public const MESSAGE_MISSING_CONFIG_PROVIDER_MANAGER = 'Missing/invalid dot-navigation config: provider_manager';

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function __invoke(ContainerInterface $container): ProviderPluginManager
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
        $config = $config['dot_navigation'];

        if (
            ! array_key_exists('provider_manager', $config)
            || ! is_array($config['provider_manager'])
        ) {
            throw new Exception(self::MESSAGE_MISSING_CONFIG_PROVIDER_MANAGER);
        }
        $config = $config['provider_manager'];

        return new ProviderPluginManager($container, $config);
    }
}
