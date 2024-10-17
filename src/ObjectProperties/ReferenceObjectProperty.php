<?php

namespace Workflowable\TypeGenerator\ObjectProperties;

use Workflowable\TypeGenerator\Abstracts\AbstractObjectProperty;
use Workflowable\TypeGenerator\Contracts\ObjectPropertyContract;

class ReferenceObjectProperty extends AbstractObjectProperty implements ObjectPropertyContract
{
    public string $reference;
}
