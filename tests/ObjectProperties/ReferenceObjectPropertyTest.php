<?php

use Strucura\TypeGenerator\Abstracts\AbstractObjectProperty;
use Strucura\TypeGenerator\Contracts\ObjectPropertyContract;
use Strucura\TypeGenerator\ObjectProperties\ReferenceArrayObjectProperty;
use Strucura\TypeGenerator\ObjectProperties\ReferenceObjectProperty;

it('initializes properties correctly', function () {
    $name = 'items';
    $reference = 'Item';

    $property = new ReferenceObjectProperty($name, $reference, true);

    expect($property->name)->toBe($name)
        ->and($property->reference)->toBe($reference)
        ->and($property->isNullable)->toBeTrue();
});

it('inherits from AbstractObjectProperty', function () {
    $property = new ReferenceArrayObjectProperty('items', 'Item');

    expect($property)->toBeInstanceOf(AbstractObjectProperty::class);
});

it('implements ObjectPropertyContract', function () {
    $property = new ReferenceArrayObjectProperty('items', 'Item');

    expect($property)->toBeInstanceOf(ObjectPropertyContract::class);
});
