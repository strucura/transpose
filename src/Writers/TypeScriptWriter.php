<?php

namespace Workflowable\TypeGenerator\Writers;

use Exception;
use Illuminate\Support\Collection;
use Workflowable\TypeGenerator\Abstracts\AbstractObjectProperty;
use Workflowable\TypeGenerator\Contracts\WriterContract;
use Workflowable\TypeGenerator\DataTypes\BackedEnumDataType;
use Workflowable\TypeGenerator\DataTypes\ObjectDataType;
use Workflowable\TypeGenerator\Enums\GenericObjectPropertyTypeEnum;
use Workflowable\TypeGenerator\ObjectProperties\GenericObjectProperty;
use Workflowable\TypeGenerator\ObjectProperties\InlineEnumObjectProperty;
use Workflowable\TypeGenerator\ObjectProperties\ReferenceArrayObjectProperty;
use Workflowable\TypeGenerator\ObjectProperties\ReferenceObjectProperty;

class TypeScriptWriter implements WriterContract
{
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

    private function writeObject(ObjectDataType $object): string
    {
        $objectName = $object->name;
        $properties = $object->getProperties();

        $propertiesString = '';

        $mappedProperties = collect($properties)->mapWithKeys(function (AbstractObjectProperty $objectProperty) {

            $typeScriptType = match (true) {
                $objectProperty instanceof GenericObjectProperty => $this->mapGenericObjectPropertyTypesToTypeScript($objectProperty),
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

    public function mapGenericObjectPropertyTypesToTypeScript(GenericObjectProperty $property): string
    {
        return match ($property->type) {
            // Number Types
            GenericObjectPropertyTypeEnum::Integer,
            GenericObjectPropertyTypeEnum::Float => 'number',

            // Boolean Types
            GenericObjectPropertyTypeEnum::Boolean => 'boolean',

            // Date Types
            GenericObjectPropertyTypeEnum::DateTime,
            GenericObjectPropertyTypeEnum::Date => 'Date',

            // String Types
            GenericObjectPropertyTypeEnum::String,
            GenericObjectPropertyTypeEnum::Time => 'string',

            // Geometry Types
            GenericObjectPropertyTypeEnum::Point => '{ latitude: number, longitude: number }',
            default => 'any',
        };
    }
}
