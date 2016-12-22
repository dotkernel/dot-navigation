# dot-navigation

Allows you to easily define and parse menus inside templates, configuration based approach.

## Installation

Run
```bash
$ composer require dotkernel/dot-navigation
```

Merge `ConfigProvider` to your application's configuration.

Register `NavigationMiddleware` in your middleware pipe between the routing and the dispatching middleware.

## Configuration

In your `config/autoload` directory, create a config file

##### navigation.global.php
```php
return [
    'dot_navigation' => [

        //enable menu item active if any child is active
        'active_recursion' => true,

        //map a provider name to a provider type
        'providers_map' => [
            'default' => \Dot\Navigation\Provider\ArrayProvider::class,
        ],

        //map a provider name to its config
        'containers' => [
            'default' => [
                [
                    'options' => [
                        'label' => 'Menu #1',
                        'route' => 'home',
                    ],
                    'attributes' => [
                        'name' => 'Menu #1',
                    ]
                ],
                [
                    'options' => [
                        'label' => 'Menu #2',
                        'route' => 'home',
                    ],
                    'attributes' => [
                        'name' => 'Menu #1',
                    ]
                ]
            ]
        ],

        //register custom providers here
        'provider_manager' => [

        ]
    ],
];
```

## Components

A menu, or navigation container, is a class implementing the \RecursiveIterator interface. It has a hierarchical structure, with nodes called pages(see the `Page` class) that may have children. It is basically a tree

A Page extends the NavigationContainer class. The NavigationContainer is the top most node which represents the entire menu. The children of this node are Page instances that defines each navigation item.

A page has a reference to its parent, and can have options and attributes. There are no limitation on what is accepted as options or attributes.

Options can be any piece of information that describes a page. Some predefined options exists, in order for the navigation module to work seamlessly with other dot modules.

Attributes are key value pairs that defines the menu item. They are usually inserted as html attributes when parsing the menu, but of course, this is implementation specific.

A `NavigationService` class, is the service that handles all defined menu container. It can fetch the container from its provider, check if a page is active or not and get the page's generated URI.


## Container providers

Each menu can be created from different sources. The responsibility of creating a menu container from the source falls on a container provider.

Each provider must implement the interface `ProviderInterface` and be registered in the ProviderPluginManager.

We offer just one provider for now, `ArrayProvider`, that is able to fetch and create a menu container from a php array that is defined in the configuration file.

When configuring the module, you'll have to associate each defined navigation container to its provider, in the `provider_map` key.

## NavigationRenderer

Used to render the navigation container in a displayable format. It can render a simple HTML ul list or use a partial template, to render the menu in a template engine.

The partial method is usually the more flexible one, custom rules can be defined and checked in the template.

If you are using twig, there is already a twig extension provided in package dot-twigrenderer, that you can use to easily parse the menus inside your templates


When using the partial method, the template will receive as parameters the container, the navigation service and any extra parameters set by the developer.

Navigation containers are referred, when parsed, by their name, as defined in the configuration file.