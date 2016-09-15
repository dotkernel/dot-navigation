<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/3/2016
 * Time: 8:08 PM
 */

namespace Dot\Navigation\Provider;

use Dot\Navigation\NavigationContainer;

interface ProviderInterface
{
    /**
     * @return NavigationContainer
     */
    public function getContainer();
}