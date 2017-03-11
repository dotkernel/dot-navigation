<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

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
    public function getContainer(): NavigationContainer;
}
