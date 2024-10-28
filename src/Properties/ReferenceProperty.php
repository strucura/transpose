<?php

namespace Strucura\TypeGenerator\Properties;

use Spatie\Macroable\Macroable;
use Strucura\TypeGenerator\Abstracts\AbstractProperty;
use Strucura\TypeGenerator\Contracts\PropertyContract;

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
