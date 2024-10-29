<?php

use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;
use Strucura\Transpose\Properties\ReferenceProperty;

it('initializes properties correctly', function () {
    $name = 'items';
    $reference = 'Item';

    $property = ReferenceProperty::make($name, $reference)->isNullable()->isArrayOf();

    expect($property->name)->toBe($name)
        ->and($property->reference)->toBe($reference)
        ->and($property->isNullable)->toBeTrue()
        ->and($property->isArrayOf)->toBeTrue();
});

it('inherits from AbstractObjectProperty', function () {
    $property = ReferenceProperty::make('items', 'Item')->isNullable();

    expect($property)->toBeInstanceOf(AbstractProperty::class);
});

it('implements ObjectPropertyContract', function () {
    $property = ReferenceProperty::make('items', 'Item')->isNullable();

    expect($property)->toBeInstanceOf(PropertyContract::class);
});
