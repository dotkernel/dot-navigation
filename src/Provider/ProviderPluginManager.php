<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Factory\InvokableFactory;

use function gettype;
use function is_object;
use function sprintf;

/**
 * @template InstanceType
 * @extends AbstractPluginManager<InstanceType>
 */
class ProviderPluginManager extends AbstractPluginManager
{
    protected string $instanceOf = ProviderInterface::class;

    protected array $factories = [
        ArrayProvider::class => InvokableFactory::class,
    ];

    protected array $aliases = [
        'arrayprovider' => ArrayProvider::class,
        'arrayProvider' => ArrayProvider::class,
        'ArrayProvider' => ArrayProvider::class,
        'array'         => ArrayProvider::class,
        'Array'         => ArrayProvider::class,
    ];

    public function validate(mixed $instance): void
    {
        if (! $instance instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                static::class,
                $this->instanceOf,
                is_object($instance) ? $instance::class : gettype($instance)
            ));
        }
    }
}
