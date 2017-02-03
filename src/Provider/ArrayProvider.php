<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-navigation
 * @author: n3vrax
 * Date: 6/5/2016
 * Time: 5:20 PM
 */

declare(strict_types = 1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;

/**
 * Class ArrayProvider
 * @package Dot\Navigation\Provider
 */
class ArrayProvider implements ProviderInterface
{
    /**
     * @var NavigationContainer
     */
    protected $container;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * ArrayProvider constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (isset($options['items']) && is_array($options['items'])) {
            $this->setItems($options['items']);
        }
    }

    /**
     * @return NavigationContainer
     */
    public function getContainer(): NavigationContainer
    {
        if ($this->container instanceof NavigationContainer) {
            return $this->container;
        }

        $this->container = new NavigationContainer();
        foreach ($this->items as $pageSpecs) {
            $page = $this->getPage($pageSpecs);
            $this->container->addPage($page);
        }

        return $this->container;
    }

    /**
     * @param array $spec
     * @return Page
     */
    protected function getPage(array $spec): Page
    {
        $page = new Page();

        if (isset($spec['attributes'])) {
            $page->setAttributes($spec['attributes']);
        }

        if (isset($spec['options'])) {
            $page->setOptions($spec['options']);
        }

        if (isset($spec['pages'])) {
            foreach ($spec['pages'] as $childSpec) {
                $page->addPage($this->getPage($childSpec));
            }
        }

        return $page;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }
}
