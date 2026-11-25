<?php

declare(strict_types=1);

namespace Dot\Navigation\Provider;

use Dot\Navigation\Exception\InvalidArgumentException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;

use function count;
use function is_array;
use function sprintf;

class ArrayProvider implements ProviderInterface
{
    protected ?NavigationContainer $container = null;
    /** @var array<array<string, mixed>> */
    protected array $items = [];

    /**
     * @param array<string, mixed>|null $options
     */
    public function __construct(?array $options = [])
    {
        if (isset($options['items']) && is_array($options['items'])) {
            $this->setItems($options['items']);
        }
    }

    /**
     * @throws InvalidArgumentException
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
     * @param array<string, mixed> $spec
     * @throws InvalidArgumentException
     */
    protected function getPage(array $spec): Page
    {
        foreach (['attributes', 'options', 'pages'] as $key) {
            if (isset($spec[$key]) && ! is_array($spec[$key])) {
                throw new InvalidArgumentException(sprintf('The navigation page key "%s" must be an array', $key));
            }
        }

        $page = new Page();

        if (isset($spec['attributes'])) {
            $page->setAttributes($spec['attributes']);
        }

        if (isset($spec['options'])) {
            $page->setOptions($spec['options']);
        }

        if (isset($spec['pages'])) {
            foreach ($spec['pages'] as $childSpec) {
                if (! is_array($childSpec)) {
                    throw new InvalidArgumentException('Each item of the navigation page key "pages" must be an array');
                }
                $page->addPage($this->getPage($childSpec));
            }
        }

        return $page;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function hasItems(): bool
    {
        return count($this->items) > 0;
    }

    /**
     * @param array<array<string, mixed>> $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
