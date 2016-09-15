<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 6/7/2016
 * Time: 2:06 PM
 */

namespace Dot\Navigation\Factory;

use Dot\Navigation\NavigationService;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Provider\ProviderPluginManager;
use Interop\Container\ContainerInterface;
use N3vrax\DkAuthorization\AuthorizationInterface;
use Zend\Expressive\Helper\UrlHelper;

class NavigationServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $urlHelper = $container->get(UrlHelper::class);
        $authorization = $container->get(AuthorizationInterface::class);
        $providerPluginManager = $container->get(ProviderPluginManager::class);

        /** @var NavigationOptions $options */
        $options = $container->get(NavigationOptions::class);

        $service = new NavigationService($providerPluginManager, $authorization, $urlHelper, $options);
        $service->setIsActiveRecursion($options->getActiveRecursion());

        return $service;
    }
}