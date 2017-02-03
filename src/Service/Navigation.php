<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation\Service;

use Dot\Authorization\AuthorizationInterface;
use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Provider\ProviderInterface;
use Dot\Navigation\Provider\ProviderPluginManager;
use Zend\Expressive\Helper\UrlHelper;
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
     * @var ProviderPluginManager
     */
    protected $providerPluginManager;

    /**
     * @var UrlHelper
     */
    protected $urlHelper;

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
     * @param ProviderPluginManager $providerPluginManager
     * @param AuthorizationInterface $authorization
     * @param UrlHelper $urlHelper
     * @param NavigationOptions $moduleOptions
     */
    public function __construct(
        ProviderPluginManager $providerPluginManager,
        UrlHelper $urlHelper,
        NavigationOptions $moduleOptions,
        AuthorizationInterface $authorization = null
    ) {
        $this->urlHelper = $urlHelper;
        $this->authorization = $authorization;
        $this->providerPluginManager = $providerPluginManager;
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @return RouteResult
     */
    public function getRouteResult()
    {
        return $this->routeResult;
    }

    /**
     * @param RouteResult|null $routeResult
     * @return $this
     */
    public function setRouteResult(RouteResult $routeResult = null)
    {
        $this->routeResult = $routeResult;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActiveRecursion()
    {
        return $this->isActiveRecursion;
    }

    /**
     * @param $isActiveRecursion
     * @return $this
     */
    public function setIsActiveRecursion($isActiveRecursion)
    {
        if ($isActiveRecursion != $this->isActiveRecursion) {
            $this->isActiveRecursion = $isActiveRecursion;
            $this->isActiveCache = array();
        }
        return $this;
    }

    /**
     * @param $name
     * @return NavigationContainer
     */
    public function getContainer($name)
    {
        if (isset($this->containers[$name])) {
            return $this->containers[$name];
        }

        $map = $this->moduleOptions->getProvidersMap();
        $containersConfig = $this->moduleOptions->getContainers();

        if (!isset($map[$name])) {
            throw new RuntimeException("No navigation container with name $name is registered");
        }

        $containerConfig = isset($containersConfig[$name]) ? $containersConfig[$name] : [];
        /** @var ProviderInterface $containerProvider */
        $containerProvider = $this->providerPluginManager->get($map[$name], $containerConfig);

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

    public function isAllowed(Page $page)
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

    public function isActive(Page $page)
    {
        $hash = spl_object_hash($page);
        if (isset($this->isActiveCache[$hash])) {
            return $this->isActiveCache[$hash];
        }
        $active = false;
        if ($this->routeResult && $this->routeResult->isSuccess()) {
            $routeName = $this->routeResult->getMatchedRouteName();
            if ($page->getOption('route') == $routeName) {
                $reqParams = array_merge($this->routeResult->getMatchedParams(), $_GET);
                $pageParams = array_merge(
                    $page->getOption('params') ? $page->getOption('params') : [],
                    $page->getOption('query_params') ? $page->getOption('query_params') : []
                );
                $ignoreParams = $page->getOption('ignore_params') ? $page->getOption('ignore_params') : [];
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
        $this->isActiveCache[$hash] = $active;
        return $active;
    }

    /**
     * @param array $pageParams
     * @param array $requestParams
     * @param array $ignoreParams
     * @return bool
     */
    protected function areParamsEqual(array $pageParams, array $requestParams, array $ignoreParams)
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
    public function getHref(Page $page)
    {
        $hash = spl_object_hash($page);
        if (isset($this->hrefCache[$hash])) {
            return $this->hrefCache[$hash];
        }
        $href = null;
        if ($page->getOption('uri')) {
            $href = $page->getOption('uri');
        } elseif ($page->getOption('route')) {
            $params = $page->getOption('params') ? $page->getOption('params') : [];
            $href = $this->urlHelper->generate($page->getOption('route'), $params);
        }
        if ($href) {
            if ($page->getOption('query_params')) {
                $href .= '?' . http_build_query($page->getOption('query_params'));
            }
            if ($page->getOption('fragment')) {
                $href .= '#' . trim($page->getOption('fragment'), '#');
            }
            $this->hrefCache[$hash] = $href;
        } else {
            throw new RuntimeException('Unable to assemble href for navigation page ' . $page->getName());
        }
        return $href;
    }
}
