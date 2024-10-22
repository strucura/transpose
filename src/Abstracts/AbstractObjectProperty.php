<?php

namespace Workflowable\TypeGenerator\Abstracts;

use Workflowable\TypeGenerator\Contracts\ObjectPropertyContract;

abstract class AbstractObjectProperty implements ObjectPropertyContract
{
    public function __construct(public string $name, public bool $isNullable = false) {}
}
