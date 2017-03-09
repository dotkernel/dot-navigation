<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation\Provider;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

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
