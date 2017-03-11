<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Factory;

use Dot\Authorization\AuthorizationInterface;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Provider\Factory;
use Dot\Navigation\Provider\ProviderPluginManager;
use Dot\Navigation\Service\Navigation;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;

/**
 * Class NavigationServiceFactory
 * @package Dot\Navigation\Factory
 */
class NavigationServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return Navigation
     */
    public function __invoke(ContainerInterface $container, $requestedName): Navigation
    {
        $urlHelper = $container->get(UrlHelper::class);
        $authorization = $container->has(AuthorizationInterface::class)
            ? $container->get(AuthorizationInterface::class)
            : null;

        $providerFactory = new Factory($container, $container->get(ProviderPluginManager::class));

        /** @var NavigationOptions $options */
        $options = $container->get(NavigationOptions::class);

        /** @var Navigation $service */
        $service = new $requestedName($providerFactory, $urlHelper, $options, $authorization);
        $service->setIsActiveRecursion($options->getActiveRecursion());

        return $service;
    }
}
