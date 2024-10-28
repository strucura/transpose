<?php

use Strucura\TypeGenerator\Abstracts\AbstractObjectProperty;
use Strucura\TypeGenerator\Contracts\ObjectPropertyContract;
use Strucura\TypeGenerator\ObjectProperties\InlineEnumObjectProperty;

it('initializes properties correctly', function () {
    $name = 'status';
    $cases = ['active', 'inactive'];
    $isNullable = true;

    $property = new InlineEnumObjectProperty($name, $cases, $isNullable);

    expect($property->name)->toBe($name)
        ->and($property->cases)->toBe($cases)
        ->and($property->isNullable)->toBeTrue();
});

it('inherits from AbstractObjectProperty', function () {
    $property = new InlineEnumObjectProperty('status', ['active', 'inactive']);

    expect($property)->toBeInstanceOf(AbstractObjectProperty::class);
});

it('implements ObjectPropertyContract', function () {
    $property = new InlineEnumObjectProperty('status', ['active', 'inactive']);

    expect($property)->toBeInstanceOf(ObjectPropertyContract::class);
});
