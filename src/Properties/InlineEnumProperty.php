<?php

namespace Strucura\Transpose\Properties;

use Spatie\Macroable\Macroable;
use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;

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
