<?php

namespace Workflowable\TypeGenerator\ObjectProperties;

use Workflowable\TypeGenerator\Abstracts\AbstractObjectProperty;
use Workflowable\TypeGenerator\Contracts\ObjectPropertyContract;
use Workflowable\TypeGenerator\Enums\GenericObjectPropertyTypeEnum;

class GenericObjectProperty extends AbstractObjectProperty implements ObjectPropertyContract
{
    /**
     * @param string                        $name       The name of the property
     * @param GenericObjectPropertyTypeEnum $type       The type of the property
     * @param bool                          $isNullable Whether the property is nullable
     */
    public function __construct(public string $name, public GenericObjectPropertyTypeEnum $type, public bool $isNullable = false)
    {
        parent::__construct($name, $isNullable);
    }
}
