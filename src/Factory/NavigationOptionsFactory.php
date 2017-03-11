<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Factory;

use Dot\Navigation\Options\NavigationOptions;
use Interop\Container\ContainerInterface;

/**
 * Class NavigationOptionsFactory
 * @package Dot\Navigation\Factory
 */
class NavigationOptionsFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return NavigationOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName): NavigationOptions
    {
        $config = $container->get('config')['dot_navigation'];
        return new $requestedName($config);
    }
}
