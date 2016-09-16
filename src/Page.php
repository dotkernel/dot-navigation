<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

namespace Dot\Navigation;

/**
 * Class Page
 * @package Dot\Navigation
 */
class Page extends Container
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var null|Page
     */
    protected $parent;

    /**
     * @param Page $parent
     */
    public function setParent(Page $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return null !== $this->parent;
    }

    /**
     * @return Page|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Page $page
     * @return $this
     */
    public function addPage(Page $page)
    {
        $page->setParent($this);
        return parent::addPage($page);
    }

    /**
     * @param $option
     * @param $value
     * @return $this
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
        return $this;
    }

    /**
     * @param $option
     * @return mixed|null
     */
    public function getOption($option)
    {
        return isset($this->options[$option]) ? $this->options[$option] : null;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $attribute
     * @param $value
     * @return $this
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }

    /**
     * @param $attribute
     * @return mixed|null
     */
    public function getAttribute($attribute)
    {
        return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : null;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return mixed|null
     */
    public function getName()
    {
        return $this->getOption('name');
    }
}