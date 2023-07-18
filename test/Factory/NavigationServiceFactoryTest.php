<?php

declare(strict_types=1);

namespace DotTest\Navigation\Factory;

use Dot\Authorization\AuthorizationInterface;
use Dot\Helpers\Route\RouteHelper;
use Dot\Navigation\Factory\NavigationServiceFactory;
use Dot\Navigation\Options\NavigationOptions;
use Dot\Navigation\Provider\ProviderPluginManager;
use Dot\Navigation\Service\Navigation;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class NavigationServiceFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateNavigationServiceWithoutRouteHelper(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('has')
            ->with(RouteHelper::class)
            ->willReturn(false);

        $this->expectExceptionMessage(NavigationServiceFactory::MESSAGE_MISSING_ROUTE_HELPER);
        (new NavigationServiceFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateNavigationServiceWithoutProviderPluginManager(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->method('has')->willReturnMap([
            [RouteHelper::class, true],
            [ProviderPluginManager::class, false],
        ]);

        $this->expectExceptionMessage(NavigationServiceFactory::MESSAGE_MISSING_PLUGIN_MANAGER);
        (new NavigationServiceFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillNotCreateNavigationServiceWithoutNavigationOptions(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->method('has')->willReturnMap([
            [RouteHelper::class, true],
            [ProviderPluginManager::class, true],
            [NavigationOptions::class, false],
        ]);

        $this->expectExceptionMessage(NavigationServiceFactory::MESSAGE_MISSING_NAVIGATION_OPTIONS);
        (new NavigationServiceFactory())($container);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillCreateNavigationServiceWithoutAuthorizationInterface(): void
    {
        $container  = $this->createMock(ContainerInterface::class);
        $navigation = $this->createMock(RouteHelper::class);
        $renderer   = $this->createMock(TemplateRendererInterface::class);
        $options    = $this->createMock(NavigationOptions::class);

        $container->method('has')->willReturnMap([
            [RouteHelper::class, true],
            [ProviderPluginManager::class, true],
            [NavigationOptions::class, true],
            [AuthorizationInterface::class, false],
        ]);

        $container->method('get')->willReturnMap([
            [RouteHelper::class, $navigation],
            [TemplateRendererInterface::class, $renderer],
            [NavigationOptions::class, $options],
            [AuthorizationInterface::class, null],
        ]);

        $service = (new NavigationServiceFactory())($container);
        $this->assertInstanceOf(Navigation::class, $service);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillCreateNavigationServiceWithAuthorizationInterface(): void
    {
        $container     = $this->createMock(ContainerInterface::class);
        $navigation    = $this->createMock(RouteHelper::class);
        $renderer      = $this->createMock(TemplateRendererInterface::class);
        $options       = $this->createMock(NavigationOptions::class);
        $authorization = $this->createMock(AuthorizationInterface::class);

        $container->method('has')->willReturnMap([
            [RouteHelper::class, true],
            [ProviderPluginManager::class, true],
            [NavigationOptions::class, true],
            [AuthorizationInterface::class, true],
        ]);

        $container->method('get')->willReturnMap([
            [RouteHelper::class, $navigation],
            [TemplateRendererInterface::class, $renderer],
            [NavigationOptions::class, $options],
            [AuthorizationInterface::class, $authorization],
        ]);

        $service = (new NavigationServiceFactory())($container);
        $this->assertInstanceOf(Navigation::class, $service);
    }
}
