<?php

namespace Workflowable\TypeGenerator\Abstracts;

use Workflowable\TypeGenerator\Contracts\ObjectPropertyContract;

abstract class AbstractObjectProperty implements ObjectPropertyContract
{
    /**
     * The name of the property.
     */
    public string $name;

    /**
     * @var bool Whether the property is nullable.
     */
    public bool $isNullable = false;
}
