<?php

namespace Strucura\Transpose\Properties;

use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;

class InlineObjectProperty extends AbstractProperty implements PropertyContract
{
    public array $properties = [];

    public function __construct(string $name, array $properties = [])
    {
        $this->name = $name;
        $this->properties = $properties;
    }

    public static function make(string $name, array $properties = []): InlineObjectProperty
    {
        return new self($name, $properties);
    }
}
