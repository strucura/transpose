<?php

namespace Strucura\TypeGenerator\Concerns;

use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionMethod;
use Strucura\TypeGenerator\Abstracts\AbstractProperty;
use Strucura\TypeGenerator\Enums\PrimitivesEnum;
use Strucura\TypeGenerator\Properties\InlineEnumProperty;
use Strucura\TypeGenerator\Properties\PrimitiveProperty;

trait ConvertsTableDefinitionToProperties
{
    /**
     * Derives the property using the database schema.
     *
     * @param  string  $propertyName  The name of the property.
     * @param  string  $modelFQCN  The fully qualified class name of the model.
     * @return AbstractProperty The derived property.
     *
     * @throws \ReflectionException
     * @throws Exception
     */
    public function derivePropertyUsingDatabase(string $propertyName, string $modelFQCN): AbstractProperty
    {
        $table = $this->getTableName($modelFQCN);
        $columnSchema = $this->getColumnSchema($table, $propertyName);

        if (empty($columnSchema)) {
            throw new Exception("Column $propertyName not found in table $table");
        }

        return $this->createObjectProperty($columnSchema, $propertyName);
    }

    /**
     * Retrieves the table name from the model.
     *
     * @param  string  $modelFQCN  The fully qualified class name of the model.
     * @return string The table name.
     *
     * @throws \ReflectionException
     */
    private function getTableName(string $modelFQCN): string
    {
        $getTable = new ReflectionMethod($modelFQCN, 'getTable');

        return $getTable->invoke(new $modelFQCN);
    }

    /**
     * Retrieves the column schema from the database.
     *
     * @param  string  $table  The table name.
     * @param  string  $objectPropertyName  The name of the object property.
     * @return array|null The column schema.
     */
    private function getColumnSchema(string $table, string $objectPropertyName): ?array
    {
        return collect(Schema::getColumns($table))->firstWhere('name', '=', $objectPropertyName);
    }

    /**
     * Creates an object property based on the column schema.
     *
     * @param  array  $columnSchema  The column schema.
     * @param  string  $propertyName  The name of the property.
     * @return AbstractProperty The created object property.
     */
    private function createObjectProperty(array $columnSchema, string $propertyName): AbstractProperty
    {
        return match (true) {
            $columnSchema['type_name'] === 'enum' => $this->handleEnumColumn($columnSchema, $propertyName),
            $columnSchema['type'] === 'tinyint(1)' => $this->handleBooleanColumn($columnSchema, $propertyName),
            default => $this->handleGenericColumn($columnSchema, $propertyName),
        };
    }

    /**
     * Handles the conversion of an enum column to an InlineEnumProperty.
     *
     * @param  array  $column  The column schema.
     * @param  string  $propertyName  The name of the property.
     * @return InlineEnumProperty The created InlineEnumProperty.
     */
    private function handleEnumColumn(array $column, string $propertyName): InlineEnumProperty
    {
        $cases = Str::of($column['type'])
            ->after('enum(')
            ->before(')')
            ->explode("','")
            ->map(fn ($case) => is_numeric($case = Str::of($case)->trim("'")->toString()) ? $case + 0 : $case)
            ->toArray();

        return InlineEnumProperty::make($propertyName)
            ->cases($cases)
            ->when($column['nullable'], fn ($property) => $property->isNullable());
    }

    /**
     * Handles the conversion of a tinyint(1) column to a PrimitiveProperty with a boolean type.
     *
     * @param  array  $column  The column schema.
     * @param  string  $propertyName  The name of the property.
     * @return PrimitiveProperty The created PrimitiveProperty.
     */
    private function handleBooleanColumn(array $column, string $propertyName): PrimitiveProperty
    {
        return PrimitiveProperty::make($propertyName)
            ->primitive(PrimitivesEnum::tryFromDatabaseColumnType('boolean'))
            ->when($column['nullable'], fn ($property) => $property->isNullable());
    }

    /**
     * Handles the conversion of a generic column to a PrimitiveProperty.
     *
     * @param  array  $column  The column schema.
     * @param  string  $propertyName  The name of the property.
     * @return PrimitiveProperty The created PrimitiveProperty.
     */
    private function handleGenericColumn(array $column, string $propertyName): PrimitiveProperty
    {
        return PrimitiveProperty::make($propertyName)
            ->primitive(PrimitivesEnum::tryFromDatabaseColumnType($column['type_name']))
            ->when($column['nullable'], fn ($property) => $property->isNullable());
    }
}
