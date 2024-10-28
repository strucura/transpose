<?php

use Strucura\TypeGenerator\Abstracts\AbstractObjectProperty;
use Strucura\TypeGenerator\Contracts\ObjectPropertyContract;
use Strucura\TypeGenerator\Enums\PrimitiveObjectPropertyTypeEnum;
use Strucura\TypeGenerator\ObjectProperties\PrimitiveObjectProperty;

it('initializes properties correctly', function () {
    $name = 'age';
    $primitive = PrimitiveObjectPropertyTypeEnum::Integer;

    $property = new PrimitiveObjectProperty($name, $primitive, true);

    expect($property->name)->toBe($name)
        ->and($property->primitive)->toBe($primitive)
        ->and($property->isNullable)->toBeTrue();
});

it('inherits from AbstractObjectProperty', function () {
    $property = new PrimitiveObjectProperty('age', PrimitiveObjectPropertyTypeEnum::Integer);

    expect($property)->toBeInstanceOf(AbstractObjectProperty::class);
});

it('implements ObjectPropertyContract', function () {
    $property = new PrimitiveObjectProperty('age', PrimitiveObjectPropertyTypeEnum::Integer);

    expect($property)->toBeInstanceOf(ObjectPropertyContract::class);
});
