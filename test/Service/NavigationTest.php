<?php

declare(strict_types=1);

namespace DotTest\Navigation\Service;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Exception\InvalidArgumentException;
use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Provider\Factory;
use Dot\Navigation\Provider\FactoryInterface;
use Dot\Navigation\Service\Navigation;
use DotTest\Navigation\TestData\Container;
use Mezzio\Router\Route;
use Mezzio\Router\RouteResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;

class NavigationTest extends TestCase
{
    public function testAccessors(): void
    {
        $navigation  = $this->getNavigation();
        $routeResult = RouteResult::fromRouteFailure([]);

        $this->assertNull($navigation->getRouteResult());
        $navigation->setRouteResult($routeResult);
        $this->assertSame($routeResult, $navigation->getRouteResult());
        $this->assertTrue($navigation->getIsActiveRecursion());
        $navigation->setIsActiveRecursion(false);
        $this->assertFalse($navigation->getIsActiveRecursion());
    }

    public function testNavigationWillNotGetInvalidContainer(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Container `test` is not defined');
        $this->getNavigation()->getContainer('test');
    }

    public function testNavigationWillGetValidContainerOnce(): void
    {
        $navigation = new Navigation(
            new Factory(new Container()),
            $this->createStub(RouteHelper::class),
            new NavigationOptions([
                'containers' => [
                    'default' => [
                        'type'    => 'array',
                        'options' => ['items' => [['options' => ['label' => 'Home']]]],
                    ],
                ],
            ])
        );

        $container = $navigation->getContainer('default');
        $this->assertSame('Home', $container->current()->getLabel());
        $this->assertSame($container, $navigation->getContainer('default'));
    }

    public function testIsAllowedWillReturnTrueWithoutAuthorization(): void
    {
        $page = new Page();
        $page->setOption('permission', 'test');

        $this->assertTrue($this->getNavigation()->isAllowed($page));
    }

    public function testIsAllowedWillReturnTrueWhenPageHasNoPermission(): void
    {
        $authorization = $this->createMock(AuthorizationInterface::class);
        $authorization->expects($this->never())->method('isGranted');

        $this->assertTrue($this->getNavigation($authorization)->isAllowed(new Page()));
    }

    public function testIsAllowedWillCheckPermissionWithoutRoles(): void
    {
        $authorization = $this->createMock(AuthorizationInterface::class);
        $authorization->expects($this->once())->method('isGranted')->with('view')->willReturn(false);

        $page = new Page();
        $page->setOption('permission', 'view');
        $this->assertFalse($this->getNavigation($authorization)->isAllowed($page));
    }

    public function testIsAllowedWillCheckPermissionWithRoles(): void
    {
        $authorization = $this->createMock(AuthorizationInterface::class);
        $authorization->expects($this->once())->method('isGranted')->with('view', ['admin'])->willReturn(true);

        $page = new Page();
        $page->setOptions(['permission' => 'view', 'roles' => ['admin']]);
        $this->assertTrue($this->getNavigation($authorization)->isAllowed($page));
    }

    public function testIsAllowedWillAcceptRolesAsString(): void
    {
        $authorization = $this->createMock(AuthorizationInterface::class);
        $authorization->expects($this->once())->method('isGranted')->with('view', ['admin'])->willReturn(true);

        $page = new Page();
        $page->setOptions(['permission' => 'view', 'roles' => 'admin']);
        $this->assertTrue($this->getNavigation($authorization)->isAllowed($page));
    }

    public function testIsAllowedWillNotAcceptInvalidRoles(): void
    {
        $page = new Page();
        $page->setOptions(['permission' => 'view', 'roles' => 1]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The navigation page option "roles" must be a string or an array');
        $this->getNavigation($this->createStub(AuthorizationInterface::class))->isAllowed($page);
    }

    public function testIsActiveWillCacheResults(): void
    {
        $navigation = $this->getNavigation();
        $this->assertSame([], $navigation->getIsActiveCache());
        $navigation->isActive(new Page());
        $this->assertCount(1, $navigation->getIsActiveCache());
    }

    public function testIsActiveWillReturnFalseWithoutRouteResult(): void
    {
        $this->assertFalse($this->getNavigation()->isActive($this->getRoutePage('test')));
    }

    public function testIsActiveWillReturnFalseWithoutSuccessfulRouteResult(): void
    {
        $navigation = $this->getNavigation();
        $navigation->setRouteResult(RouteResult::fromRouteFailure([]));

        $this->assertFalse($navigation->isActive($this->getRoutePage('test')));
    }

    public function testIsActiveWillReturnFalseWhenPageHasNoRoute(): void
    {
        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('test'));

        $this->assertFalse($navigation->isActive(new Page()));
    }

    public function testIsActiveWillReturnTrueWhenRequestedRouteMatchesPageRoute(): void
    {
        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('test'));
        $navigation->setIsActiveRecursion(false);

        $this->assertTrue($navigation->isActive($this->getRoutePage('test')));
    }

    public function testIsActiveWillReturnTrueWhenRequestedRouteMatchesChildPageRoute(): void
    {
        $parentPage = $this->getRoutePage('parent');
        $parentPage->addPage($this->getRoutePage('child'));

        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('child'));

        $this->assertTrue($navigation->isActive($parentPage));
    }

    public function testIsActiveWillNotCheckChildPagesWithoutRecursion(): void
    {
        $parentPage = $this->getRoutePage('parent');
        $parentPage->addPage($this->getRoutePage('child'));

        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('child'));
        $navigation->setIsActiveRecursion(false);

        $this->assertFalse($navigation->isActive($parentPage));
    }

    public function testIsActiveComparesParamKeys(): void
    {
        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('list', ['page' => '1']));

        // Keep both pages in variables. The active cache uses spl_object_hash(), which PHP reuses for freed objects.
        $otherKey = $this->getRoutePage('list', ['id' => 1]);
        $sameKey  = $this->getRoutePage('list', ['page' => 1]);
        $this->assertFalse($navigation->isActive($otherKey));
        $this->assertTrue($navigation->isActive($sameKey));
    }

    public function testIsActiveRequiresEachRequestParamOnThePage(): void
    {
        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('list', ['id' => '1']));

        $this->assertFalse($navigation->isActive($this->getRoutePage('list')));
    }

    public function testIsActiveSkipsIgnoredParams(): void
    {
        $page = new Page();
        $page->setOption('route', ['route_name' => 'list', 'ignore_params' => ['id']]);

        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('list', ['id' => '1']));

        $this->assertTrue($navigation->isActive($page));
    }

    public function testIsActiveUsesQueryParamsAndNotGlobalGet(): void
    {
        $_GET['tab'] = 'other';

        $page = new Page();
        $page->setOption('route', ['route_name' => 'list', 'query_params' => ['tab' => 'main']]);

        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('list'));
        $navigation->setQueryParams(['tab' => 'main']);
        $this->assertTrue($navigation->isActive($page));

        $navigation->setQueryParams(['tab' => 'other']);
        $this->assertFalse($navigation->isActive($page));

        unset($_GET['tab']);
    }

    public function testIsActiveAcceptsArrayQueryValue(): void
    {
        $page = new Page();
        $page->setOption('route', ['route_name' => 'list', 'query_params' => ['tags' => ['a', 'b']]]);

        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('list'));
        $navigation->setQueryParams(['tags' => ['a', 'b']]);

        $this->assertTrue($navigation->isActive($page));
    }

    public function testIsActiveCacheIsClearedByNewRouteResult(): void
    {
        $page       = $this->getRoutePage('first');
        $navigation = $this->getNavigation();

        $navigation->setRouteResult($this->getRouteResult('first'));
        $this->assertTrue($navigation->isActive($page));

        $navigation->setRouteResult($this->getRouteResult('second'));
        $this->assertFalse($navigation->isActive($page));
    }

    public function testParentWithoutRouteIsActiveWhenChildIsActive(): void
    {
        $parentPage = new Page();
        $parentPage->setOption('uri', '#');
        $parentPage->addPage($this->getRoutePage('child'));

        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('child'));

        $this->assertTrue($navigation->isActive($parentPage));
    }

    public function testRouteOptionWithoutRouteNameIsNotActive(): void
    {
        $page = new Page();
        $page->setOption('route', ['route_params' => []]);

        $navigation = $this->getNavigation();
        $navigation->setRouteResult($this->getRouteResult('test'));

        $this->assertFalse($navigation->isActive($page));
    }

    public function testGetHrefWillCacheResults(): void
    {
        $navigation = $this->getNavigation();
        $this->assertSame([], $navigation->getHrefCache());

        $page = new Page();
        $page->setOption('uri', 'page1');
        $navigation->getHref($page);
        $this->assertCount(1, $navigation->getHrefCache());

        $navigation->getHref($this->getRoutePage('page2'));
        $this->assertCount(2, $navigation->getHrefCache());
    }

    public function testWillGetHrefForValidPage(): void
    {
        $navigation = $this->getNavigation();

        $page = new Page();
        $page->setOption('uri', 'page1');
        $this->assertSame('page1', $navigation->getHref($page));

        $this->assertSame('/generated', $navigation->getHref($this->getRoutePage('page2')));
    }

    public function testWillGetHrefForUriZero(): void
    {
        $page = new Page();
        $page->setOption('uri', '0');

        $this->assertSame('0', $this->getNavigation()->getHref($page));
    }

    public function testWillNotGetHrefForInvalidPage(): void
    {
        $page = new Page();
        $page->setOption('name', 7);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to assemble href for navigation page "7"');
        $this->getNavigation()->getHref($page);
    }

    private function getNavigation(?AuthorizationInterface $authorization = null): Navigation
    {
        $uri = $this->createStub(UriInterface::class);
        $uri->method('__toString')->willReturn('/generated');

        $routeHelper = $this->createStub(RouteHelper::class);
        $routeHelper->method('generateUri')->willReturn($uri);

        return new Navigation(
            $this->createStub(FactoryInterface::class),
            $routeHelper,
            new NavigationOptions(),
            $authorization
        );
    }

    /**
     * @param array<string, mixed> $routeParams
     */
    private function getRoutePage(string $routeName, array $routeParams = []): Page
    {
        $page = new Page();
        $page->setOption('route', ['route_name' => $routeName, 'route_params' => $routeParams]);

        return $page;
    }

    /**
     * @param array<string, mixed> $params
     */
    private function getRouteResult(string $routeName, array $params = []): RouteResult
    {
        return RouteResult::fromRoute(
            new Route('/path', $this->createStub(MiddlewareInterface::class), Route::HTTP_METHOD_ANY, $routeName),
            $params
        );
    }
}
