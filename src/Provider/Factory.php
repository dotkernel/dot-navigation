<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\Exception\RuntimeException;
use Interop\Container\ContainerInterface;

/**
 * Class Factory
 * @package Dot\Navigation\Provider
 */
class Factory
{
    /** @var  ContainerInterface */
    protected $container;

    /** @var  ProviderPluginManager */
    protected $providerPluginManager;

    /**
     * Factory constructor.
     * @param ContainerInterface $container
     * @param ProviderPluginManager|null $providerPluginManager
     */
    public function __construct(ContainerInterface $container, ProviderPluginManager $providerPluginManager = null)
    {
        $this->container = $container;
        $this->providerPluginManager = $providerPluginManager;
    }

    /**
     * @param array $specs
     * @return ProviderInterface
     */
    public function create(array $specs): ProviderInterface
    {
        $type = $specs['type'] ?? '';
        if (empty($type)) {
            throw new RuntimeException('Undefined navigation provider type');
        }

        $options = $specs['options'] ?? null;
        return $this->getProviderPluginManager()->get($type, $options);
    }

    /**
     * @return ProviderPluginManager
     */
    public function getProviderPluginManager(): ProviderPluginManager
    {
        if (!$this->providerPluginManager) {
            $this->providerPluginManager = new ProviderPluginManager($this->container, []);
        }

        return $this->providerPluginManager;
    }
}
