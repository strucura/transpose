<?php

use Strucura\TypeGenerator\Abstracts\AbstractProperty;
use Strucura\TypeGenerator\Contracts\PropertyContract;
use Strucura\TypeGenerator\Properties\ReferenceProperty;

it('initializes properties correctly', function () {
    $name = 'items';
    $reference = 'Item';

    $property = ReferenceProperty::make($name)->references($reference)->isNullable()->isArrayOf();

    expect($property->name)->toBe($name)
        ->and($property->reference)->toBe($reference)
        ->and($property->isNullable)->toBeTrue()
        ->and($property->isArrayOf)->toBeTrue();
});

it('inherits from AbstractObjectProperty', function () {
    $property = ReferenceProperty::make('items')->references('Item')->isNullable();

    expect($property)->toBeInstanceOf(AbstractProperty::class);
});

it('implements ObjectPropertyContract', function () {
    $property = ReferenceProperty::make('items')->references('Item')->isNullable();

    expect($property)->toBeInstanceOf(PropertyContract::class);
});
