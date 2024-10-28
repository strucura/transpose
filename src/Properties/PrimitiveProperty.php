<?php

namespace Strucura\Transpose\Properties;

use Spatie\Macroable\Macroable;
use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;
use Strucura\Transpose\Enums\PrimitivesEnum;

class PrimitiveProperty extends AbstractProperty implements PropertyContract
{
    use Macroable;

    public PrimitivesEnum $primitive;

    public function primitive(PrimitivesEnum $primitive): PrimitiveProperty
    {
        $this->primitive = $primitive;

        return $this;
    }
}
