<?php

namespace Strucura\TypeGenerator\Properties;

use Spatie\Macroable\Macroable;
use Strucura\TypeGenerator\Abstracts\AbstractProperty;
use Strucura\TypeGenerator\Contracts\PropertyContract;
use Strucura\TypeGenerator\Enums\PrimitivesEnum;

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
