<?php

namespace Strucura\Transpose\Abstracts;

use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Strucura\Transpose\Contracts\PropertyContract;

abstract class AbstractProperty implements PropertyContract
{
    use Conditionable, Macroable;

    public string $name;

    public bool $isArrayOf = false;

    public bool $isNullable = false;

    public function isArrayOf(): self
    {
        $this->isArrayOf = true;

        return $this;
    }

    public function isNullable(): self
    {
        $this->isNullable = true;

        return $this;
    }
}
