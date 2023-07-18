<?php

declare(strict_types=1);

namespace DotTest\Navigation;

use Dot\Navigation\Page;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    public function testWillCreatePage(): void
    {
        $page = new Page();
        $this->assertInstanceOf(Page::class, $page);
    }

    public function testParentAccessors(): void
    {
        $page = new Page();
        $this->assertFalse($page->hasParent());
        $page->setParent(new Page());
        $this->assertTrue($page->hasParent());
        $this->assertInstanceOf(Page::class, $page->getParent());
    }

    public function testWillAddPage(): void
    {
        $page1 = new Page();
        $page2 = new Page();
        $this->assertFalse($page1->hasChildren());
        $this->assertFalse($page2->hasParent());
        $page1->addPage($page2);
        $this->assertTrue($page1->hasChildren());
        $this->assertTrue($page2->hasParent());
    }

    public function testOptionAccessors(): void
    {
        $page = new Page();
        $this->assertIsArray($page->getOptions());
        $this->assertEmpty($page->getOptions());
        $this->assertFalse($page->hasOptions());
        $page->setOption('opt1', 'value1');
        $this->assertIsArray($page->getOptions());
        $this->assertCount(1, $page->getOptions());
        $this->assertTrue($page->hasOptions());
        $this->assertSame('value1', $page->getOption('opt1'));
        $page->setOptions([
            'opt1' => 'value1',
            'opt2' => 'value2',
        ]);
        $this->assertIsArray($page->getOptions());
        $this->assertCount(2, $page->getOptions());
        $this->assertTrue($page->hasOptions());
    }

    public function testAttributeAccessors(): void
    {
        $page = new Page();
        $this->assertIsArray($page->getAttributes());
        $this->assertEmpty($page->getAttributes());
        $this->assertFalse($page->hasAttributes());
        $page->setAttribute('attr1', 'value1');
        $this->assertIsArray($page->getAttributes());
        $this->assertCount(1, $page->getAttributes());
        $this->assertTrue($page->hasAttributes());
        $this->assertSame('value1', $page->getAttribute('attr1'));
        $page->setAttributes([
            'attr1' => 'value1',
            'attr2' => 'value2',
        ]);
        $this->assertIsArray($page->getAttributes());
        $this->assertCount(2, $page->getAttributes());
        $this->assertTrue($page->hasAttributes());
    }

    public function testGetLabel(): void
    {
        $page = new Page();
        $this->assertSame('Not defined', $page->getLabel());
        $page->setOption('label', 'Label');
        $this->assertSame('Label', $page->getLabel());
    }
}
