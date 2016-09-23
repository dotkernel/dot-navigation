<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation\Provider;

use Dot\Navigation\NavigationContainer;

/**
 * Interface ProviderInterface
 * @package Dot\Navigation\Provider
 */
interface ProviderInterface
{
    /**
     * @return NavigationContainer
     */
    public function getContainer();
}