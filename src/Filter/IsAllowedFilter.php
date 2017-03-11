<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Filter;

use Dot\Navigation\Service\NavigationInterface;

/**
 * Class IsAllowedFilter
 * @package Dot\Navigation\Filter
 */
class IsAllowedFilter extends \RecursiveFilterIterator
{
    /** @var  NavigationInterface */
    protected $navigation;

    /**
     * IsAllowedFilter constructor.
     * @param \RecursiveIterator $iterator
     * @param NavigationInterface $navigation
     */
    public function __construct(\RecursiveIterator $iterator, NavigationInterface $navigation)
    {
        $this->navigation = $navigation;
        parent::__construct($iterator);
    }

    /**
     * @return bool
     */
    public function accept(): bool
    {
        return $this->navigation->isAllowed($this->current());
    }

    /**
     * @return IsAllowedFilter
     */
    public function getChildren(): IsAllowedFilter
    {
        /** @var \RecursiveIterator $innerIterator */
        $innerIterator = $this->getInnerIterator();
        return new self($innerIterator->getChildren(), $this->navigation);
    }
}
