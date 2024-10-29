<?php

namespace Strucura\Transpose\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use ReflectionClass;
use ReflectionException;
use Strucura\Transpose\Attributes\DefineProperties;
use Strucura\Transpose\Attributes\DerivePropertiesFromModel;
use Strucura\Transpose\Concerns\ConvertsTableDefinitionToProperties;
use Strucura\Transpose\Contracts\DataTypeTransformerContract;
use Strucura\Transpose\DataTypes\ObjectDataType;
use Strucura\Transpose\Properties\ReferenceProperty;

/**
 * Transforms Laravel JsonResource classes into standardized ObjectDataType instances.
 */
class JsonResourceDataTypeTransformer implements DataTypeTransformerContract
{
    use ConvertsTableDefinitionToProperties;

    public ObjectDataType $objectData;

    public function __construct()
    {
        $this->objectData = new ObjectDataType;
    }

    /**
     * Determines if the given class can be transformed by this transformer.
     */
    public function canTransform(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(JsonResource::class);
    }

    /**
     * Transforms the given class into an ObjectDataType instance.
     *
     * @throws ReflectionException
     */
    public function transform(ReflectionClass $class): ObjectDataType
    {
        $this->setObjectName($class->getShortName());

        // Derive object properties from the model if the attribute is present.
        if (! empty($class->getAttributes(DerivePropertiesFromModel::class))) {
            $this->derivePropertiesFromModel($class);
        }

        // Apply manually defined object properties if the attribute is present.
        if (! empty($class->getAttributes(DefineProperties::class))) {
            $this->applyDefinedProperties($class);
        }

        return $this->objectData;
    }

    /**
     * Sets the name of the object data type.
     */
    protected function setObjectName(ReflectionClass $class): void
    {
        $this->objectData->name = $class->getShortName();
    }

    /**
     * Derives object properties from the model.
     *
     * @throws ReflectionException
     */
    protected function derivePropertiesFromModel(ReflectionClass $class): void
    {
        $modelFQCN = $class->getAttributes(DerivePropertiesFromModel::class)[0]->getArguments()[0];
        $resourceClass = new ($class->getName())(new $modelFQCN);
        $resourceProperties = $resourceClass->toArray(new Request);

        foreach ($resourceProperties as $resourcePropertyKey => $resourceProperty) {
            $property = match (true) {
                $resourceProperty instanceof AnonymousResourceCollection => ReferenceProperty::make($resourcePropertyKey, $resourceProperty->collects)
                    ->isArrayOf()
                    ->isNullable(),
                $resourceProperty instanceof JsonResource => ReferenceProperty::make($resourcePropertyKey, class_basename($resourceProperty))
                    ->isNullable(),
                default => $this->derivePropertyUsingDatabase($resourcePropertyKey, $modelFQCN),
            };

            $this->objectData->addProperty($property);
        }
    }

    /**
     * Applies manually defined object properties.
     */
    protected function applyDefinedProperties(ReflectionClass $class): void
    {
        $properties = $class->getAttributes(DefineProperties::class)[0]->getArguments()[0];

        foreach ($properties as $property) {
            $this->objectData->addProperty($property);
        }
    }
}
