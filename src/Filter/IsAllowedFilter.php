<?php

declare(strict_types=1);

namespace Dot\Navigation\Filter;

use Dot\Navigation\Service\NavigationInterface;
use RecursiveFilterIterator;
use RecursiveIterator;

class IsAllowedFilter extends RecursiveFilterIterator
{
    protected NavigationInterface $navigation;

    public function __construct(RecursiveIterator $iterator, NavigationInterface $navigation)
    {
        $this->navigation = $navigation;
        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        return $this->navigation->isAllowed($this->current());
    }

    public function getChildren(): IsAllowedFilter
    {
        /** @var RecursiveIterator $innerIterator */
        $innerIterator = $this->getInnerIterator();
        return new self($innerIterator->getChildren(), $this->navigation);
    }
}
