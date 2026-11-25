<?php

declare(strict_types=1);

namespace DotTest\Navigation;

use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;
use PHPUnit\Framework\TestCase;
use RecursiveIteratorIterator;

use function array_map;
use function iterator_to_array;

class NavigationContainerTest extends TestCase
{
    protected int $count = 5;

    public function testWillAddPages(): void
    {
        $navigationContainer = new NavigationContainer();
        $this->assertFalse($navigationContainer->hasChildren());
        $navigationContainer->addPages([
            new Page(),
        ]);
        $this->assertTrue($navigationContainer->hasChildren());
    }

    public function testWillAddPage(): void
    {
        $navigationContainer = new NavigationContainer();
        $this->assertFalse($navigationContainer->hasChildren());
        $navigationContainer->addPage(new Page());
        $this->assertTrue($navigationContainer->hasChildren());
    }

    public function testWillReturnCurrentChild(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $currentPage = $navigationContainer->current();
        $this->assertSame($pages[0]->getOption('opt'), $currentPage->getOption('opt'));
    }

    public function testWillReturnNextChild(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);
        $navigationContainer->next();

        $currentPage = $navigationContainer->current();
        $this->assertSame($pages[1]->getOption('opt'), $currentPage->getOption('opt'));
    }

    public function testWillReturnCurrentKey(): void
    {
        $navigationContainer = new NavigationContainer();
        $this->assertSame(0, $navigationContainer->key());
    }

    public function testWillReturnInvalidIfNoItems(): void
    {
        $navigationContainer = new NavigationContainer();
        $this->assertFalse($navigationContainer->valid());
    }

    public function testWillReturnValidWhileIndexExists(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $this->assertTrue($navigationContainer->valid());
        for ($i = 0; $i < $this->count; $i++) {
            $this->assertTrue($navigationContainer->valid());
            $navigationContainer->next();
        }
        $this->assertFalse($navigationContainer->valid());
    }

    public function testWillReturnFalseIfNotHasChildren(): void
    {
        $navigationContainer = new NavigationContainer();
        $this->assertFalse($navigationContainer->hasChildren());
    }

    public function testWillReturnTrueIfHasChildren(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $this->assertTrue($navigationContainer->hasChildren());
    }

    public function testWillNotFindOneByNotExistingAttribute(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $result = $navigationContainer->findOneByAttribute('test', 'test');
        $this->assertNull($result);
    }

    public function testWillFindOneByExistingAttribute(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $result = $navigationContainer->findOneByAttribute('attr', 'attr #0');
        $this->assertInstanceOf(Page::class, $result);
        $this->assertSame($pages[0]->getAttribute('attr'), $result->getAttribute('attr'));
    }

    public function testWillNotFindManyByNonExistingAttribute(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $results = $navigationContainer->findByAttribute('test', 'test');
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testWillFindManyByExistingAttribute(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $results = $navigationContainer->findByAttribute('attr', 'attr #0');
        $this->assertIsArray($results);
        $this->assertInstanceOf(Page::class, $results[0]);
        $this->assertSame($pages[0]->getAttribute('attr'), $results[0]->getAttribute('attr'));
    }

    public function testWillNotFindOneByNonExistingOption(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $result = $navigationContainer->findOneByOption('test', 'test');
        $this->assertNull($result);
    }

    public function testWillFindOneByExistingOption(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $result = $navigationContainer->findOneByOption('opt', 'opt #0');
        $this->assertInstanceOf(Page::class, $result);
        $this->assertSame($pages[0]->getOption('opt'), $result->getOption('opt'));
    }

    public function testWillNotFindManyByNonExistingOption(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $results = $navigationContainer->findByOption('test', 'test');
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testWillFindManyByExistingOption(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);

        $results = $navigationContainer->findByOption('opt', 'opt #0');
        $this->assertIsArray($results);
        $this->assertInstanceOf(Page::class, $results[0]);
        $this->assertSame($pages[0]->getAttribute('opt'), $results[0]->getAttribute('opt'));
    }

    public function testCurrentWillThrowOnEmptyContainer(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The navigation container has no page at the current position');
        (new NavigationContainer())->current();
    }

    public function testHasPagesTellsIfPageHasSubPages(): void
    {
        $parent = new Page();
        $leaf   = new Page();
        $parent->addPage(new Page());

        $this->assertTrue($parent->hasPages());
        $this->assertFalse($leaf->hasPages());
        $this->assertTrue($parent->hasChildren());
        $this->assertFalse($leaf->hasChildren());
    }

    public function testSelfFirstIterationGivesAllPages(): void
    {
        $parent = new Page();
        $parent->setOption('label', 'parent');
        $child = new Page();
        $child->setOption('label', 'child');
        $parent->addPage($child);
        $leaf = new Page();
        $leaf->setOption('label', 'leaf');

        $iterator = new RecursiveIteratorIterator(
            new NavigationContainer([$parent, $leaf]),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $labels   = array_map(
            static fn (Page $page): string => $page->getLabel(),
            iterator_to_array($iterator, false)
        );

        $this->assertSame(['parent', 'child', 'leaf'], $labels);
    }

    public function testFindIncludesSubPages(): void
    {
        $parent = new Page();
        $child  = new Page();
        $child->setOption('name', 'child');
        $parent->addPage($child);

        $container = new NavigationContainer([$parent]);
        $this->assertSame($child, $container->findOneByOption('name', 'child'));
        $this->assertSame([$child], $container->findByOption('name', 'child'));
    }

    /**
     * @return list<Page>
     */
    protected function getTestPages(): array
    {
        $pages = [];

        for ($i = 0; $i < $this->count; $i++) {
            $page = new Page();
            $page->setOption('opt', 'opt #' . $i);
            $page->setAttribute('attr', 'attr #' . $i);
            $pages[] = $page;
        }

        return $pages;
    }
}
