<?php

namespace Strucura\TypeGenerator\Writers;

use Exception;
use Illuminate\Support\Collection;
use Strucura\TypeGenerator\Abstracts\AbstractObjectProperty;
use Strucura\TypeGenerator\Contracts\WriterContract;
use Strucura\TypeGenerator\DataTypes\BackedEnumDataType;
use Strucura\TypeGenerator\DataTypes\ObjectDataType;
use Strucura\TypeGenerator\Enums\PrimitiveObjectPropertyTypeEnum;
use Strucura\TypeGenerator\ObjectProperties\InlineEnumObjectProperty;
use Strucura\TypeGenerator\ObjectProperties\PrimitiveObjectProperty;
use Strucura\TypeGenerator\ObjectProperties\ReferenceArrayObjectProperty;
use Strucura\TypeGenerator\ObjectProperties\ReferenceObjectProperty;

/**
 * Class TypeScriptWriter
 *
 * This class is responsible for converting data types into TypeScript definitions.
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

        $mappedProperties = collect($properties)->mapWithKeys(function (AbstractObjectProperty $objectProperty) {

            $typeScriptType = match (true) {
                $objectProperty instanceof PrimitiveObjectProperty => $this->mapGenericObjectPropertyTypesToTypeScript($objectProperty),
                $objectProperty instanceof InlineEnumObjectProperty => $this->mapInlineEnumToTypeScript($objectProperty),
                $objectProperty instanceof ReferenceObjectProperty => $objectProperty->reference,
                $objectProperty instanceof ReferenceArrayObjectProperty => $objectProperty->reference.'[]',
                default => 'any',
            };

            $nullableString = $objectProperty->isNullable ? '?' : '';

            return ["'".$objectProperty->name."'$nullableString" => $typeScriptType];
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
     * Map an inline enum object property to a TypeScript type.
     */
    public function mapInlineEnumToTypeScript(InlineEnumObjectProperty $enumObjectProperty): string
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
     * Map a primitive object property to a TypeScript type.
     */
    public function mapGenericObjectPropertyTypesToTypeScript(PrimitiveObjectProperty $property): string
    {
        return match ($property->primitive) {
            // Number Types
            PrimitiveObjectPropertyTypeEnum::Integer,
            PrimitiveObjectPropertyTypeEnum::Float => 'number',

            // Boolean Types
            PrimitiveObjectPropertyTypeEnum::Boolean => 'boolean',

            // Date Types
            PrimitiveObjectPropertyTypeEnum::DateTime,
            PrimitiveObjectPropertyTypeEnum::Date => 'Date',

            // String Types
            PrimitiveObjectPropertyTypeEnum::String,
            PrimitiveObjectPropertyTypeEnum::Time => 'string',

            // Geometry Types
            PrimitiveObjectPropertyTypeEnum::Point => '{ latitude: number, longitude: number }',
            default => 'any',
        };
    }
}
