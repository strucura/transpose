<?php

namespace Strucura\Transpose\Contracts;

use ReflectionClass;

interface DataTypeTransformerContract
{
    public function canTransform(ReflectionClass $class): bool;

    public function transform(ReflectionClass $class): DataTypeContract;
}
