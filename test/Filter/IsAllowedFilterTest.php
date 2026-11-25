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
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use RecursiveIterator;

class IsAllowedFilterTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[AllowMockObjectsWithoutExpectations]
    public function testWillAcceptWithoutAuthorization(): void
    {
        $iterator = $this->createStub(RecursiveIterator::class);
        $factory  = $this->createStub(FactoryInterface::class);
        $route    = $this->createStub(RouteHelper::class);
        $options  = $this->createStub(NavigationOptions::class);

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
    #[AllowMockObjectsWithoutExpectations]
    public function testWillAcceptWithAuthorization(): void
    {
        $iterator      = $this->createStub(RecursiveIterator::class);
        $factory       = $this->createStub(FactoryInterface::class);
        $route         = $this->createStub(RouteHelper::class);
        $options       = $this->createStub(NavigationOptions::class);
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
        $navigation = $this->createStub(NavigationInterface::class);

        $filter = new IsAllowedFilter(new NavigationContainer([new Page()]), $navigation);
        $this->assertSame(IsAllowedFilter::class, $filter->getChildren()::class);
    }

    /**
     * @throws Exception
     */
    public function testWillCreateFilter(): void
    {
        $iterator   = $this->createStub(RecursiveIterator::class);
        $navigation = $this->createStub(NavigationInterface::class);

        $filter = new IsAllowedFilter($iterator, $navigation);
        $this->assertSame(IsAllowedFilter::class, $filter::class);
    }
}
