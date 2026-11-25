# Components

## Navigation container

A menu, or navigation container, is an instance of `Dot\Navigation\NavigationContainer`.
The container implements `RecursiveIterator`. It is a tree, and its nodes are pages (`Dot\Navigation\Page`).

`Page` extends `NavigationContainer`, so a page can have sub-pages.
The container is the top node, and it represents the full menu.

Use these methods to find the sub-pages:

- `hasPages()` tells if the container or the page has sub-pages.
- `hasChildren()` gives the same result as `hasPages()`. Templates use it to show a sub-menu.
- `current()` returns the page at the current position. On an empty container, or after the last page, it throws `Dot\Navigation\Exception\RuntimeException`.
- `findOneByOption()`, `findByOption()`, `findOneByAttribute()` and `findByAttribute()` search all pages and sub-pages.

`hasChildren()` does not follow the `RecursiveIterator` contract. It tells if the container has pages, not if the current page has sub-pages.
Because of this, `RecursiveIteratorIterator::LEAVES_ONLY` gives no pages. Use `RecursiveIteratorIterator::SELF_FIRST` or `RecursiveIteratorIterator::CHILD_FIRST`.

```php
$iterator = new RecursiveIteratorIterator($container, RecursiveIteratorIterator::SELF_FIRST);
foreach ($iterator as $page) {
    echo $page->getLabel();
}
```

## Page

A page has a parent, options and attributes.

- Options describe the page, for example `label`, `uri`, `route` and `permission`. See [Page options](required-page-options.md).
- Attributes are key-value pairs. A template usually writes them as HTML attributes. See [Page attributes](navigation-renderer.md#page-attributes).

## Navigation service

The navigation service is `Dot\Navigation\Service\Navigation`. Get it from the container with `Dot\Navigation\Service\NavigationInterface`.
It loads the menu containers from their providers. It also tells if a page is allowed or active, and it makes the URI of a page.
