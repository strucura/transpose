<?php

namespace Strucura\Transpose\Properties;

use Spatie\Macroable\Macroable;
use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;

class InlineObjectProperty extends AbstractProperty implements PropertyContract
{
    use Macroable;

    public array $properties = [];

    public function properties(array $properties = []): InlineObjectProperty
    {
        $this->properties = $properties;

        return $this;
    }
}
