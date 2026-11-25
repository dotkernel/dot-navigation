<?php

declare(strict_types=1);

namespace Dot\Navigation\Options;

use Laminas\Stdlib\AbstractOptions;

/**
 * @extends AbstractOptions<mixed>
 */
class NavigationOptions extends AbstractOptions
{
    /** @var array<string, array<string, mixed>> */
    protected array $containers     = [];
    protected bool $activeRecursion = true;

    /**
     * @param array<string, mixed>|null $options
     */
    public function __construct(?array $options = null)
    {
        $this->__strictMode__ = false;
        parent::__construct($options);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getContainers(): array
    {
        return $this->containers;
    }

    /**
     * @param array<string, array<string, mixed>> $containers
     */
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
