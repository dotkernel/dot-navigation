# dot-navigation

![OSS Lifecycle](https://img.shields.io/osslifecycle/dotkernel/dot-navigation)
![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-navigation/3.4.0)

[![GitHub issues](https://img.shields.io/github/issues/dotkernel/dot-navigation)](https://github.com/dotkernel/dot-navigation/issues)
[![GitHub forks](https://img.shields.io/github/forks/dotkernel/dot-navigation)](https://github.com/dotkernel/dot-navigation/network)
[![GitHub stars](https://img.shields.io/github/stars/dotkernel/dot-navigation)](https://github.com/dotkernel/dot-navigation/stargazers)
[![GitHub license](https://img.shields.io/github/license/dotkernel/dot-navigation)](https://github.com/dotkernel/dot-navigation/blob/3.0/LICENSE.md)

[![SymfonyInsight](https://insight.symfony.com/projects/68b7c728-4cc9-40ac-a3be-cf17f9b2eaf1/big.svg)](https://insight.symfony.com/projects/68b7c728-4cc9-40ac-a3be-cf17f9b2eaf1)


Allows you to easily define and parse menus inside templates, configuration based approach.

## Installation

Run

    composer require dotkernel/dot-navigation

Merge `ConfigProvider` to your application's configuration.

The package uses dot-helpers package, please merge dot-helpers `ConfigProvider` to your application's configuration
also, if it's not merged already!

Register `NavigationMiddleware` in your middleware pipe between the routing and the dispatching middleware.

## Configuration

Locate dot-navigation's distributable config file `vendor/dotkernel/dot-navigation/config/autoload/navigation.global.php.dist` and duplicate it in your project as `config/autoload/navigation.global.php`


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


## NavigationRenderer

Used to render the navigation container in a displayable format. It can render a simple HTML ul list or use a partial template, to render the menu in a template engine.

The partial method is usually the more flexible one, custom rules can be defined and checked in the template.

If you are using twig, there is already a twig extension provided in package dot-twigrenderer, that you can use to easily parse the menus inside your templates


When using the partial method, the template will receive as parameters the container, the navigation service and any extra parameters set by the developer.

Navigation containers are referred, when parsed, by their name, as defined in the configuration file.

## Required page options and attributes

The following are options that each page should define in the configuration
* `label` - the text of the menu item
* `route` or `uri` - defines the route or link the menu item will have
* `permission` - can be used optionally, if authorization service is present, in order to omit menu items that are not authorized to visit.
