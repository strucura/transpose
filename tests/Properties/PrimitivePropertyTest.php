<?php

use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;
use Strucura\Transpose\Enums\PrimitivesEnum;
use Strucura\Transpose\Properties\PrimitiveProperty;

it('initializes properties correctly', function () {
    $name = 'age';
    $primitive = PrimitivesEnum::Integer;

    $property = PrimitiveProperty::make($name)->primitive($primitive)->isNullable();

    expect($property->name)->toBe($name)
        ->and($property->primitive)->toBe($primitive)
        ->and($property->isNullable)->toBeTrue();
});

it('inherits from AbstractObjectProperty', function () {
    $property = PrimitiveProperty::make('age')->primitive(PrimitivesEnum::Integer)->isNullable();

    expect($property)->toBeInstanceOf(AbstractProperty::class);
});

it('implements ObjectPropertyContract', function () {
    $property = PrimitiveProperty::make('age')->primitive(PrimitivesEnum::Integer)->isNullable();

    expect($property)->toBeInstanceOf(PropertyContract::class);
});
