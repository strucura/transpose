<?php

namespace Strucura\TypeGenerator\Properties;

use Spatie\Macroable\Macroable;
use Strucura\TypeGenerator\Abstracts\AbstractProperty;
use Strucura\TypeGenerator\Contracts\PropertyContract;

class InlineEnumProperty extends AbstractProperty implements PropertyContract
{
    use Macroable;

    public array $cases = [];

    public function cases(array $cases = []): InlineEnumProperty
    {
        $this->cases = $cases;

        return $this;
    }
}
