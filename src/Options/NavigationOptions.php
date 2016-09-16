<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation\Options;

use Dot\Navigation\Exception\InvalidArgumentException;
use Zend\Stdlib\AbstractOptions;

/**
 * Class NavigationOptions
 * @package Dot\Navigation\Options
 */
class NavigationOptions extends AbstractOptions
{
    /** @var  array */
    protected $containers;

    /** @var  array */
    protected $providersMap;

    /** @var bool  */
    protected $activeRecursion = true;

    /** @var  MenuOptions */
    protected $menuOptions;

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

    /**
     * @return MenuOptions
     */
    public function getMenuOptions()
    {
        return $this->menuOptions;
    }

    /**
     * @param MenuOptions|array $menuOptions
     * @return NavigationOptions
     */
    public function setMenuOptions($menuOptions)
    {
        if($menuOptions instanceof MenuOptions) {
            $this->menuOptions = $menuOptions;
        }
        elseif(is_array($menuOptions)) {
            $this->menuOptions = new MenuOptions($menuOptions);
        }
        else {
            throw new InvalidArgumentException('Menu options must be an array or instance of' . MenuOptions::class);
        }

        return $this;
    }



}