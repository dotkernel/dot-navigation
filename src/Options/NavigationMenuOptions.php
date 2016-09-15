<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/7/2016
 * Time: 6:24 PM
 */

namespace Dot\Navigation\Options;

use Zend\Stdlib\AbstractOptions;

class NavigationMenuOptions extends AbstractOptions
{
    /** @var int  */
    protected $minDepth = -1;

    /** @var int  */
    protected $maxDepth = -1;

    /** @var string  */
    protected $activeClass = 'active';

    /** @var string  */
    protected $ulClass = 'nav';

    /** @var bool  */
    protected $__strictMode__ = false;

    /**
     * @return int
     */
    public function getMinDepth()
    {
        return $this->minDepth;
    }

    /**
     * @param int $minDepth
     */
    public function setMinDepth($minDepth)
    {
        $this->minDepth = $minDepth;
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * @param int $maxDepth
     */
    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;
    }

    /**
     * @return string
     */
    public function getActiveClass()
    {
        return $this->activeClass;
    }

    /**
     * @param string $activeClass
     */
    public function setActiveClass($activeClass)
    {
        $this->activeClass = $activeClass;
    }

    /**
     * @return string
     */
    public function getUlClass()
    {
        return $this->ulClass;
    }

    /**
     * @param string $ulClass
     */
    public function setUlClass($ulClass)
    {
        $this->ulClass = $ulClass;
    }


}