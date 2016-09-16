<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation\Filter;


use Dot\Navigation\Service\Navigation;

/**
 * Class IsAllowedFilter
 * @package Dot\Navigation\Filter
 */
class IsAllowedFilter extends \RecursiveFilterIterator
{
    /** @var  Navigation */
    protected $navigation;

    /**
     * IsAllowedFilter constructor.
     * @param \RecursiveIterator $iterator
     * @param Navigation $navigation
     */
    public function __construct(\RecursiveIterator $iterator, Navigation $navigation)
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