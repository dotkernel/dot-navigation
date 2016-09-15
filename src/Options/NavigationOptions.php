<?php
/**
 * Created by PhpStorm.
 * User: n3vra
 * Date: 6/5/2016
 * Time: 4:19 PM
 */

namespace Dot\Navigation\Options;

use Zend\Stdlib\AbstractOptions;

class NavigationOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $containers;

    /**
     * @var array
     */
    protected $providersMap;

    /**
     * @var bool
     */
    protected $activeRecursion = true;

    /**
     * @var bool
     */
    protected $__strictMode__ = false;

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
     * @return mixed
     */
    public function getProvidersMap()
    {
        return $this->providersMap;
    }

    /**
     * @param mixed $providersMap
     */
    public function setProvidersMap($providersMap)
    {
        $this->providersMap = $providersMap;
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