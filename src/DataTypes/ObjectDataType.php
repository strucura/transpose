<?php

namespace Strucura\TypeGenerator\DataTypes;

use Strucura\TypeGenerator\Contracts\DataTypeContract;
use Strucura\TypeGenerator\Contracts\ObjectPropertyContract;

class ObjectDataType implements DataTypeContract
{
    public string $name;

    protected array $properties = [];

    public function addProperty(ObjectPropertyContract $property): void
    {
        $this->properties[] = $property;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
