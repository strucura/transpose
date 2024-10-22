<?php

namespace Workflowable\TypeGenerator\Concerns;

use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionMethod;
use Workflowable\TypeGenerator\Abstracts\AbstractObjectProperty;
use Workflowable\TypeGenerator\Enums\GenericObjectPropertyTypeEnum;
use Workflowable\TypeGenerator\ObjectProperties\GenericObjectProperty;
use Workflowable\TypeGenerator\ObjectProperties\InlineEnumObjectProperty;

trait ConvertsTableDefinitionToObjectProperties
{
    /**
     * @throws \ReflectionException
     * @throws Exception
     */
    public function deriveTypeUsingDatabase(string $objectPropertyName, string $modelFQCN): AbstractObjectProperty
    {
        // Grab the table name from the model
        $getTable = new ReflectionMethod($modelFQCN, 'getTable');
        $table = $getTable->invoke(new $modelFQCN);

        $columnSchema = collect(Schema::getColumns($table))->firstWhere('name', '=', $objectPropertyName);

        if (empty($columnSchema)) {
            throw new Exception("Column $objectPropertyName not found in table $table");
        }

        return match (true) {
            /**
             * If the column is an enum, we need to create an InlineEnumObjectProperty
             */
            $columnSchema['type_name'] === 'enum' => $this->handleEnumColumn($columnSchema, $objectPropertyName),

            /**
             * If the column is a tinyint(1), we need to create a GenericObjectProperty with a boolean type
             */
            $columnSchema['type'] === 'tinyint(1)' => $this->handleBooleanColumn($columnSchema, $objectPropertyName),

            /**
             * Otherwise, we can create a GenericObjectProperty with the type derived from the database column type
             */
            default => $this->handleGenericColumn($columnSchema, $objectPropertyName),
        };
    }

    /**
     * Handles the conversion of an enum column to an InlineEnumObjectProperty
     */
    public function handleEnumColumn(array $column, string $objectPropertyName): InlineEnumObjectProperty
    {
        // Extract the cases from the column type
        $cases = Str::of($column['type'])
            ->after('enum(')
            ->before(')')
            ->explode("','")
            ->map(function ($case) {
                // Trim the quotes from the case
                $enumValue = Str::of($case)->trim("'")->toString();

                /**
                 * If the enum value is numeric, cast it to a number.
                 */
                if (is_numeric($enumValue)) {
                    // Adding 0 allows us to maintain the type as a float or int
                    $enumValue = $enumValue + 0;
                }

                return $enumValue;
            })->toArray();

        return new InlineEnumObjectProperty($objectPropertyName, $cases);
    }

    /**
     * Handles the conversion of a tinyint(1) column to a GenericObjectProperty with a boolean type
     */
    public function handleBooleanColumn(array $column, string $objectPropertyName): GenericObjectProperty
    {
        return new GenericObjectProperty(
            $objectPropertyName,
            GenericObjectPropertyTypeEnum::tryFromDatabaseColumnType('boolean'),
            $column['nullable']
        );
    }

    /**
     * Handles the conversion of a generic column to a GenericObjectProperty
     */
    public function handleGenericColumn(array $column, string $objectPropertyName): GenericObjectProperty
    {
        return new GenericObjectProperty(
            $objectPropertyName,
            GenericObjectPropertyTypeEnum::tryFromDatabaseColumnType($column['type_name']),
            $column['nullable']
        );
    }
}
