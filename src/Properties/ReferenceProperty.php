<?php

namespace Strucura\Transpose\Properties;

use Spatie\Macroable\Macroable;
use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;

class ReferenceProperty extends AbstractProperty implements PropertyContract
{
    use Macroable;

    public string $reference;

    public function __construct(string $name, string $reference)
    {
        $this->name = $name;
        $this->reference = $reference;
    }

    public static function make(string $name, string $reference): ReferenceProperty
    {
        return new self($name, $reference);
    }
}
