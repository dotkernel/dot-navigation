<?php

declare(strict_types=1);

namespace Dot\Navigation\Service;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Exception\InvalidArgumentException;
use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Provider\FactoryInterface;
use Mezzio\Router\RouteResult;

use function array_key_exists;
use function array_merge;
use function is_array;
use function is_scalar;
use function is_string;
use function spl_object_hash;
use function sprintf;

class Navigation implements NavigationInterface
{
    protected FactoryInterface $providerFactory;
    protected RouteHelper $routeHelper;
    protected NavigationOptions $moduleOptions;
    protected ?AuthorizationInterface $authorization;
    protected ?RouteResult $routeResult = null;
    /** @var array<string, NavigationContainer> */
    protected array $containers = [];
    /** @var array<string, bool> */
    protected array $isActiveCache = [];
    /** @var array<string, string> */
    protected array $hrefCache = [];
    /** @var array<string, mixed> */
    protected array $queryParams      = [];
    protected bool $isActiveRecursion = true;

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
        $this->routeResult   = $routeResult;
        $this->isActiveCache = [];
        $this->hrefCache     = [];
    }

    /**
     * @param array<string, mixed> $queryParams
     */
    public function setQueryParams(array $queryParams): void
    {
        $this->queryParams   = $queryParams;
        $this->isActiveCache = [];
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

    /**
     * @return array<string, bool>
     */
    public function getIsActiveCache(): array
    {
        return $this->isActiveCache;
    }

    /**
     * @return array<string, string>
     */
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

    /**
     * @throws InvalidArgumentException
     */
    public function isAllowed(Page $page): bool
    {
        //authorization module is optional, this function will always return true if missing
        if (! $this->authorization instanceof AuthorizationInterface) {
            return true;
        }

        $options = $page->getOptions();

        if (isset($options['permission'])) {
            if (isset($options['roles'])) {
                $roles = $options['roles'];
                if (is_string($roles)) {
                    $roles = [$roles];
                }
                if (! is_array($roles)) {
                    throw new InvalidArgumentException(
                        'The navigation page option "roles" must be a string or an array'
                    );
                }

                return $this->authorization->isGranted($options['permission'], $roles);
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
            $pageRoute = $page->getOption('route');
            if (
                is_array($pageRoute)
                && ($pageRoute['route_name'] ?? null) === $this->routeResult->getMatchedRouteName()
            ) {
                $active = $this->areParamsEqual(
                    array_merge($pageRoute['route_params'] ?? [], $pageRoute['query_params'] ?? []),
                    array_merge($this->routeResult->getMatchedParams(), $this->queryParams),
                    $pageRoute['ignore_params'] ?? []
                );
            } elseif ($this->isActiveRecursion) {
                foreach ($page as $child) {
                    if ($this->isActive($child)) {
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
     * Tells if each request parameter, except the ignored ones, is also a page parameter with the same value.
     *
     * @param array<string, mixed> $pageParams
     * @param array<string, mixed> $requestParams
     * @param list<string> $ignoreParams
     */
    protected function areParamsEqual(array $pageParams, array $requestParams, array $ignoreParams): bool
    {
        foreach ($ignoreParams as $key) {
            unset($requestParams[$key]);
        }

        foreach ($requestParams as $key => $value) {
            if (! array_key_exists($key, $pageParams) || ! $this->isSameParam($pageParams[$key], $value)) {
                return false;
            }
        }

        return true;
    }

    private function isSameParam(mixed $pageValue, mixed $requestValue): bool
    {
        if (is_scalar($pageValue) && is_scalar($requestValue)) {
            return (string) $pageValue === (string) $requestValue;
        }

        return $pageValue === $requestValue;
    }

    /**
     * @throws RuntimeException
     */
    public function getHref(Page $page): string
    {
        $hash = spl_object_hash($page);
        if (isset($this->hrefCache[$hash])) {
            return $this->hrefCache[$hash];
        }

        $href  = null;
        $uri   = $page->getOption('uri');
        $route = $page->getOption('route');
        if (is_string($uri) && $uri !== '') {
            $href = $uri;
        } elseif (is_array($route)) {
            $href = (string) $this->routeHelper->generateUri($route);
        }

        if ($href === null || $href === '') {
            throw new RuntimeException(sprintf(
                'Unable to assemble href for navigation page "%s"',
                $page->getName() ?? $page->getLabel()
            ));
        }

        $this->hrefCache[$hash] = $href;

        return $href;
    }
}
