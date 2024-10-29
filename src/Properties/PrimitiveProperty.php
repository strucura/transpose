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

    public function __construct(string $name, PrimitivesEnum $primitive)
    {
        $this->name = $name;
        $this->primitive = $primitive;
    }

    public static function make(string $name, PrimitivesEnum $primitive): PrimitiveProperty
    {
        return new PrimitiveProperty($name, $primitive);
    }
}
