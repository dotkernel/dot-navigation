<?php

declare(strict_types=1);

namespace DotTest\Navigation\TestData;

use Psr\Container\ContainerInterface;

use function array_key_exists;
use function sprintf;

/**
 * A minimal PSR-11 container for the tests. It returns the services that the constructor receives.
 */
class Container implements ContainerInterface
{
    /**
     * @param array<string, mixed> $services
     */
    public function __construct(
        private array $services = [],
    ) {
    }

    public function get(string $id): mixed
    {
        if (! $this->has($id)) {
            throw new ServiceNotFoundException(sprintf('Service "%s" is not in the container', $id));
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}
