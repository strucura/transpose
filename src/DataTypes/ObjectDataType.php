<?php

namespace Workflowable\TypeGenerator\DataTypes;

use Workflowable\TypeGenerator\Contracts\DataTypeContract;
use Workflowable\TypeGenerator\Contracts\ObjectPropertyContract;

class ObjectDataType implements DataTypeContract
{
    public string $name;

    protected array $properties;

    public function addProperty(ObjectPropertyContract $property): void
    {
        $this->properties[] = $property;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
