# dot-navigation

`dot-navigation` is Dotkernel's component that allows you to easily define and parse menus inside templates, using a configuration-based approach.

## Documentation

Documentation is available at: https://docs.dotkernel.org/dot-navigation/.

## Badges

![OSS Lifecycle](https://img.shields.io/osslifecycle/dotkernel/dot-navigation)
![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-navigation/5.0.0)

[![GitHub issues](https://img.shields.io/github/issues/dotkernel/dot-navigation)](https://github.com/dotkernel/dot-navigation/issues)
[![GitHub forks](https://img.shields.io/github/forks/dotkernel/dot-navigation)](https://github.com/dotkernel/dot-navigation/network)
[![GitHub stars](https://img.shields.io/github/stars/dotkernel/dot-navigation)](https://github.com/dotkernel/dot-navigation/stargazers)
[![GitHub license](https://img.shields.io/github/license/dotkernel/dot-navigation)](https://github.com/dotkernel/dot-navigation/blob/5.0/LICENSE.md)

[![Build Static](https://github.com/dotkernel/dot-navigation/actions/workflows/continuous-integration.yml/badge.svg?branch=5.0)](https://github.com/dotkernel/dot-navigation/actions/workflows/continuous-integration.yml)
[![codecov](https://codecov.io/gh/dotkernel/dot-navigation/graph/badge.svg?token=AI9WFYDDX9)](https://codecov.io/gh/dotkernel/dot-navigation)
[![PHPStan](https://github.com/dotkernel/dot-navigation/actions/workflows/static-analysis.yml/badge.svg?branch=5.0)](https://github.com/dotkernel/dot-navigation/actions/workflows/static-analysis.yml)

## Installation

Install `dotkernel/dot-navigation` with this Composer command:

```shell
composer require dotkernel/dot-navigation
```

Add `Dot\Navigation\ConfigProvider` to the configuration of your application.

> The package uses dot-helpers. Also add the `ConfigProvider` of dot-helpers to the configuration of your application, if it is not there.

Add `NavigationMiddleware` to the middleware pipeline, after the routing middleware and before the dispatch middleware.

The factories use only `Psr\Container\ContainerInterface`, so they work with each PSR-11 container that accepts a factory for each service name.
The package does not require `laminas/laminas-servicemanager`. If your application uses it, require it in your application.

## Configuration

Copy the configuration file `vendor/dotkernel/dot-navigation/config/autoload/navigation.global.php.dist` to `config/autoload/navigation.global.php` in your project.

## Components

A menu, or navigation container, is an instance of `NavigationContainer`. It implements `RecursiveIterator`.
It is a tree, and its nodes are pages (see the `Page` class). A page can have sub-pages.

`Page` extends `NavigationContainer`. The container is the top node, and it represents the full menu.
`hasPages()` and `hasChildren()` tell if a container or a page has sub-pages.

A page has a parent, options and attributes.
Options describe the page, for example its label, link and permission.
Attributes are key-value pairs. A template usually writes them as HTML attributes.

The `Navigation` service (`Dot\Navigation\Service\Navigation`) handles all menu containers.
It loads a container from its provider. It tells if a page is allowed or active, and it makes the URI of a page.

## Container providers

A container provider makes a menu container from a source. Each provider implements `ProviderInterface`.

The package has one provider, `ArrayProvider`. It makes a menu container from a PHP array in the configuration file.

To add a provider, register its factory in the `provider_manager` configuration key. This key supports `factories` and `aliases`.
For a provider that needs no services, use `Dot\Navigation\Provider\InvokableProviderFactory`.

## NavigationRenderer

The renderer renders a navigation container with a template.
`render()` and `renderPartial()` give the template the container, the navigation service and the extra parameters.

If you use Twig, the package dot-twigrenderer has a Twig extension for menus.

Templates refer to a navigation container by its name in the configuration file.

`htmlAttributes()` writes the attributes of a page as HTML attributes. Each value must be a string or a list of strings.
Other value types throw a `TypeError`. Write a number or a boolean as a string, for example `'tabindex' => '0'`.

## Page options

Each page can have these options:

- `label` - the text of the menu item
- `route` or `uri` - the route or the link of the menu item
- `permission` - optional. If an authorization service is in the container, the page shows only when the user has this permission
