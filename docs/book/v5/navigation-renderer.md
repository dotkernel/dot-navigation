# Navigation renderer

`Dot\Navigation\View\NavigationRenderer` renders a navigation container with a template. Get it from the container with `Dot\Navigation\View\RendererInterface`.

- `render(string|NavigationContainer $container, string $template, array $params = [])` renders the container with a template.
- `renderPartial(string|NavigationContainer $container, string $partial, array $params = [])` does the same with a partial template.

The first argument is the name of a container in the configuration, or a container object.
The template gets these parameters: `container`, `navigation` (the navigation service) and the keys of `$params`.
An empty template name throws `Dot\Navigation\Exception\InvalidArgumentException`.

If you use Twig, the package `dotkernel/dot-twigrenderer` has a Twig extension for menus.
Its functions include `navigation()`, `navigationPartial()` and `pageAttributes()`.

## Page attributes

`htmlAttributes(array $attributes)` writes the attributes of a page as HTML attributes. The Twig function `pageAttributes(page)` calls it.

- Each value must be a string or a list of strings. A list is joined with spaces, for example `'class' => ['nav-link', 'active']`.
- An int, float, bool or `null` value throws a `TypeError`. Write the value as a string, for example `'tabindex' => '0'` or `'aria-expanded' => 'false'`.
- For a boolean attribute, write the name as the value, for example `'hidden' => 'hidden'`. To leave the attribute out, do not add the key.
- For a key that starts with `on` and for `constraints`, an array is encoded as JSON.
- The method escapes each value. Twig does not escape the output again. Use only valid attribute names as keys, because the method does not check them.
- The method does not change an `id` value. Do not use `[` or `]` in an `id`.

```php
$renderer->htmlAttributes(['class' => ['nav-link', 'active'], 'href' => '#']);
// ' class="nav-link&#x20;active" href="&#x23;"'
```

The browser decodes the escaped characters, so the class is `nav-link active` and the link is `#`.

## Show only the allowed pages

`Dot\Navigation\Filter\IsAllowedFilter` skips each page that the user is not allowed to see, and its sub-pages.
It uses `NavigationInterface::isAllowed()`.

```php
use Dot\Navigation\Filter\IsAllowedFilter;

$visible = new RecursiveIteratorIterator(
    new IsAllowedFilter($navigation->getContainer('default'), $navigation),
    RecursiveIteratorIterator::SELF_FIRST
);
foreach ($visible as $page) {
    echo $page->getLabel();
}
```
