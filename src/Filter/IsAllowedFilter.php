<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

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
    public function accept()
    {
        return $this->navigation->isAllowed($this->current());
    }

    /**
     * @return IsAllowedFilter
     */
    public function getChildren()
    {
        /** @var \RecursiveIterator $innerIterator */
        $innerIterator = $this->getInnerIterator();
        return new self($innerIterator->getChildren(), $this->navigation);
    }

}