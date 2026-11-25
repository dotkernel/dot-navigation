<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

use function class_exists;
use function get_debug_type;
use function is_callable;
use function is_string;
use function sprintf;

/**
 * Creates navigation providers from the "provider_manager" config.
 * It supports only the "factories" and "aliases" keys, and it needs no Laminas class.
 */
class ProviderPluginManager
{
    /** @var array<string, callable|class-string> */
    protected array $factories = [
        ArrayProvider::class => InvokableProviderFactory::class,
    ];

    /** @var array<string, string> */
    protected array $aliases = [
        'arrayprovider' => ArrayProvider::class,
        'arrayProvider' => ArrayProvider::class,
        'ArrayProvider' => ArrayProvider::class,
        'array'         => ArrayProvider::class,
        'Array'         => ArrayProvider::class,
    ];

    /**
     * @param array{factories?: array<string, callable|class-string>, aliases?: array<string, string>} $config
     */
    public function __construct(
        protected ContainerInterface $container,
        array $config = [],
    ) {
        $this->factories = [...$this->factories, ...$config['factories'] ?? []];
        $this->aliases   = [...$this->aliases, ...$config['aliases'] ?? []];
    }

    public function has(string $name): bool
    {
        return isset($this->factories[$this->aliases[$name] ?? $name]);
    }

    /**
     * @param array<string, mixed>|null $options
     * @throws RuntimeException
     */
    public function build(string $name, ?array $options = null): ProviderInterface
    {
        $resolved = $this->aliases[$name] ?? $name;
        $factory  = $this->factories[$resolved] ?? null;
        if (is_string($factory) && class_exists($factory)) {
            $factory = new $factory();
        }

        if (! is_callable($factory)) {
            throw new RuntimeException(sprintf('Navigation provider "%s" is not registered', $name));
        }

        $provider = $factory($this->container, $resolved, $options);
        if (! $provider instanceof ProviderInterface) {
            throw new RuntimeException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                static::class,
                ProviderInterface::class,
                get_debug_type($provider)
            ));
        }

        return $provider;
    }
}
