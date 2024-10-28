<?php

use Strucura\Transpose\DataTypes\BackedEnumDataType;
use Strucura\Transpose\Transformers\BackedEnumDataTypeTransformer;

enum SampleEnum: string
{
    case FIRST = 'first';
    case SECOND = 'second';
}

class NonEnumClass {}

it('can transform an enum class', function () {
    $transformer = new BackedEnumDataTypeTransformer;
    $reflectionClass = new ReflectionClass(SampleEnum::class);

    expect($transformer->canTransform($reflectionClass))->toBeTrue();
});

it('cannot transform a non-enum class', function () {
    $transformer = new BackedEnumDataTypeTransformer;
    $reflectionClass = new ReflectionClass(NonEnumClass::class);

    expect($transformer->canTransform($reflectionClass))->toBeFalse();
});

it('transforms an enum class into BackedEnumDataType', function () {
    $transformer = new BackedEnumDataTypeTransformer;
    $reflectionClass = new ReflectionClass(SampleEnum::class);

    $result = $transformer->transform($reflectionClass);

    expect($result)->toBeInstanceOf(BackedEnumDataType::class)
        ->and($result->name)->toBe('SampleEnum')
        ->and($result->cases)->toBe([
            'FIRST' => 'first',
            'SECOND' => 'second',
        ]);
});
