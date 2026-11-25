<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

use function class_exists;
use function is_a;
use function sprintf;

/**
 * Creates a navigation provider with "new $requestedName($options)", or with no argument when $options is null.
 * Use it in place of the InvokableFactory of Laminas.
 */
class InvokableProviderFactory
{
    /**
     * @param array<string, mixed>|null $options
     * @throws RuntimeException
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        ?array $options = null
    ): ProviderInterface {
        if (! class_exists($requestedName) || ! is_a($requestedName, ProviderInterface::class, true)) {
            throw new RuntimeException(sprintf('"%s" is not a navigation provider class', $requestedName));
        }

        return $options === null ? new $requestedName() : new $requestedName($options);
    }
}
