<?php

declare(strict_types=1);

namespace Dot\Navigation\Service;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;
use Mezzio\Router\RouteResult;

interface NavigationInterface
{
    public function getRouteResult(): ?RouteResult;

    public function setRouteResult(RouteResult $routeResult): void;

    /**
     * Sets the query parameters of the current request. isActive() uses them, not the PHP superglobal.
     *
     * @param array<string, mixed> $queryParams
     */
    public function setQueryParams(array $queryParams): void;

    public function getIsActiveRecursion(): bool;

    public function setIsActiveRecursion(bool $isActiveRecursion): void;

    /**
     * @return array<string, bool>
     */
    public function getIsActiveCache(): array;

    /**
     * @return array<string, string>
     */
    public function getHrefCache(): array;

    public function getContainer(string $name): NavigationContainer;

    public function isAllowed(Page $page): bool;

    public function isActive(Page $page): bool;

    public function getHref(Page $page): string;
}
