<?php

declare(strict_types=1);

namespace Dot\Navigation\Filter;

use Dot\Navigation\Page;
use Dot\Navigation\Service\NavigationInterface;
use RecursiveFilterIterator;
use RecursiveIterator;

/**
 * Skips the pages that the current user is not allowed to see, and their sub-pages.
 *
 * @extends RecursiveFilterIterator<int, Page, RecursiveIterator<int, Page>>
 */
final class IsAllowedFilter extends RecursiveFilterIterator
{
    /**
     * @param RecursiveIterator<int, Page> $iterator
     */
    public function __construct(
        private readonly RecursiveIterator $iterator,
        private readonly NavigationInterface $navigation,
    ) {
        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        return $this->navigation->isAllowed($this->current());
    }

    public function getChildren(): self
    {
        return new self($this->iterator->getChildren(), $this->navigation);
    }
}
