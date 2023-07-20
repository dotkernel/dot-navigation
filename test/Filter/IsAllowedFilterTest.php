<?php

declare(strict_types=1);

namespace DotTest\Navigation\Filter;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Filter\IsAllowedFilter;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Provider\FactoryInterface;
use Dot\Navigation\Service\Navigation;
use Dot\Navigation\Service\NavigationInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use RecursiveIterator;

class IsAllowedFilterTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testWillAcceptWithoutAuthorization(): void
    {
        $iterator = $this->createMock(RecursiveIterator::class);
        $factory  = $this->createMock(FactoryInterface::class);
        $route    = $this->createMock(RouteHelper::class);
        $options  = $this->createMock(NavigationOptions::class);

        $navigation = new Navigation($factory, $route, $options, null);
        $filter     = $this->getMockBuilder(IsAllowedFilter::class)
            ->setConstructorArgs([$iterator, $navigation])
            ->onlyMethods(['current'])
            ->getMock();
        $filter->method('current')->willReturn(new Page());

        $this->assertTrue($filter->accept());
    }

    /**
     * @throws Exception
     */
    public function testWillAcceptWithAuthorization(): void
    {
        $iterator      = $this->createMock(RecursiveIterator::class);
        $factory       = $this->createMock(FactoryInterface::class);
        $route         = $this->createMock(RouteHelper::class);
        $options       = $this->createMock(NavigationOptions::class);
        $authorization = $this->createMock(AuthorizationInterface::class);

        $authorization->expects($this->once())->method('isGranted')->willReturn(true);

        $page = new Page();
        $page->setOption('permission', 'test');
        $page->setOption('roles', []);

        $navigation = new Navigation($factory, $route, $options, $authorization);
        $filter     = $this->getMockBuilder(IsAllowedFilter::class)
            ->setConstructorArgs([$iterator, $navigation])
            ->onlyMethods(['current'])
            ->getMock();
        $filter->method('current')->willReturn($page);

        $this->assertTrue($filter->accept());
    }

    /**
     * @throws Exception
     */
    public function testGetChildren(): void
    {
        $navigation = $this->createMock(NavigationInterface::class);

        $filter = new IsAllowedFilter(new NavigationContainer([new Page()]), $navigation);
        $this->assertInstanceOf(IsAllowedFilter::class, $filter->getChildren());
    }

    /**
     * @throws Exception
     */
    public function testWillCreateFilter(): void
    {
        $iterator   = $this->createMock(RecursiveIterator::class);
        $navigation = $this->createMock(NavigationInterface::class);

        $filter = new IsAllowedFilter($iterator, $navigation);
        $this->assertInstanceOf(IsAllowedFilter::class, $filter);
    }
}
