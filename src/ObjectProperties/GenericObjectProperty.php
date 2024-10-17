<?php

namespace Workflowable\TypeGenerator\ObjectProperties;

use Workflowable\TypeGenerator\Abstracts\AbstractObjectProperty;
use Workflowable\TypeGenerator\Contracts\ObjectPropertyContract;
use Workflowable\TypeGenerator\Enums\GenericObjectPropertyTypeEnum;

class GenericObjectProperty extends AbstractObjectProperty implements ObjectPropertyContract
{
    public GenericObjectPropertyTypeEnum $type;
}
