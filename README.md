# Type Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/workflowable/type-generator.svg?style=flat-square)](https://packagist.org/packages/workflowable/type-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/workflowable/type-generator/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/workflowable/type-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/workflowable/type-generator/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/workflowable/type-generator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/workflowable/type-generator.svg?style=flat-square)](https://packagist.org/packages/workflowable/type-generator)

Handles the conversion of data structures to TypeScript, with the ability to incorporate other languages.

## Installation

You can install the package via composer:

```bash
composer require workflowable/type-generator
```

You can publish and run the migrations with:

```bash
php artisan types:generate typescript
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="type-generator-config"
```

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
