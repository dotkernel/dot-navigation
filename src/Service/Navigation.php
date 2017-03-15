<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Service;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Provider\Factory;
use Dot\Navigation\Provider\ProviderInterface;
use Zend\Expressive\Router\RouteResult;

/**
 * Class Navigation
 * @package Dot\Navigation\Service
 */
class Navigation implements NavigationInterface
{
    /**
     * @var NavigationContainer[]
     */
    protected $containers = [];

    /**
     * @var Factory
     */
    protected $providerFactory;

    /** @var  RouteHelper */
    protected $routeHelper;

    /**
     * @var NavigationOptions
     */
    protected $moduleOptions;

    /**
     * @var AuthorizationInterface
     */
    protected $authorization;

    /**
     * @var RouteResult
     */
    protected $routeResult;

    /**
     * @var array
     */
    protected $isActiveCache = [];

    /**
     * @var array
     */
    protected $hrefCache = [];

    /**
     * @var bool
     */
    protected $isActiveRecursion = true;

    /**
     * NavigationService constructor.
     * @param Factory $providerFactory
     * @param AuthorizationInterface $authorization
     * @param RouteHelper $routeHelper
     * @param NavigationOptions $moduleOptions
     */
    public function __construct(
        Factory $providerFactory,
        RouteHelper $routeHelper,
        NavigationOptions $moduleOptions,
        AuthorizationInterface $authorization = null
    ) {
        $this->routeHelper = $routeHelper;
        $this->authorization = $authorization;
        $this->providerFactory = $providerFactory;
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @return RouteResult
     */
    public function getRouteResult(): RouteResult
    {
        return $this->routeResult;
    }

    /**
     * @param RouteResult $routeResult
     */
    public function setRouteResult(RouteResult $routeResult)
    {
        $this->routeResult = $routeResult;
    }

    /**
     * @return bool
     */
    public function getIsActiveRecursion(): bool
    {
        return $this->isActiveRecursion;
    }

    /**
     * @param $isActiveRecursion
     */
    public function setIsActiveRecursion(bool $isActiveRecursion)
    {
        if ($isActiveRecursion != $this->isActiveRecursion) {
            $this->isActiveRecursion = $isActiveRecursion;
            $this->isActiveCache = array();
        }
    }

    /**
     * @param string $name
     * @return NavigationContainer
     */
    public function getContainer(string $name): NavigationContainer
    {
        if (isset($this->containers[$name])) {
            return $this->containers[$name];
        }

        $containersConfig = $this->moduleOptions->getContainers();
        $containerConfig = $containersConfig[$name] ?? [];
        if (empty($containerConfig)) {
            throw new RuntimeException(sprintf('Container `%s` is not defined', $name));
        }

        /** @var ProviderInterface $containerProvider */
        $containerProvider = $this->providerFactory->create($containerConfig);

        $container = $containerProvider->getContainer();
        if (!$container instanceof NavigationContainer) {
            throw new RuntimeException(
                sprintf(
                    "Navigation container for name %s is not an instance of %s",
                    $name,
                    NavigationContainer::class
                )
            );
        }

        $this->containers[$name] = $container;
        return $this->containers[$name];
    }

    /**
     * @param Page $page
     * @return bool
     */
    public function isAllowed(Page $page): bool
    {
        //authorization module is optional, this function will always return true if missing
        if (!$this->authorization) {
            return true;
        }

        $options = $page->getOptions();

        if (isset($options['permission'])) {
            if (isset($options['roles'])) {
                return $this->authorization->isGranted($options['permission'], $options['roles']);
            }
            return $this->authorization->isGranted($options['permission']);
        }

        return true;
    }

    /**
     * @param Page $page
     * @return bool
     */
    public function isActive(Page $page): bool
    {
        $hash = spl_object_hash($page);
        if (isset($this->isActiveCache[$hash])) {
            return $this->isActiveCache[$hash];
        }
        $active = false;
        if ($this->routeResult && $this->routeResult->isSuccess()) {
            $routeName = $this->routeResult->getMatchedRouteName();
            $pageRoute = $page->getOption('route');
            if ($pageRoute) {
                if ($pageRoute['route_name'] === $routeName) {
                    $reqParams = array_merge($this->routeResult->getMatchedParams(), $_GET);
                    $pageParams = array_merge(
                        $pageRoute['route_params'] ?? [],
                        $pageRoute['query_params'] ?? []
                    );

                    $ignoreParams = $pageRoute['ignore_params'] ?? [];
                    $active = $this->areParamsEqual($pageParams, $reqParams, $ignoreParams);
                } elseif ($this->isActiveRecursion) {
                    $iterator = new \RecursiveIteratorIterator($page, \RecursiveIteratorIterator::CHILD_FIRST);
                    /** @var Page $page */
                    foreach ($iterator as $leaf) {
                        if (!$leaf instanceof Page) {
                            continue;
                        }
                        if ($this->isActive($leaf)) {
                            $active = true;
                            break;
                        }
                    }
                }
            }
        }
        $this->isActiveCache[$hash] = $active;
        return $active;
    }

    /**
     * @param array $pageParams
     * @param array $requestParams
     * @param array $ignoreParams
     * @return bool
     */
    protected function areParamsEqual(array $pageParams, array $requestParams, array $ignoreParams): bool
    {
        foreach ($ignoreParams as $unsetKey) {
            if (isset($requestParams[$unsetKey])) {
                unset($requestParams[$unsetKey]);
            }
        }
        $diff = array_diff($requestParams, $pageParams);
        return empty($diff);
    }

    /**
     * @param Page $page
     * @return string
     */
    public function getHref(Page $page): string
    {
        $hash = spl_object_hash($page);
        if (isset($this->hrefCache[$hash])) {
            return (string) $this->hrefCache[$hash];
        }

        $href = null;
        if ($page->getOption('uri')) {
            $href = $page->getOption('uri');
        } elseif ($page->getOption('route')) {
            $pageRoute = $page->getOption('route');
            $href = $this->routeHelper->generateUri($pageRoute);
        }

        if ($href) {
            $this->hrefCache[$hash] = $href->__toString();
        } else {
            throw new RuntimeException('Unable to assemble href for navigation page ' . $page->getName());
        }

        return $href->__toString();
    }
}
