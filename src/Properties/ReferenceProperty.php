<?php

namespace Strucura\Transpose\Properties;

use Spatie\Macroable\Macroable;
use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;

class ReferenceProperty extends AbstractProperty implements PropertyContract
{
    use Macroable;

    public string $reference;

    public function references(string $reference): ReferenceProperty
    {
        $this->reference = $reference;

        return $this;
    }
}
