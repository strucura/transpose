<?php

use Strucura\TypeGenerator\Abstracts\AbstractProperty;
use Strucura\TypeGenerator\Contracts\PropertyContract;
use Strucura\TypeGenerator\Properties\InlineEnumProperty;

it('initializes properties correctly', function () {
    $name = 'status';
    $cases = ['active', 'inactive'];

    $property = InlineEnumProperty::make($name)->cases($cases)->isNullable();

    expect($property->name)->toBe($name)
        ->and($property->cases)->toBe($cases)
        ->and($property->isNullable)->toBeTrue();
});

it('inherits from AbstractObjectProperty', function () {
    $property = InlineEnumProperty::make('status')->cases(['active', 'inactive'])->isNullable();

    expect($property)->toBeInstanceOf(AbstractProperty::class);
});

it('implements ObjectPropertyContract', function () {
    $property = InlineEnumProperty::make('status')->cases(['active', 'inactive'])->isNullable();

    expect($property)->toBeInstanceOf(PropertyContract::class);
});
