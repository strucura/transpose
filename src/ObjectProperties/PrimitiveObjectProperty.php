<?php

namespace Workflowable\TypeGenerator\ObjectProperties;

use Workflowable\TypeGenerator\Abstracts\AbstractObjectProperty;
use Workflowable\TypeGenerator\Contracts\ObjectPropertyContract;
use Workflowable\TypeGenerator\Enums\PrimitiveObjectPropertyTypeEnum;

class PrimitiveObjectProperty extends AbstractObjectProperty implements ObjectPropertyContract
{
    /**
     * @param  string  $name  The name of the property
     * @param  PrimitiveObjectPropertyTypeEnum  $primitive  The type of the property
     * @param  bool  $isNullable  Whether the property is nullable
     */
    public function __construct(public string $name, public PrimitiveObjectPropertyTypeEnum $primitive, public bool $isNullable = false)
    {
        parent::__construct($name, $isNullable);
    }
}
