<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\NavigationContainer;

interface ProviderInterface
{
    public function getContainer(): NavigationContainer;
}
