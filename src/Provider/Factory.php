<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

use function is_array;
use function is_string;

class Factory implements FactoryInterface
{
    protected ContainerInterface $container;
    protected ?ProviderPluginManager $providerPluginManager;

    public function __construct(ContainerInterface $container, ?ProviderPluginManager $providerPluginManager = null)
    {
        $this->container             = $container;
        $this->providerPluginManager = $providerPluginManager;
    }

    /**
     * @param array<string, mixed> $specs
     * @throws RuntimeException
     */
    public function create(array $specs): ProviderInterface
    {
        $type = $specs['type'] ?? '';
        if (! is_string($type) || $type === '') {
            throw new RuntimeException('Undefined navigation provider type');
        }

        $options = $specs['options'] ?? null;
        if ($options !== null && ! is_array($options)) {
            throw new RuntimeException('The navigation provider options must be an array');
        }

        return $this->getProviderPluginManager()->build($type, $options);
    }

    public function getProviderPluginManager(): ProviderPluginManager
    {
        if (! $this->providerPluginManager instanceof ProviderPluginManager) {
            $this->providerPluginManager = new ProviderPluginManager($this->container, []);
        }

        return $this->providerPluginManager;
    }
}
