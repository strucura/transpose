<?php

namespace Strucura\Transpose\Contracts;

interface PropertyContract
{
    public static function make(string $name): mixed;

    public function isArrayOf(): PropertyContract;

    public function isNullable(): PropertyContract;
}
