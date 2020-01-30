<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Provider;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * Class ProviderPluginManager
 * @package Dot\Navigation\Provider
 */
class ProviderPluginManager extends AbstractPluginManager
{
    /** @var  string */
    protected $instanceOf = ProviderInterface::class;

    /** @var array */
    protected $factories = [
        ArrayProvider::class => InvokableFactory::class,
    ];

    protected $aliases = [
        'arrayprovider' => ArrayProvider::class,
        'arrayProvider' => ArrayProvider::class,
        'ArrayProvider' => ArrayProvider::class,
        'array' => ArrayProvider::class,
        'Array' => ArrayProvider::class,
    ];
}
