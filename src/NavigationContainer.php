<?php

declare(strict_types=1);

namespace Dot\Navigation;

use Dot\Navigation\Exception\RuntimeException;
use RecursiveIterator;
use RecursiveIteratorIterator;

/**
 * @implements RecursiveIterator<int, Page>
 */
class NavigationContainer implements RecursiveIterator
{
    protected int $index = 0;
    /** @var list<Page> */
    protected array $children = [];

    /**
     * @param array<Page> $pages
     */
    public function __construct(array $pages = [])
    {
        $this->addPages($pages);
    }

    /**
     * @param array<Page> $pages
     */
    public function addPages(array $pages): void
    {
        foreach ($pages as $page) {
            $this->addPage($page);
        }
    }

    public function addPage(Page $page): void
    {
        $this->children[] = $page;
    }

    /**
     * @throws RuntimeException
     */
    public function current(): Page
    {
        if (! $this->valid()) {
            throw new RuntimeException('The navigation container has no page at the current position');
        }

        return $this->children[$this->index];
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return isset($this->children[$this->index]);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * Tells if this container has pages. Templates use it to show a sub-menu.
     * Unlike the RecursiveIterator contract, it does not check the current page only.
     * Because of this, RecursiveIteratorIterator::LEAVES_ONLY gives no pages.
     */
    public function hasChildren(): bool
    {
        return $this->hasPages();
    }

    public function hasPages(): bool
    {
        return $this->children !== [];
    }

    /**
     * @throws RuntimeException
     */
    public function getChildren(): Page
    {
        return $this->current();
    }

    public function findOneByAttribute(string $attribute, mixed $value): ?Page
    {
        foreach ($this->iteratePages() as $page) {
            if ($page->getAttribute($attribute) === $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * @return list<Page>
     */
    public function findByAttribute(string $attribute, mixed $value): array
    {
        $result = [];
        foreach ($this->iteratePages() as $page) {
            if ($page->getAttribute($attribute) === $value) {
                $result[] = $page;
            }
        }

        return $result;
    }

    public function findOneByOption(string $option, mixed $value): ?Page
    {
        foreach ($this->iteratePages() as $page) {
            if ($page->getOption($option) === $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * @return list<Page>
     */
    public function findByOption(string $option, mixed $value): array
    {
        $result = [];
        foreach ($this->iteratePages() as $page) {
            if ($page->getOption($option) === $value) {
                $result[] = $page;
            }
        }

        return $result;
    }

    /**
     * @return RecursiveIteratorIterator<$this>
     */
    private function iteratePages(): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);
    }
}
