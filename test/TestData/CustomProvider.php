<?php

declare(strict_types=1);

namespace DotTest\Navigation\TestData;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Provider\ProviderInterface;

/**
 * A navigation provider with no constructor arguments.
 */
class CustomProvider implements ProviderInterface
{
    public function getContainer(): NavigationContainer
    {
        return new NavigationContainer();
    }
}
