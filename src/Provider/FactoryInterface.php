<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

interface FactoryInterface
{
    public function create(array $specs): ProviderInterface;

    public function getProviderPluginManager(): ProviderPluginManager;
}
