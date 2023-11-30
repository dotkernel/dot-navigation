<?php

declare(strict_types=1);

namespace Dot\Navigation\Options;

use Laminas\Stdlib\AbstractOptions;

/**
 * @template TValue
 * @template-extends AbstractOptions<TValue>
 */
class NavigationOptions extends AbstractOptions
{
    protected array $containers     = [];
    protected bool $activeRecursion = true;

    public function __construct(?array $options = null)
    {
        $this->__strictMode__ = false;
        parent::__construct($options);
    }

    public function getContainers(): array
    {
        return $this->containers;
    }

    public function setContainers(array $containers): void
    {
        $this->containers = $containers;
    }

    public function getActiveRecursion(): bool
    {
        return $this->activeRecursion;
    }

    public function setActiveRecursion(bool $activeRecursion): void
    {
        $this->activeRecursion = $activeRecursion;
    }
}
