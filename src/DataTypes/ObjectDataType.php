<?php

namespace Strucura\Transpose\DataTypes;

use Strucura\Transpose\Contracts\DataTypeContract;
use Strucura\Transpose\Contracts\PropertyContract;

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
