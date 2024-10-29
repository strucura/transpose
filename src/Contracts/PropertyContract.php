<?php

namespace Strucura\Transpose\Contracts;

interface PropertyContract
{
    public function isArrayOf(): PropertyContract;

    public function isNullable(): PropertyContract;
}
