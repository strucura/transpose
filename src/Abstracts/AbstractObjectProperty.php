<?php

namespace Strucura\TypeGenerator\Abstracts;

use Strucura\TypeGenerator\Contracts\ObjectPropertyContract;

abstract class AbstractObjectProperty implements ObjectPropertyContract
{
    public function __construct(public string $name, public bool $isNullable = false) {}
}
