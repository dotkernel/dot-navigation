<?php

declare(strict_types=1);

namespace DotTest\Navigation\Service;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\NavigationContainer;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Provider\ArrayProvider;
use Dot\Navigation\Provider\FactoryInterface;
use Dot\Navigation\Service\Navigation;
use Dot\Navigation\Service\NavigationInterface;
use Mezzio\Router\RouteResult;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class NavigationTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testNavigationWillInitialize(): void
    {
        $factory = $this->createMock(FactoryInterface::class);
        $route   = $this->createMock(RouteHelper::class);
        $options = $this->createMock(NavigationOptions::class);

        $navigation = new Navigation($factory, $route, $options);
        $this->assertInstanceOf(NavigationInterface::class, $navigation);
    }

    /**
     * @throws Exception
     */
    public function testAccessors(): void
    {
        $factory     = $this->createMock(FactoryInterface::class);
        $route       = $this->createMock(RouteHelper::class);
        $options     = $this->createMock(NavigationOptions::class);
        $routeResult = $this->createMock(RouteResult::class);

        $navigation = new Navigation($factory, $route, $options);
        $this->assertNull($navigation->getRouteResult());
        $navigation->setRouteResult($routeResult);
        $this->assertInstanceOf(RouteResult::class, $navigation->getRouteResult());
        $this->assertTrue($navigation->getIsActiveRecursion());
        $navigation->setIsActiveRecursion(false);
        $this->assertFalse($navigation->getIsActiveRecursion());
    }

    /**
     * @throws Exception
     */
    public function testNavigationWillNotGetInvalidContainer(): void
    {
        $factory = $this->createMock(FactoryInterface::class);
        $route   = $this->createMock(RouteHelper::class);
        $options = $this->createMock(NavigationOptions::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Container `test` is not defined');
        $navigation = new Navigation($factory, $route, $options);
        $navigation->getContainer('test');
    }

    /**
     * @throws Exception
     */
    public function testNavigationWillGetValidContainer(): void
    {
        $factory = $this->createMock(FactoryInterface::class);
        $route   = $this->createMock(RouteHelper::class);

        $options = new NavigationOptions([
            'containers' => [
                'default' => [
                    'type' => ArrayProvider::class,
                ],
            ],
        ]);

        $navigation = new Navigation($factory, $route, $options);
        $this->assertInstanceOf(NavigationContainer::class, $navigation->getContainer('default'));
    }

    /**
     * @throws Exception
     */
    public function testIsAllowedWillReturnTrueWithoutAuthorization(): void
    {
        $factory = $this->createMock(FactoryInterface::class);
        $route   = $this->createMock(RouteHelper::class);
        $options = $this->createMock(NavigationOptions::class);

        $navigation = new Navigation($factory, $route, $options);
        $this->assertTrue($navigation->isAllowed(new Page()));
    }

    /**
     * @throws Exception
     */
    public function testIsAllowedWillReturnTrueWithAuthorizationWhenPageHasNoPermission(): void
    {
        $factory       = $this->createMock(FactoryInterface::class);
        $route         = $this->createMock(RouteHelper::class);
        $options       = $this->createMock(NavigationOptions::class);
        $authorization = $this->createMock(AuthorizationInterface::class);

        $navigation = new Navigation($factory, $route, $options, $authorization);
        $this->assertTrue($navigation->isAllowed(new Page()));
    }

    /**
     * @throws Exception
     */
    public function testIsAllowedWillReturnTrueWithAuthorizationWhenPageHasNoRoles(): void
    {
        $factory       = $this->createMock(FactoryInterface::class);
        $route         = $this->createMock(RouteHelper::class);
        $options       = $this->createMock(NavigationOptions::class);
        $authorization = $this->createMock(AuthorizationInterface::class);

        $authorization->expects($this->once())->method('isGranted')->willReturn(true);

        $page = new Page();
        $page->setOption('permission', '');
        $navigation = new Navigation($factory, $route, $options, $authorization);
        $this->assertTrue($navigation->isAllowed($page));
    }

    /**
     * @throws Exception
     */
    public function testIsAllowedWillReturnTrueWithAuthorizationWhenPageHasPermissionsAndRoles(): void
    {
        $factory       = $this->createMock(FactoryInterface::class);
        $route         = $this->createMock(RouteHelper::class);
        $options       = $this->createMock(NavigationOptions::class);
        $authorization = $this->createMock(AuthorizationInterface::class);

        $authorization->expects($this->once())->method('isGranted')->willReturn(true);

        $page = new Page();
        $page->setOption('permission', '');
        $page->setOption('roles', []);
        $navigation = new Navigation($factory, $route, $options, $authorization);
        $this->assertTrue($navigation->isAllowed($page));
    }

    /**
     * @throws Exception
     */
    public function testIsActiveWillCacheResults(): void
    {
        $factory = $this->createMock(FactoryInterface::class);
        $route   = $this->createMock(RouteHelper::class);
        $options = $this->createMock(NavigationOptions::class);

        $navigation = new Navigation($factory, $route, $options);
        $this->assertIsArray($navigation->getIsActiveCache());
        $this->assertEmpty($navigation->getIsActiveCache());
        $navigation->isActive(new Page());
        $this->assertIsArray($navigation->getIsActiveCache());
        $this->assertCount(1, $navigation->getIsActiveCache());
    }

    /**
     * @throws Exception
     */
    public function testIsActiveWillReturnFalseWithoutRouteResult(): void
    {
        $factory = $this->createMock(FactoryInterface::class);
        $route   = $this->createMock(RouteHelper::class);
        $options = $this->createMock(NavigationOptions::class);

        $navigation = new Navigation($factory, $route, $options);
        $this->assertFalse($navigation->isActive(new Page()));
    }

    /**
     * @throws Exception
     */
    public function testIsActiveWillReturnFalseWithoutSuccessfulRouteResult(): void
    {
        $factory     = $this->createMock(FactoryInterface::class);
        $route       = $this->createMock(RouteHelper::class);
        $options     = $this->createMock(NavigationOptions::class);
        $routeResult = $this->createMock(RouteResult::class);

        $routeResult->expects($this->once())->method('isSuccess')->willReturn(false);

        $navigation = new Navigation($factory, $route, $options);
        $navigation->setRouteResult($routeResult);
        $this->assertFalse($navigation->isActive(new Page()));
    }

    /**
     * @throws Exception
     */
    public function testIsActiveWillReturnFalseWhenPageHasNoRoute(): void
    {
        $factory     = $this->createMock(FactoryInterface::class);
        $route       = $this->createMock(RouteHelper::class);
        $options     = $this->createMock(NavigationOptions::class);
        $routeResult = $this->createMock(RouteResult::class);

        $routeResult->expects($this->once())->method('isSuccess')->willReturn(true);

        $navigation = new Navigation($factory, $route, $options);
        $navigation->setRouteResult($routeResult);
        $this->assertFalse($navigation->isActive(new Page()));
    }

    /**
     * @throws Exception
     */
    public function testIsActiveWillReturnTrueWhenRequestedRouteMatchesPageRoute(): void
    {
        $factory     = $this->createMock(FactoryInterface::class);
        $route       = $this->createMock(RouteHelper::class);
        $options     = $this->createMock(NavigationOptions::class);
        $routeResult = $this->createMock(RouteResult::class);

        $routeResult->expects($this->once())->method('isSuccess')->willReturn(true);
        $routeResult->expects($this->once())->method('getMatchedRouteName')->willReturn('test');

        $page = new Page();
        $page->setOption('route', [
            'route_name' => 'test',
        ]);
        $navigation = new Navigation($factory, $route, $options);
        $navigation->setRouteResult($routeResult);
        $navigation->setIsActiveRecursion(false);
        $this->assertTrue($navigation->isActive($page));
    }

    /**
     * @throws Exception
     */
    public function testIsActiveWillReturnTrueWhenRequestedRouteMatchesChildPageRoute(): void
    {
        $factory     = $this->createMock(FactoryInterface::class);
        $route       = $this->createMock(RouteHelper::class);
        $options     = $this->createMock(NavigationOptions::class);
        $routeResult = $this->createMock(RouteResult::class);

        $routeResult->expects($this->any())->method('isSuccess')->willReturn(true);
        $routeResult->expects($this->any())->method('getMatchedRouteName')->willReturn('child');

        $childPage = new Page();
        $childPage->setOption('route', [
            'route_name' => 'child',
        ]);
        $parentPage = new Page();
        $parentPage->setOption('route', [
            'route_name' => 'parent',
        ]);
        $parentPage->addPage($childPage);
        $navigation = new Navigation($factory, $route, $options);
        $navigation->setRouteResult($routeResult);
        $navigation->setIsActiveRecursion(true);
        $this->assertTrue($navigation->isActive($parentPage));
    }

    /**
     * @throws Exception
     */
    public function testGetHrefWillCacheResults(): void
    {
        $factory = $this->createMock(FactoryInterface::class);
        $route   = $this->createMock(RouteHelper::class);
        $options = $this->createMock(NavigationOptions::class);

        $navigation = new Navigation($factory, $route, $options);
        $this->assertIsArray($navigation->getHrefCache());
        $this->assertEmpty($navigation->getHrefCache());

        $page = new Page();
        $page->setOption('uri', 'page1');
        $navigation->getHref($page);
        $this->assertIsArray($navigation->getHrefCache());
        $this->assertCount(1, $navigation->getHrefCache());

        $page = new Page();
        $page->setOption('route', [
            'route_name' => 'page2',
        ]);
        $navigation->getHref($page);
        $this->assertIsArray($navigation->getHrefCache());
        $this->assertCount(2, $navigation->getHrefCache());
    }

    /**
     * @throws Exception
     */
    public function testWillNotGetHrefForInvalidPage(): void
    {
        $factory = $this->createMock(FactoryInterface::class);
        $route   = $this->createMock(RouteHelper::class);
        $options = $this->createMock(NavigationOptions::class);

        $navigation = new Navigation($factory, $route, $options);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/^Unable to assemble href for navigation page.*/');
        $page = new Page();
        $navigation->getHref($page);
    }

    /**
     * @throws Exception
     */
    public function testWillGetHrefForValidPage(): void
    {
        $factory = $this->createMock(FactoryInterface::class);
        $route   = $this->createMock(RouteHelper::class);
        $options = $this->createMock(NavigationOptions::class);

        $navigation = new Navigation($factory, $route, $options);

        $page = new Page();
        $page->setOption('uri', 'page1');
        $this->assertSame('page1', $navigation->getHref($page));

        $page = new Page();
        $page->setOption('route', [
            'route_name' => 'page2',
        ]);
        $this->assertIsString($navigation->getHref($page));
    }
}
