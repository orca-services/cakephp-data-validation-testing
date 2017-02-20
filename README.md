# CakePHP DataValidation Testing

[![Travis-CI Build Status](https://travis-ci.org/your-name/plugin-name.png)](https://travis-ci.org/your-name/plugin-name)
[![Coverage Status](https://img.shields.io/coveralls/your-name/plugin-name.svg)](https://coveralls.io/r/your-name/plugin-name?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/your-name/plugin-name/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/your-name/plugin-name/?branch=master)
[![Total Downloads](https://poser.pugx.org/your-name/plugin-name/d/total.png)](https://packagist.org/packages/your-name/plugin-name)
[![Latest Stable Version](https://poser.pugx.org/your-name/plugin-name/v/stable.png)](https://packagist.org/packages/your-name/plugin-name)

A CakePHP 2.x plugin to help testing data validation.

## Installation

### Requirements

- PHP >= 5.3
- CakePHP 2.x

### CakePHP Version Support

This plugin supports CakePHP 2.x only.

### Installation via composer

First, require the package through Composer:

````
composer require --dev orca-services/cakephp-data-validation-testing
````

Then load plugin in bootstrap.php:

````
CakePlugin::load('DataValidationTesting');
````

### Installation alternatives

Refer to the CakePHP CookBook section
[How To Install Plugins](http://book.cakephp.org/2.0/en/plugins/how-to-install-plugins.html).

## How to use

You can use the plugin as shown in [BlogPostTest example](examples/BlogPostTest.php) .

## Versioning

The releases of this plugin are versioned using [SemVer](http://semver.org/).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md)

## Changelog

See [CHANGELOG.md](CHANGELOG.md)

## TODOs

- Add Unit Tests
- Integrate with Travis CI
- Integrate with Scrutinizer CI
- Integrate with AppVeyor
- Extend examples

## License

This plugin is licensed under the [MIT License](LICENSE).
