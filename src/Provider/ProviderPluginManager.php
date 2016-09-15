<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/3/2016
 * Time: 8:09 PM
 */

namespace Dot\Navigation\Provider;

use Dot\Navigation\Exception\RuntimeException;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

class ProviderPluginManager extends AbstractPluginManager
{
    protected $factories = [
        ArrayProvider::class => InvokableFactory::class,
    ];

    public function validate($instance)
    {
        if($instance instanceof ProviderInterface) {
            return;
        }
        
        throw new RuntimeException(sprintf(
            'Navigation provider must implement "%s", but "%s" given',
            ProviderInterface::class,
            is_object($instance) ? get_class($instance) : gettype($instance)
        ));
    }
}