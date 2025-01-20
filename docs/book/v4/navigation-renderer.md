# NavigationRenderer

Used to render the navigation container in a displayable format. It can render a simple HTML ul list or use a partial template, to render the menu in a template engine.

The partial method is usually the more flexible one, custom rules can be defined and checked in the template.

If you are using twig, there is already a twig extension provided in package dot-twigrenderer, that you can use to easily parse the menus inside your templates

When using the partial method, the template will receive as parameters the container, the navigation service and any extra parameters set by the developer.

Navigation containers are referred, when parsed, by their name, as defined in the configuration file.
