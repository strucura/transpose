<?php

namespace Workflowable\TypeGenerator\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use ReflectionClass;
use ReflectionException;
use Workflowable\TypeGenerator\Attributes\DefineObjectProperties;
use Workflowable\TypeGenerator\Attributes\DeriveObjectPropertiesFromModel;
use Workflowable\TypeGenerator\Concerns\ConvertsTableDefinitionToObjectProperties;
use Workflowable\TypeGenerator\Contracts\DataTypeTransformerContract;
use Workflowable\TypeGenerator\DataTypes\ObjectDataType;
use Workflowable\TypeGenerator\ObjectProperties\ReferenceArrayObjectProperty;
use Workflowable\TypeGenerator\ObjectProperties\ReferenceObjectProperty;

class JsonResourceDataTypeTransformer implements DataTypeTransformerContract
{
    use ConvertsTableDefinitionToObjectProperties;

    public ObjectDataType $objectData;

    public function __construct()
    {
        $this->objectData = new ObjectDataType;
    }

    public function canTransform(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(JsonResource::class);
    }

    /**
     * @throws ReflectionException
     */
    public function transform(ReflectionClass $class): ObjectDataType
    {
        // Set the object name
        $this->objectData->name = $class->getShortName();

        // Derive object properties from the model
        if (! empty($class->getAttributes(DeriveObjectPropertiesFromModel::class))) {
            $this->deriveObjectPropertiesFromModel($class);
        }

        // Apply manually defined object properties
        if (! empty($class->getAttributes(DefineObjectProperties::class))) {
            $this->applyManuallyDefinedObjectProperties($class);
        }

        return $this->objectData;
    }

    public function applyManuallyDefinedObjectProperties(ReflectionClass $class): void
    {
        $properties = $class->getAttributes(DefineObjectProperties::class)[0]->getArguments()[0];

        foreach ($properties as $property) {
            $this->objectData->addProperty($property);
        }
    }

    /**
     * @throws ReflectionException
     */
    public function deriveObjectPropertiesFromModel(ReflectionClass $class): void
    {
        // Get the fully qualified class name of the model
        $modelFQCN = $class->getAttributes(DeriveObjectPropertiesFromModel::class)[0]->getArguments()[0];

        // Create an instance of the resource class
        $resourceClass = new ($class->getName())(new $modelFQCN);

        // Get the resource properties
        $resourceProperties = $resourceClass->toArray(new Request);

        foreach ($resourceProperties as $resourcePropertyKey => $resourceProperty) {
            $property = match (true) {
                /**
                 * Derive the reference object property from the anonymous resource collection first because it is a
                 * subclass of JsonResource.
                 */
                $resourceProperty instanceof AnonymousResourceCollection => new ReferenceArrayObjectProperty(
                    $resourcePropertyKey,
                    class_basename($resourceProperty->collects),
                    true
                ),

                /**
                 * Derive the reference object property from the JsonResource.
                 */
                $resourceProperty instanceof JsonResource => new ReferenceObjectProperty(
                    $resourcePropertyKey,
                    class_basename($resourceProperty),
                    true
                ),

                /**
                 * Derive the reference object property from the PointResource.
                 */
                default => $this->deriveTypeUsingDatabase($resourcePropertyKey, $modelFQCN),
            };

            $this->objectData->addProperty($property);
        }
    }
}
