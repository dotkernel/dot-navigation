# Container providers

A container provider makes a menu container from a source. Each provider implements `Dot\Navigation\Provider\ProviderInterface`.

The package has one provider, `Dot\Navigation\Provider\ArrayProvider`. It makes a menu container from a PHP array in the configuration.
These names select it in the `type` key of a container: `Dot\Navigation\Provider\ArrayProvider`, `ArrayProvider`, `arrayProvider`, `arrayprovider`, `Array` and `array`.

`ArrayProvider` throws `Dot\Navigation\Exception\InvalidArgumentException` when the `attributes`, `options` or `pages` key of a page is not an array.

## Register a custom provider

`Dot\Navigation\Provider\ProviderPluginManager` makes the providers. Configure it in the `provider_manager` key.
It reads only two keys:

- `factories`: a map of a provider name to a factory. The factory is a class name or a callable.
- `aliases`: a map of an alias to a provider name.

A factory gets the PSR-11 container, the provider name and the `options` of the container config:

```php
use Dot\Navigation\Provider\ProviderInterface;
use Psr\Container\ContainerInterface;

class MyProviderFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): ProviderInterface
    {
        return new MyProvider($container->get(MyRepository::class), $options ?? []);
    }
}
```

Register the factory and an alias in the configuration:

```php
return [
    'dot_navigation' => [
        'containers'       => [
            'main' => [
                'type'    => 'my',
                'options' => [],
            ],
        ],
        'provider_manager' => [
            'factories' => [
                MyProvider::class => MyProviderFactory::class,
            ],
            'aliases'   => [
                'my' => MyProvider::class,
            ],
        ],
    ],
];
```

For a provider that needs no services, use `Dot\Navigation\Provider\InvokableProviderFactory`.
It calls `new $requestedName($options)`, or `new $requestedName()` when there are no options.

```php
'factories' => [
    MySimpleProvider::class => Dot\Navigation\Provider\InvokableProviderFactory::class,
],
```

The plugin manager makes a new provider each time that the navigation service loads a container.
An unknown provider name, or a factory that does not return a `ProviderInterface`, throws `Dot\Navigation\Exception\RuntimeException`.
