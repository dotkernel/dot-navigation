# Upgrading

## Upgrade from 4.x to 5.0

| Change | Symptom | Fix |
| --- | --- | --- |
| PHP 8.2 support removed | Composer cannot install 5.x on PHP 8.2 | use PHP 8.3 or higher, or stay on 4.x |
| `laminas/laminas-servicemanager` is not required | a class from Laminas Service Manager is not found | require `laminas/laminas-servicemanager` in your application if you use it |
| `ProviderPluginManager` does not extend `AbstractPluginManager` | `Call to undefined method ...ProviderPluginManager::get()` | call `build()` |
| `provider_manager` supports only `factories` and `aliases` | a provider from `invokables` is not found | register it in `factories` with `InvokableProviderFactory::class` |
| package exceptions in the provider manager | a `catch` for a Laminas exception does not run | catch `Dot\Navigation\Exception\ExceptionInterface` |
| factories throw `Dot\Navigation\Exception\RuntimeException` | a check for the exact class `Exception` fails | catch `ExceptionInterface` or `\RuntimeException` |
| `NavigationInterface::setQueryParams()` added | a custom implementation is a fatal error | add the method |
| `isActive()` uses the query of the request, not `$_GET` | a page with `query_params` is not active when the application does not use `NavigationMiddleware` | add `NavigationMiddleware` to the pipeline, or call `setQueryParams()` |
| `IsAllowedFilter` is `final` | a subclass is a fatal error | wrap the filter, do not extend it |
| `NavigationContainer::current()` and `getChildren()` return `Page`, and `current()` throws at an invalid position | a subclass that returns `NavigationContainer` is a fatal error | change the return type |
| `areParamsEqual()` compares keys | a page that was active through a value match is not active now | set the correct `route_params` or `ignore_params` |
| `render()` and `renderPartial()` reject an empty template name | `InvalidArgumentException` | give a template name |
| invalid page config throws package exceptions | `InvalidArgumentException` for a page key that is not an array, or for `roles` that are not a string or an array | correct the config |

## Replace the Laminas plugin manager

Before, in 4.x:

```php
'provider_manager' => [
    'invokables' => [
        MyProvider::class => MyProvider::class,
    ],
],
```

After, in 5.0:

```php
'provider_manager' => [
    'factories' => [
        MyProvider::class => Dot\Navigation\Provider\InvokableProviderFactory::class,
    ],
],
```

A factory class or a callable with the signature `__invoke(ContainerInterface $container, string $requestedName, ?array $options = null)` works in both versions.
