<?php

namespace Strucura\Transpose\Writers;

use Exception;
use Illuminate\Support\Collection;
use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\WriterContract;
use Strucura\Transpose\DataTypes\BackedEnumDataType;
use Strucura\Transpose\DataTypes\ObjectDataType;
use Strucura\Transpose\Enums\PrimitivesEnum;
use Strucura\Transpose\Properties\InlineEnumProperty;
use Strucura\Transpose\Properties\PrimitiveProperty;
use Strucura\Transpose\Properties\ReferenceProperty;

/**
 * Class TypeScriptWriter
 *
 * This class is responsible for transposing data types into TypeScript definitions.
 */
class TypeScriptWriter implements WriterContract
{
    /**
     * Write TypeScript definitions for a collection of data types.
     *
     * @throws Exception
     */
    public function write(Collection $types): string
    {
        return $types->map(function ($type) {
            if ($type instanceof BackedEnumDataType) {
                return $this->writeEnum($type);
            } elseif ($type instanceof ObjectDataType) {
                return $this->writeObject($type);
            }

            throw new Exception('Unsupported type');
        })->implode("\n");
    }

    /**
     * Write a TypeScript enum definition.
     */
    private function writeEnum(BackedEnumDataType $enum): string
    {
        $enumName = $enum->name;

        $enumValuesString = collect($enum->cases)->map(function ($case, $caseKey) {
            return $caseKey.' = '.(is_numeric($case) ? $case : "'$case'");
        })->implode(",\n    ");

        $enumString = "export enum $enumName {\n";
        $enumString .= "    $enumValuesString\n";
        $enumString .= "}\n";

        return $enumString;
    }

    /**
     * Write a TypeScript interface definition for an object.
     */
    private function writeObject(ObjectDataType $object): string
    {
        $objectName = $object->name;
        $properties = $object->getProperties();

        $propertiesString = '';

        $mappedProperties = collect($properties)->mapWithKeys(function (AbstractProperty $objectProperty) {

            $typeScriptType = match (true) {
                $objectProperty instanceof PrimitiveProperty => $this->mapGenericObjectPropertyTypesToTypeScript($objectProperty),
                $objectProperty instanceof InlineEnumProperty => $this->mapInlineEnumToTypeScript($objectProperty),
                $objectProperty instanceof ReferenceProperty => $objectProperty->reference,
                default => 'any',
            };

            $nullableString = $objectProperty->isNullable ? '?' : '';
            $arrayString = $objectProperty->isArrayOf ? '[]' : '';

            return ["'".$objectProperty->name."'$nullableString" => $typeScriptType.$arrayString];
        });

        foreach ($mappedProperties as $name => $propertyType) {
            $propertiesString .= "  {$name}: {$propertyType}\n";
        }

        $objectString = "export interface $objectName {\n";
        $objectString .= $propertiesString;
        $objectString .= "}\n";

        return $objectString;
    }

    /**
     * Map an inline enum property to a TypeScript type.
     */
    public function mapInlineEnumToTypeScript(InlineEnumProperty $enumObjectProperty): string
    {
        if (empty($enumObjectProperty->cases)) {
            return '(string | number)[]';
        }

        return collect($enumObjectProperty->cases)->map(function ($case) {
            if (is_numeric($case)) {
                return $case;
            }

            return "'$case'";
        })->implode(' | ');
    }

    /**
     * Map a primitive property to a TypeScript type.
     */
    public function mapGenericObjectPropertyTypesToTypeScript(PrimitiveProperty $property): string
    {
        return match ($property->primitive) {
            // Number Types
            PrimitivesEnum::Integer,
            PrimitivesEnum::Float => 'number',

            // Boolean Types
            PrimitivesEnum::Boolean => 'boolean',

            // Date Types
            PrimitivesEnum::DateTime,
            PrimitivesEnum::Date => 'Date',

            // String Types
            PrimitivesEnum::String,
            PrimitivesEnum::Time => 'string',

            // Geometry Types
            PrimitivesEnum::Point => '{ latitude: number, longitude: number }',
            default => 'any',
        };
    }
}
