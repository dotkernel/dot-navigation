<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Factory\InvokableFactory;

class ProviderPluginManager extends AbstractPluginManager
{
    /** @var string $instanceOf */
    protected $instanceOf = ProviderInterface::class;

    /** @var array */
    protected $factories = [
        ArrayProvider::class => InvokableFactory::class,
    ];

    /** @var string[] $aliases */
    protected $aliases = [
        'arrayprovider' => ArrayProvider::class,
        'arrayProvider' => ArrayProvider::class,
        'ArrayProvider' => ArrayProvider::class,
        'array'         => ArrayProvider::class,
        'Array'         => ArrayProvider::class,
    ];
}
