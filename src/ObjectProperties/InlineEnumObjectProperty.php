<?php

namespace Workflowable\TypeGenerator\ObjectProperties;

use Workflowable\TypeGenerator\Abstracts\AbstractObjectProperty;
use Workflowable\TypeGenerator\Contracts\ObjectPropertyContract;

class InlineEnumObjectProperty extends AbstractObjectProperty implements ObjectPropertyContract
{
    public array $cases = [];
}
