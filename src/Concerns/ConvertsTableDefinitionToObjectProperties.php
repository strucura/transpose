<?php

namespace Strucura\TypeGenerator\Concerns;

use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionMethod;
use Strucura\TypeGenerator\Abstracts\AbstractObjectProperty;
use Strucura\TypeGenerator\Enums\PrimitiveObjectPropertyTypeEnum;
use Strucura\TypeGenerator\ObjectProperties\InlineEnumObjectProperty;
use Strucura\TypeGenerator\ObjectProperties\PrimitiveObjectProperty;

trait ConvertsTableDefinitionToObjectProperties
{
    /**
     * Derives the type of an object property using the database schema.
     *
     * @param  string  $objectPropertyName  The name of the object property.
     * @param  string  $modelFQCN  The fully qualified class name of the model.
     * @return AbstractObjectProperty The derived object property.
     *
     * @throws \ReflectionException
     * @throws Exception
     */
    public function deriveTypeUsingDatabase(string $objectPropertyName, string $modelFQCN): AbstractObjectProperty
    {
        $table = $this->getTableName($modelFQCN);
        $columnSchema = $this->getColumnSchema($table, $objectPropertyName);

        if (empty($columnSchema)) {
            throw new Exception("Column $objectPropertyName not found in table $table");
        }

        return $this->createObjectProperty($columnSchema, $objectPropertyName);
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
     * @param  string  $objectPropertyName  The name of the object property.
     * @return AbstractObjectProperty The created object property.
     */
    private function createObjectProperty(array $columnSchema, string $objectPropertyName): AbstractObjectProperty
    {
        return match (true) {
            $columnSchema['type_name'] === 'enum' => $this->handleEnumColumn($columnSchema, $objectPropertyName),
            $columnSchema['type'] === 'tinyint(1)' => $this->handleBooleanColumn($columnSchema, $objectPropertyName),
            default => $this->handleGenericColumn($columnSchema, $objectPropertyName),
        };
    }

    /**
     * Handles the conversion of an enum column to an InlineEnumObjectProperty.
     *
     * @param  array  $column  The column schema.
     * @param  string  $objectPropertyName  The name of the object property.
     * @return InlineEnumObjectProperty The created InlineEnumObjectProperty.
     */
    private function handleEnumColumn(array $column, string $objectPropertyName): InlineEnumObjectProperty
    {
        $cases = Str::of($column['type'])
            ->after('enum(')
            ->before(')')
            ->explode("','")
            ->map(fn ($case) => is_numeric($case = Str::of($case)->trim("'")->toString()) ? $case + 0 : $case)
            ->toArray();

        return new InlineEnumObjectProperty($objectPropertyName, $cases);
    }

    /**
     * Handles the conversion of a tinyint(1) column to a PrimitiveObjectProperty with a boolean type.
     *
     * @param  array  $column  The column schema.
     * @param  string  $objectPropertyName  The name of the object property.
     * @return PrimitiveObjectProperty The created PrimitiveObjectProperty.
     */
    private function handleBooleanColumn(array $column, string $objectPropertyName): PrimitiveObjectProperty
    {
        return new PrimitiveObjectProperty(
            $objectPropertyName,
            PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('boolean'),
            $column['nullable']
        );
    }

    /**
     * Handles the conversion of a generic column to a PrimitiveObjectProperty.
     *
     * @param  array  $column  The column schema.
     * @param  string  $objectPropertyName  The name of the object property.
     * @return PrimitiveObjectProperty The created PrimitiveObjectProperty.
     */
    private function handleGenericColumn(array $column, string $objectPropertyName): PrimitiveObjectProperty
    {
        return new PrimitiveObjectProperty(
            $objectPropertyName,
            PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType($column['type_name']),
            $column['nullable']
        );
    }
}
