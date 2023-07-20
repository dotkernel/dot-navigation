<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;

use function count;
use function is_array;

class ArrayProvider implements ProviderInterface
{
    protected ?NavigationContainer $container = null;
    protected array $items                    = [];

    public function __construct(?array $options = [])
    {
        if (isset($options['items']) && is_array($options['items'])) {
            $this->setItems($options['items']);
        }
    }

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

    public function getItems(): array
    {
        return $this->items;
    }

    public function hasItems(): bool
    {
        return count($this->items) > 0;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
