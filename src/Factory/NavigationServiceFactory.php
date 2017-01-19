<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation\Factory;

use Dot\Authorization\AuthorizationInterface;
use Dot\Navigation\Options\NavigationOptions;
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
     * @return Navigation
     */
    public function __invoke(ContainerInterface $container)
    {
        $urlHelper = $container->get(UrlHelper::class);
        $authorization = $container->has(AuthorizationInterface::class)
            ? $container->get(AuthorizationInterface::class)
            : null;

        $providerPluginManager = $container->get(ProviderPluginManager::class);

        /** @var NavigationOptions $options */
        $options = $container->get(NavigationOptions::class);

        $service = new Navigation($providerPluginManager, $urlHelper, $options, $authorization);
        $service->setIsActiveRecursion($options->getActiveRecursion());

        return $service;
    }
}
