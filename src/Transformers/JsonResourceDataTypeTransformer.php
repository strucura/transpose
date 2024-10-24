<?php

namespace Strucura\TypeGenerator\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use ReflectionClass;
use ReflectionException;
use Strucura\TypeGenerator\Attributes\DefineObjectProperties;
use Strucura\TypeGenerator\Attributes\DeriveObjectPropertiesFromModel;
use Strucura\TypeGenerator\Concerns\ConvertsTableDefinitionToObjectProperties;
use Strucura\TypeGenerator\Contracts\DataTypeTransformerContract;
use Strucura\TypeGenerator\DataTypes\ObjectDataType;
use Strucura\TypeGenerator\ObjectProperties\ReferenceArrayObjectProperty;
use Strucura\TypeGenerator\ObjectProperties\ReferenceObjectProperty;

/**
 * Transforms Laravel JsonResource classes into standardized ObjectDataType instances.
 */
class JsonResourceDataTypeTransformer implements DataTypeTransformerContract
{
    use ConvertsTableDefinitionToObjectProperties;

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
        $this->setObjectName($class);

        // Derive object properties from the model if the attribute is present.
        if (! empty($class->getAttributes(DeriveObjectPropertiesFromModel::class))) {
            $this->derivePropertiesFromModel($class);
        }

        // Apply manually defined object properties if the attribute is present.
        if (! empty($class->getAttributes(DefineObjectProperties::class))) {
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
        $modelFQCN = $class->getAttributes(DeriveObjectPropertiesFromModel::class)[0]->getArguments()[0];
        $resourceClass = new ($class->getName())(new $modelFQCN);
        $resourceProperties = $resourceClass->toArray(new Request);

        foreach ($resourceProperties as $resourcePropertyKey => $resourceProperty) {
            $property = match (true) {
                $resourceProperty instanceof AnonymousResourceCollection => new ReferenceArrayObjectProperty(
                    $resourcePropertyKey,
                    class_basename($resourceProperty->collects),
                    true
                ),
                $resourceProperty instanceof JsonResource => new ReferenceObjectProperty(
                    $resourcePropertyKey,
                    class_basename($resourceProperty),
                    true
                ),
                default => $this->deriveTypeUsingDatabase($resourcePropertyKey, $modelFQCN),
            };

            $this->objectData->addProperty($property);
        }
    }

    /**
     * Applies manually defined object properties.
     */
    protected function applyDefinedProperties(ReflectionClass $class): void
    {
        $properties = $class->getAttributes(DefineObjectProperties::class)[0]->getArguments()[0];

        foreach ($properties as $property) {
            $this->objectData->addProperty($property);
        }
    }
}
