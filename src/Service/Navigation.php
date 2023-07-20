<?php

declare(strict_types=1);

namespace Dot\Navigation\Service;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Provider\FactoryInterface;
use Mezzio\Router\RouteResult;
use RecursiveIteratorIterator;

use function array_diff;
use function array_merge;
use function spl_object_hash;
use function sprintf;

class Navigation implements NavigationInterface
{
    protected FactoryInterface $providerFactory;
    protected RouteHelper $routeHelper;
    protected NavigationOptions $moduleOptions;
    protected ?AuthorizationInterface $authorization;
    protected ?RouteResult $routeResult = null;
    protected array $containers         = [];
    protected array $isActiveCache      = [];
    protected array $hrefCache          = [];
    protected bool $isActiveRecursion   = true;

    public function __construct(
        FactoryInterface $providerFactory,
        RouteHelper $routeHelper,
        NavigationOptions $moduleOptions,
        ?AuthorizationInterface $authorization = null
    ) {
        $this->routeHelper     = $routeHelper;
        $this->authorization   = $authorization;
        $this->providerFactory = $providerFactory;
        $this->moduleOptions   = $moduleOptions;
    }

    public function getRouteResult(): ?RouteResult
    {
        return $this->routeResult;
    }

    public function setRouteResult(RouteResult $routeResult): void
    {
        $this->routeResult = $routeResult;
    }

    public function getIsActiveRecursion(): bool
    {
        return $this->isActiveRecursion;
    }

    public function setIsActiveRecursion(bool $isActiveRecursion): void
    {
        if ($isActiveRecursion !== $this->isActiveRecursion) {
            $this->isActiveRecursion = $isActiveRecursion;
            $this->isActiveCache     = [];
        }
    }

    public function getIsActiveCache(): array
    {
        return $this->isActiveCache;
    }

    public function getHrefCache(): array
    {
        return $this->hrefCache;
    }

    public function getContainer(string $name): NavigationContainer
    {
        if (isset($this->containers[$name])) {
            return $this->containers[$name];
        }

        $containersConfig = $this->moduleOptions->getContainers();
        $containerConfig  = $containersConfig[$name] ?? [];
        if (empty($containerConfig)) {
            throw new RuntimeException(sprintf('Container `%s` is not defined', $name));
        }

        $containerProvider       = $this->providerFactory->create($containerConfig);
        $this->containers[$name] = $containerProvider->getContainer();

        return $this->containers[$name];
    }

    public function isAllowed(Page $page): bool
    {
        //authorization module is optional, this function will always return true if missing
        if (! $this->authorization instanceof AuthorizationInterface) {
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

    public function isActive(Page $page): bool
    {
        $hash = spl_object_hash($page);
        if (isset($this->isActiveCache[$hash])) {
            return $this->isActiveCache[$hash];
        }

        $active = false;
        if ($this->routeResult instanceof RouteResult && $this->routeResult->isSuccess()) {
            $routeName = $this->routeResult->getMatchedRouteName();
            $pageRoute = $page->getOption('route');
            if ($pageRoute) {
                if ($pageRoute['route_name'] === $routeName) {
                    $reqParams  = array_merge($this->routeResult->getMatchedParams(), $_GET);
                    $pageParams = array_merge($pageRoute['route_params'] ?? [], $pageRoute['query_params'] ?? []);

                    $active = $this->areParamsEqual($pageParams, $reqParams, $pageRoute['ignore_params'] ?? []);
                } elseif ($this->isActiveRecursion) {
                    $iterator = new RecursiveIteratorIterator($page, RecursiveIteratorIterator::CHILD_FIRST);
                    foreach ($iterator as $leaf) {
                        if (! $leaf instanceof Page) {
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
            $href      = $this->routeHelper->generateUri($pageRoute);
        }

        if ($href) {
            $this->hrefCache[$hash] = (string) $href;
        } else {
            throw new RuntimeException('Unable to assemble href for navigation page ' . $page->getName());
        }

        return (string) $href;
    }
}
