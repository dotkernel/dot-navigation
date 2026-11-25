# Installation

Install `dotkernel/dot-navigation` with this Composer command:

```shell
composer require dotkernel/dot-navigation
```

Add `Dot\Navigation\ConfigProvider` to the configuration of your application.

> The package uses `dotkernel/dot-helpers`. Also add `Dot\Helpers\ConfigProvider` to the configuration of your application, if it is not there.

Add `Dot\Navigation\NavigationMiddleware` to the middleware pipeline, after the routing middleware and before the dispatch middleware.
The middleware gives the route result and the query parameters of the request to the navigation service.

## Dependency injection container

The factories of the package use only `Psr\Container\ContainerInterface`.
They work with each PSR-11 container that accepts a factory for each service name, for example Laminas Service Manager.
The package does not require `laminas/laminas-servicemanager`. If your application uses it, require it in your application.
