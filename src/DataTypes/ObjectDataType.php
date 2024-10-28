<?php

namespace Strucura\TypeGenerator\DataTypes;

use Strucura\TypeGenerator\Contracts\DataTypeContract;
use Strucura\TypeGenerator\Contracts\PropertyContract;

class ObjectDataType implements DataTypeContract
{
    public string $name;

    protected array $properties = [];

    public function addProperty(PropertyContract $property): void
    {
        $this->properties[] = $property;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
