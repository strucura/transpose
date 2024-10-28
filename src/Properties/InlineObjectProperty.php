<?php

namespace Strucura\TypeGenerator\Properties;

use Spatie\Macroable\Macroable;
use Strucura\TypeGenerator\Abstracts\AbstractProperty;
use Strucura\TypeGenerator\Contracts\PropertyContract;

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
