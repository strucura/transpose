<?php

namespace Strucura\TypeGenerator\Contracts;

use ReflectionClass;

interface DataTypeTransformerContract
{
    public function canTransform(ReflectionClass $class): bool;

    public function transform(ReflectionClass $class): DataTypeContract;
}
