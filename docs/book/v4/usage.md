# Usage

Below is an example with content of `config/autoload/navigation.global.php`.

Here we can change or modify the menu structure that will be rendered in the application we are building.

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
                                'name' => 'Menu #1',
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
