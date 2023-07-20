<?php

declare(strict_types=1);

namespace Dot\Navigation;

use RecursiveIterator;
use RecursiveIteratorIterator;

use function count;

class NavigationContainer implements RecursiveIterator
{
    protected int $index      = 0;
    protected array $children = [];

    public function __construct(array $pages = [])
    {
        $this->addPages($pages);
    }

    /**
     * @param Page[] $pages
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

    public function current(): NavigationContainer
    {
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

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    public function getChildren(): NavigationContainer
    {
        return $this->children[$this->index];
    }

    public function findOneByAttribute(string $attribute, mixed $value): ?Page
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getAttribute($attribute) === $value) {
                return $page;
            }
        }

        return null;
    }

    public function findByAttribute(string $attribute, mixed $value): array
    {
        $result   = [];
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getAttribute($attribute) === $value) {
                $result[] = $page;
            }
        }

        return $result;
    }

    public function findOneByOption(string $option, mixed $value): ?Page
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) === $value) {
                return $page;
            }
        }

        return null;
    }

    public function findByOption(string $option, mixed $value): array
    {
        $result   = [];
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) === $value) {
                $result[] = $page;
            }
        }

        return $result;
    }
}
