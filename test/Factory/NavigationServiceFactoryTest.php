<?php

declare(strict_types=1);

namespace DotTest\Navigation\Factory;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Exception\RuntimeException;
use Dot\Navigation\Factory\NavigationServiceFactory;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Page;
use Dot\Navigation\Provider\ProviderPluginManager;
use Dot\Navigation\Service\Navigation;
use DotTest\Navigation\TestData\Container;
use PHPUnit\Framework\TestCase;

class NavigationServiceFactoryTest extends TestCase
{
    public function testWillNotCreateNavigationServiceWithoutRouteHelper(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationServiceFactory::MESSAGE_MISSING_ROUTE_HELPER);
        (new NavigationServiceFactory())(new Container());
    }

    public function testWillNotCreateNavigationServiceWithoutProviderPluginManager(): void
    {
        $container = new Container([
            RouteHelper::class => $this->createStub(RouteHelper::class),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationServiceFactory::MESSAGE_MISSING_PLUGIN_MANAGER);
        (new NavigationServiceFactory())($container);
    }

    public function testWillNotCreateNavigationServiceWithoutNavigationOptions(): void
    {
        $container = new Container([
            RouteHelper::class           => $this->createStub(RouteHelper::class),
            ProviderPluginManager::class => new ProviderPluginManager(new Container()),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(NavigationServiceFactory::MESSAGE_MISSING_NAVIGATION_OPTIONS);
        (new NavigationServiceFactory())($container);
    }

    public function testWillCreateNavigationServiceWithoutAuthorizationInterface(): void
    {
        $container = new Container([
            RouteHelper::class           => $this->createStub(RouteHelper::class),
            ProviderPluginManager::class => new ProviderPluginManager(new Container()),
            NavigationOptions::class     => new NavigationOptions(['active_recursion' => false]),
        ]);

        $service = (new NavigationServiceFactory())($container);
        $this->assertSame(Navigation::class, $service::class);
        $this->assertFalse($service->getIsActiveRecursion());

        $page = new Page();
        $page->setOption('permission', 'test');
        $this->assertTrue($service->isAllowed($page));
    }

    public function testWillCreateNavigationServiceWithAuthorizationInterface(): void
    {
        $authorization = $this->createStub(AuthorizationInterface::class);
        $authorization->method('isGranted')->willReturn(false);

        $container = new Container([
            RouteHelper::class            => $this->createStub(RouteHelper::class),
            ProviderPluginManager::class  => new ProviderPluginManager(new Container()),
            NavigationOptions::class      => new NavigationOptions(),
            AuthorizationInterface::class => $authorization,
        ]);

        $service = (new NavigationServiceFactory())($container);
        $this->assertSame(Navigation::class, $service::class);

        $page = new Page();
        $page->setOption('permission', 'test');
        $this->assertFalse($service->isAllowed($page));
    }
}
