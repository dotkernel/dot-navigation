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

    public function getIsActiveRecursion(): bool;

    public function setIsActiveRecursion(bool $isActiveRecursion): void;

    public function getIsActiveCache(): array;

    public function getHrefCache(): array;

    public function getContainer(string $name): NavigationContainer;

    public function isAllowed(Page $page): bool;

    public function isActive(Page $page): bool;

    public function getHref(Page $page): string;
}
