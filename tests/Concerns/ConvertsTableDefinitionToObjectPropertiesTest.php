<?php

use Illuminate\Support\Facades\Schema;
use Strucura\TypeGenerator\Concerns\ConvertsTableDefinitionToObjectProperties;
use Strucura\TypeGenerator\Enums\PrimitiveObjectPropertyTypeEnum;
use Strucura\TypeGenerator\ObjectProperties\InlineEnumObjectProperty;
use Strucura\TypeGenerator\ObjectProperties\PrimitiveObjectProperty;

beforeEach(function () {
    $this->traitInstance = new class
    {
        use ConvertsTableDefinitionToObjectProperties;
    };
});

class TestModel
{
    public function getTable(): string
    {
        return 'test_table';
    }
}

it('derives enum type using database', function () {
    Schema::shouldReceive('getColumns')
        ->once()
        ->with('test_table')
        ->andReturn([
            ['name' => 'status', 'type' => "enum('active','inactive')", 'type_name' => 'enum', 'nullable' => false],
        ]);

    $property = $this->traitInstance->deriveTypeUsingDatabase('status', TestModel::class);

    expect($property)->toBeInstanceOf(InlineEnumObjectProperty::class)
        ->and($property->name)->toBe('status')
        ->and($property->cases)->toBe(['active', 'inactive']);
});

it('derives boolean type using database', function () {
    Schema::shouldReceive('getColumns')
        ->once()
        ->with('test_table')
        ->andReturn([
            ['name' => 'is_active', 'type' => 'tinyint(1)', 'type_name' => 'tinyint(1)', 'nullable' => true],
        ]);

    $property = $this->traitInstance->deriveTypeUsingDatabase('is_active', TestModel::class);

    expect($property)->toBeInstanceOf(PrimitiveObjectProperty::class)
        ->and($property->name)->toBe('is_active')
        ->and($property->primitive)->toBe(PrimitiveObjectPropertyTypeEnum::Boolean)
        ->and($property->isNullable)->toBeTrue();
});

it('derives generic type using database', function () {
    Schema::shouldReceive('getColumns')
        ->once()
        ->with('test_table')
        ->andReturn([
            ['name' => 'name', 'type' => 'varchar', 'type_name' => 'varchar', 'nullable' => false],
        ]);

    $property = $this->traitInstance->deriveTypeUsingDatabase('name', TestModel::class);

    expect($property)->toBeInstanceOf(PrimitiveObjectProperty::class)
        ->and($property->name)->toBe('name')
        ->and($property->primitive)->toBe(PrimitiveObjectPropertyTypeEnum::String)
        ->and($property->isNullable)->toBeFalse();
});
