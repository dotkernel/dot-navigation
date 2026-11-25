# Usage

This is an example of `config/autoload/navigation.global.php`.
Change the menu structure here.

```php
<?php

return [
    'dot_navigation' => [
        //enable menu item active if any child is active
        'active_recursion' => true,

        //map a provider name to its config
        'containers' => [
            'default' => [
                'type' => 'ArrayProvider',
                'options' => [
                    'items' => [
                        [
                            'options' => [
                                'label' => 'Menu #1',
                                'route' => [
                                    'route_name' => 'home',
                                    'route_params' => [],
                                    'query_params' => [],
                                    'fragment_id' => null,
                                    'options' => [],

                                    //the below parameters are not used in route generation
                                    //they are used in finding if a page is active by omitting some parameters from the check
                                    'ignore_params' => []
                                ],
                            ],
                            'attributes' => [
                                'name' => 'Menu #1',
                            ]
                        ],
                        [
                            'options' => [
                                'label' => 'Menu #2',
                                'route' => ['route_name' => 'home'],
                            ],
                            'attributes' => [
                                'name' => 'Menu #2',
                            ]
                        ]
                    ],
                ],
            ],
        ],

        //register custom providers here
        'provider_manager' => [],
    ],
];
```

## Active pages

`NavigationInterface::isActive($page)` tells if a page is active for the current request.
`NavigationMiddleware` gives the route result and the query parameters of the request to the navigation service.

A page with a `route` option is active when all of these conditions are true:

- The `route_name` of the page is the name of the matched route.
- Each request parameter is also a page parameter, with the same name and the same value. The request parameters are the matched route parameters and the query parameters. The page parameters are `route_params` and `query_params`.
- The comparison ignores the request parameters in `ignore_params`.

The values `1` and `'1'` are equal. For example, a page with `'route_params' => ['id' => 1]` is not active for the request `page=1`, because the names are different.
A page without parameters is not active for `/list/1`, because the request has the parameter `id`. Add `'ignore_params' => ['id']` to make it active.

If `active_recursion` is `true`, a page is also active when one of its sub-pages is active. This is also true for a parent page that has a `uri` and no `route`.
