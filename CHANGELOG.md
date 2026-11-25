# Changelog

## 5.0.0 - TBD

### Added

- PHP 8.6 support.
- `NavigationInterface::setQueryParams()`. `NavigationMiddleware` sets the query parameters of the request.
- `NavigationContainer::hasPages()`.
- `Dot\Navigation\Provider\InvokableProviderFactory`.

### Changed

- `ProviderPluginManager` is a plain class and supports only the `factories` and `aliases` config keys.
- The factories throw `Dot\Navigation\Exception\RuntimeException` in place of `\Exception`.
- `IsAllowedFilter` is `final`.
- `NavigationContainer::current()` and `getChildren()` return `Page`.
- `render()` and `renderPartial()` throw `InvalidArgumentException` for an empty template name.
- `Navigation::isAllowed()` accepts a string `roles` option as one role.
- Documented the value rules of `htmlAttributes()`. Its output does not change.
- Allowed `mezzio/mezzio-template` 3.x.
- Declared the packages that the code uses directly: `laminas/laminas-stdlib`, `mezzio/mezzio-router`, `psr/container` and `psr/http-server-handler`.
- PHPStan level 8. PHPUnit 12.

### Deprecated

- Nothing.

### Removed

- PHP 8.2 support.
- The `laminas/laminas-servicemanager` requirement.

### Fixed

- `isActive()` compares the keys of the parameters, not only the values.
- `isActive()` uses the query of the request, not `$_GET`.
- `setRouteResult()` clears the active and href caches.
- A parent page without a `route` option is active when a sub-page is active.
- `getPartial()` returns `null` before `setPartial()`.
- `Page::getName()` and `getHref()` accept int names and the URI `"0"`.
- Invalid config gives a package exception, not a PHP warning or a `TypeError`.

## 4.2.0 - 2025-10-31

### Changed

- Removed PHP 8.1 support and added PHP 8.4 and 8.5 support.

## 4.1.0 - 2025-03-06

### Changed

- Replaced Psalm with PHPStan.

## 4.0.3 - 2025-01-30

### Fixed

- Fixed mixed up messages in `NavigationServiceFactory`.

## 4.0.2 - 2025-01-28

### Changed

- Bumped Psalm to 6.x.

### Fixed

- Fixed `.laminas-ci.json`.

## 4.0.1 - 2025-01-20

### Fixed

- Fixed the documentation and the static analysis badge.

## 4.0.0 - 2025-01-20

### Added

- Support for `laminas/laminas-servicemanager` 4.x.
- Documentation for v4.

## 3.6.0 - 2025-11-17

### Changed

- Removed PHP 8.1 support and added PHP 8.4 and 8.5 support.

## 3.5.1 - 2024-07-31

### Changed

- Bumped the minimum version of `dotkernel/dot-helpers` to 3.5.1.

## 3.5.0 - 2024-05-03

### Added

- Documentation.

## 3.4.2 - 2023-11-30

### Fixed

- Added a missing annotation.

## 3.4.1 - 2023-08-22

### Added

- Codecov.

### Fixed

- Added the missing static analysis badge and two missing return types.

## 3.4.0 - 2023-07-20

### Changed

- Code quality.

## 3.3.0 - 2023-03-29

### Removed

- `laminas/laminas-dependency-plugin`.

## 3.2.0 - 2022-05-03

### Changed

- Updated `composer.json`, the README and `OSSMETADATA`.

## 3.1.0 - 2020-11-03

### Changed

- Composer 2 compatibility.
- Update composer.json to require version 2 of laminas/laminas-dependency-plugin to be Composer 2 compatible.

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- bump min version of laminas/laminas-dependency-plugin
- bump min version of dotkernel/dot-helpers

## 2.0.0 - 2020-01-30

### Changed

- Laminas and Mezzio migration.

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Support

### Fixed

- Nothing

## 1.0.0 - 2018-05-15

Migration to Zend Expressive 3

### Changed

- Middleware changed from `http-interop/http-middleware` to `psr/http-server-middleware`

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- nothing

## 0.2.1 - 2017-03-22

### Changed

- Nothing

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- navigation service getHref error when using page option `uri`

## 0.2.0 - 2017-03-17

Migration to ZE2

### Changed

- updated dependencies
- updated to PSR11 container
- updated middleware to PSR15
- changed the way menu item route is configured, to support new UrlHelper options

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 0.1.1 - 2017-03-11

Updated php file headers doc blocks

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 0.1.0 - 2017-03-09

Initial tagged release

### Added

- Everything

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing
