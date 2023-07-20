<?php

declare(strict_types=1);

namespace DotTest\Navigation;

use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Page;
use PHPUnit\Framework\TestCase;

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
        $this->assertInstanceOf(Page::class, $currentPage);
        $this->assertSame($pages[0]->getOption('opt'), $currentPage->getOption('opt'));
    }

    public function testWillReturnNextChild(): void
    {
        $pages = $this->getTestPages();

        $navigationContainer = new NavigationContainer();
        $navigationContainer->addPages($pages);
        $navigationContainer->next();

        $currentPage = $navigationContainer->current();
        $this->assertInstanceOf(Page::class, $currentPage);
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

    /**
     * @return Page[]
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
