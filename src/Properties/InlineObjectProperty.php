<?php

namespace Strucura\Transpose\Properties;

use Spatie\Macroable\Macroable;
use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;

class InlineObjectProperty extends AbstractProperty implements PropertyContract
{
    use Macroable;

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
