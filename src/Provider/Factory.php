<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

class Factory implements FactoryInterface
{
    protected ContainerInterface $container;
    protected ?ProviderPluginManager $providerPluginManager;

    public function __construct(ContainerInterface $container, ?ProviderPluginManager $providerPluginManager = null)
    {
        $this->container             = $container;
        $this->providerPluginManager = $providerPluginManager;
    }

    public function create(array $specs): ProviderInterface
    {
        $type = $specs['type'] ?? '';
        if (empty($type)) {
            throw new RuntimeException('Undefined navigation provider type');
        }

        return $this->getProviderPluginManager()->get($type, $specs['options'] ?? null);
    }

    public function getProviderPluginManager(): ProviderPluginManager
    {
        if (! $this->providerPluginManager instanceof ProviderPluginManager) {
            $this->providerPluginManager = new ProviderPluginManager($this->container, []);
        }

        return $this->providerPluginManager;
    }
}
