<?php

use Illuminate\Support\Facades\Schema;
use Strucura\Transpose\Concerns\ConvertsTableDefinitionToProperties;
use Strucura\Transpose\Enums\PrimitivesEnum;
use Strucura\Transpose\Properties\InlineEnumProperty;
use Strucura\Transpose\Properties\PrimitiveProperty;

beforeEach(function () {
    $this->traitInstance = new class
    {
        use ConvertsTableDefinitionToProperties;
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

    $property = $this->traitInstance->derivePropertyUsingDatabase('status', TestModel::class);

    expect($property)->toBeInstanceOf(InlineEnumProperty::class)
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

    $property = $this->traitInstance->derivePropertyUsingDatabase('is_active', TestModel::class);

    expect($property)->toBeInstanceOf(PrimitiveProperty::class)
        ->and($property->name)->toBe('is_active')
        ->and($property->primitive)->toBe(PrimitivesEnum::Boolean)
        ->and($property->isNullable)->toBeTrue();
});

it('derives generic type using database', function () {
    Schema::shouldReceive('getColumns')
        ->once()
        ->with('test_table')
        ->andReturn([
            ['name' => 'name', 'type' => 'varchar', 'type_name' => 'varchar', 'nullable' => false],
        ]);

    $property = $this->traitInstance->derivePropertyUsingDatabase('name', TestModel::class);

    expect($property)->toBeInstanceOf(PrimitiveProperty::class)
        ->and($property->name)->toBe('name')
        ->and($property->primitive)->toBe(PrimitivesEnum::String)
        ->and($property->isNullable)->toBeFalse();
});
