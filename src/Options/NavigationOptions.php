<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class NavigationOptions
 * @package Dot\Navigation\Options
 */
class NavigationOptions extends AbstractOptions
{
    /** @var  array */
    protected $containers;

    /** @var bool */
    protected $activeRecursion = true;

    /**
     * NavigationOptions constructor.
     * @param array $options
     */
    public function __construct($options = null)
    {
        $this->__strictMode__ = false;
        parent::__construct($options);
    }

    /**
     * @return mixed
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * @param mixed $containers
     */
    public function setContainers($containers)
    {
        $this->containers = $containers;
    }

    /**
     * @return boolean
     */
    public function getActiveRecursion()
    {
        return $this->activeRecursion;
    }

    /**
     * @param boolean $activeRecursion
     */
    public function setActiveRecursion($activeRecursion)
    {
        $this->activeRecursion = $activeRecursion;
    }
}
