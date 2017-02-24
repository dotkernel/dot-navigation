<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation;

/**
 * Class Page
 * @package Dot\Navigation
 */
class Page extends NavigationContainer
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
     * @return bool
     */
    public function hasParent(): bool
    {
        return null !== $this->parent;
    }

    /**
     * @return Page|null
     */
    public function getParent(): ?Page
    {
        return $this->parent;
    }

    /**
     * @param Page $parent
     */
    public function setParent(Page $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param Page $page
     */
    public function addPage(Page $page)
    {
        $page->setParent($this);
        parent::addPage($page);
    }

    /**
     * @param string $option
     * @param mixed $value
     */
    public function setOption(string $option, $value)
    {
        $this->options[$option] = $value;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     */
    public function setAttribute(string $attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    /**
     * @param string $attribute
     * @return mixed
     */
    public function getAttribute(string $attribute)
    {
        return $this->attributes[$attribute] ?? null;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->getOption('name');
    }

    /**
     * @param string $option
     * @return mixed
     */
    public function getOption(string $option)
    {
        return $this->options[$option] ?? null;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        $label = $this->getOption('label');
        if (!is_string($label) || empty($label)) {
            $label = 'Not defined';
        }
        return $label;
    }
}
