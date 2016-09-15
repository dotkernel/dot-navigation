<?php
/**
 * Created by PhpStorm.
 * User: n3vrax
 * Date: 6/3/2016
 * Time: 8:00 PM
 */

namespace Dot\Navigation;

use RecursiveIterator;

class NavigationContainer implements \RecursiveIterator
{
    /**
     * Index of current active child
     * @var int
     */
    protected $index = 0;

    /**
     * Child nodes
     * @var array
     */
    protected $children = [];

    /**
     * NavigationContainer constructor.
     * @param array $pages
     */
    public function __construct(array $pages = [])
    {
        $this->addPages($pages);
    }


    public function current()
    {
        return $this->children[$this->index];
    }

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->children[$this->index]);
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    public function getChildren()
    {
        return $this->children[$this->index];
    }

    /**
     * @param array $pages
     */
    public function addPages(array $pages)
    {
        foreach ($pages as $page)
        {
            $this->addPage($page);
        }
    }

    /**
     * @param Page $page
     * @return $this
     */
    public function addPage(Page $page)
    {
        $this->children[] = $page;
        return $this;
    }

    /**
     * Find a single child by attribute
     *
     * @param string $attribute
     * @param mixed $value
     * @return Page|null
     */
    public function findOneByAttribute($attribute, $value)
    {
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var Page $page */
        foreach ($iterator as $page) {
            if($page->getAttribute($attribute) == $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * Find all children by attribute
     *
     * @param string $attribute
     * @param mixed $value
     * @return array
     */
    public function findByAttribute($attribute, $value)
    {
        $result   = [];
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getAttribute($attribute) == $value) {
                $result[] = $page;
            }
        }
        return $result;
    }

    /**
     * Finds a single child by option.
     *
     * @param string $option
     * @param mixed $value
     * @return Page|null
     */
    public function findOneByOption($option, $value)
    {
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) == $value) {
                return $page;
            }
        }
        return null;
    }
    /**
     * Finds all children by option.
     *
     * @param string $option
     * @param mixed $value
     * @return array
     */
    public function findByOption($option, $value)
    {
        $result   = [];
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) == $value) {
                $result[] = $page;
            }
        }
        return $result;
    }

}