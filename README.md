# Type Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/workflowable/type-generator.svg?style=flat-square)](https://packagist.org/packages/workflowable/type-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/workflowable/type-generator/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/workflowable/type-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/workflowable/type-generator/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/workflowable/type-generator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/workflowable/type-generator.svg?style=flat-square)](https://packagist.org/packages/workflowable/type-generator)

Type Generator is a package designed to streamline the creation of types for your Laravel application across 
different languages by introducing standardized data types, which can then be consumed by a writer of your choice.

## Installation

You can install the package via composer:

```bash
composer require workflowable/type-generator
```

## Configuration:

You can publish the config file with:

```bash
php artisan vendor:publish --tag="type-generator-config"
```

### Discovery

In your configuration there is a section for discovery where you can identify paths where we will look for discovering
classes that can be transformed, as well as a way of assigning conditions for assessing whether a class should
be looked at.  This is handled via a package by Spatie called [php-structure-discoverer](https://github.com/spatie/php-structure-discoverer)

## Transformers and Writers

Transformers take concepts within your application like ENUMs and JsonResources and map it to a standardized set of
data types.  From there, a writer will be utilized to handle writing your language specific conversion of those data
types.  There are several default writers and transformers that are included by default that will handle conversions of
Laravel JSON Resources as well as Backed ENUMS to TypeScript.  You can generate your types with:

```bash
php artisan types:generate {writer} // php artisan types:generate typescript
```

You can register new writers and transformers by adding them to your `type-generator.php` configuration file.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Andrew Leach](https://github.com/7387639+andyleach)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
