<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/7/2016
 * Time: 6:34 PM
 */

namespace Dot\Navigation\Filter;

use Dot\Navigation\NavigationService;

class IsAllowedFilter extends \RecursiveFilterIterator
{
    /** @var  NavigationService */
    protected $navigation;

    public function __construct(\RecursiveIterator $iterator, NavigationService $navigation)
    {
        $this->navigation = $navigation;
        parent::__construct($iterator);
    }

    public function accept()
    {
        return $this->navigation->isAllowed($this->current());
    }

    public function getChildren()
    {
        /** @var \RecursiveIterator $innerIterator */
        $innerIterator = $this->getInnerIterator();
        return new self($innerIterator->getChildren(), $this->navigation);
    }

}