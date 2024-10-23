# Type Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/workflowable/type-generator.svg?style=flat-square)](https://packagist.org/packages/workflowable/type-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/workflowable/type-generator/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/workflowable/type-generator/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/workflowable/type-generator/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/workflowable/type-generator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/workflowable/type-generator.svg?style=flat-square)](https://packagist.org/packages/workflowable/type-generator)

Type Generator is a package designed to streamline the creation of types for your Laravel application across different languages by introducing standardized data types, which can then be consumed by a writer of your choice.

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

In your configuration there is a section for discovery where you can identify paths where we will look for discovering classes that can be transformed, as well as a way of assigning conditions for assessing whether a class should be looked at. This is handled via a package by Spatie called [php-structure-discoverer](https://github.com/spatie/php-structure-discoverer).

## Transformers and Writers

Transformers take concepts within your application like ENUMs and JsonResources and map them to a standardized set of data types. From there, a writer will be utilized to handle writing your language-specific conversion of those data types. There are several default writers and transformers included by default that will handle conversions of Laravel JSON Resources as well as Backed ENUMS to TypeScript. You can generate your types with:

```bash
php artisan types:generate {writer} // php artisan types:generate typescript
```

You can register new writers and transformers by adding them to your `type-generator.php` configuration file.

### Adding New Writers

To add a new writer, implement the `WriterContract` interface:

```php
namespace Workflowable\TypeGenerator\Writers;

use Workflowable\TypeGenerator\Contracts\WriterContract;
use Illuminate\Support\Collection;

class MyCustomWriter implements WriterContract
{
    public function write(Collection $types): string
    {
        // Implement your custom writing logic here
    }
}
```

Then, register your writer in the `type-generator.php` configuration file:

```php
return [
    'writers' => [
        'my_custom_writer' => [
            'class' => \Workflowable\TypeGenerator\Writers\MyCustomWriter::class,
            'output_path' => base_path('path/to/output/file'),
        ],
    ],
];
```

### Adding New Transformers

To add a new transformer, implement the `DataTypeTransformerContract` interface:

```php
namespace Workflowable\TypeGenerator\Transformers;

use Workflowable\TypeGenerator\Contracts\DataTypeTransformerContract;
use ReflectionClass;

class MyCustomTransformer implements DataTypeTransformerContract
{
    public function canTransform(ReflectionClass $class): bool
    {
        // Implement your logic to determine if the class can be transformed
    }

    public function transform(ReflectionClass $class): DataTypeContract
    {
        // Implement your transformation logic here
    }
}
```

Then, register your transformer in the `type-generator.php` configuration file:

```php
return [
    'transformers' => [
        \Workflowable\TypeGenerator\Transformers\MyCustomTransformer::class,
    ],
];
```

## Attributes

### DefineObjectProperties

The `DefineObjectProperties` attribute allows developers to manually assign properties to an object for edge cases where automated property assignment is not possible.

```php
use Workflowable\TypeGenerator\Attributes\DefineObjectProperties;
use Workflowable\TypeGenerator\ObjectProperties\PrimitiveObjectProperty;
use Workflowable\TypeGenerator\Enums\PrimitiveObjectPropertyTypeEnum;

#[DefineObjectProperties([
    new PrimitiveObjectProperty('property1', PrimitiveObjectPropertyTypeEnum::STRING),
    new PrimitiveObjectProperty('property2', PrimitiveObjectPropertyTypeEnum::INT),
])]
class MyClass
{
    // Class implementation
}
```

### DeriveObjectPropertiesFromModel

The `DeriveObjectPropertiesFromModel` attribute allows developers to derive object properties from a model.

```php
use Workflowable\TypeGenerator\Attributes\DeriveObjectPropertiesFromModel;

#[DeriveObjectPropertiesFromModel(MyModel::class)]
class MyClass
{
    // Class implementation
}
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
```
