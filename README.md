# Type Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/strucura/type-generator.svg?style=flat-square)](https://packagist.org/packages/strucura/type-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/strucura/type-generator/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/strucura/type-generator/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/strucura/type-generator/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/strucura/type-generator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/strucura/type-generator.svg?style=flat-square)](https://packagist.org/packages/strucura/type-generator)

Type Generator is a package designed to streamline the creation of types for your Laravel application across different languages by introducing standardized data types, which can then be consumed by a writer of your choice.

## Installation

You can install the package via composer:

```bash
composer require strucura/type-generator
```

## Configuration:

You can publish the config file with:

```bash
php artisan vendor:publish --tag="type-generator-config"
```

### Registering Bundles

To register a bundle, you need to define it in the `type-generator.php` configuration file. A bundle consists of discovery paths, discovery conditions, transformers, and a writer.

Example configuration:

```php
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\StructureDiscoverer\Support\Conditions\ConditionBuilder;
use Strucura\TypeGenerator\Builders\BundleBuilder;
use Strucura\TypeGenerator\Transformers\BackedEnumDataTypeTransformer;
use Strucura\TypeGenerator\Transformers\JsonResourceDataTypeTransformer;
use Strucura\TypeGenerator\Writers\TypeScriptWriter;

return [
    'bundles' => [
        'typescript' => BundleBuilder::make()
            ->discoveryPaths([
                app_path(''),
            ])
            ->discoveryConditions([
                ConditionBuilder::create()->enums(),
                ConditionBuilder::create()
                    ->classes()
                    ->extending(JsonResource::class),
            ])
            ->transformers([
                BackedEnumDataTypeTransformer::class,
                JsonResourceDataTypeTransformer::class,
            ])
            ->writer(TypeScriptWriter::class)
            ->writesTo(base_path('resources/js/types.ts')),
    ],
];
```

For more information on discovery conditions, please refer to the [php-structure-discoverer](https://github.com/spatie/php-structure-discoverer) package.

## Transformers and Writers

Transformers take concepts within your application like ENUMs and JsonResources and map them to a standardized set of data types. From there, a writer will be utilized to handle writing your language-specific conversion of those data types. There are several default writers and transformers included by default that will handle conversions of Laravel JSON Resources as well as Backed ENUMS to TypeScript. You can generate your types with:

```bash
php artisan types:generate {bundle} // php artisan types:generate typescript
```

## Attributes

### DefineObjectProperties

The `DefineObjectProperties` attribute allows developers to manually assign properties to an object for edge cases where automated property assignment is not possible.

```php
use Strucura\TypeGenerator\Attributes\DefineObjectProperties;
use Strucura\TypeGenerator\ObjectProperties\PrimitiveObjectProperty;
use Strucura\TypeGenerator\Enums\PrimitiveObjectPropertyTypeEnum;

#[DefineObjectProperties([
    new PrimitiveObjectProperty('property1', PrimitiveObjectPropertyTypeEnum::String),
    new PrimitiveObjectProperty('property2', PrimitiveObjectPropertyTypeEnum::Integer),
])]
class MyClass
{
    // Class implementation
}
```

### DeriveObjectPropertiesFromModel

The `DeriveObjectPropertiesFromModel` attribute allows developers to derive object properties from a model.

```php
use Strucura\TypeGenerator\Attributes\DeriveObjectPropertiesFromModel;

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
