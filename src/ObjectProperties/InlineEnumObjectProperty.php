<?php

namespace Workflowable\TypeGenerator\ObjectProperties;

use Workflowable\TypeGenerator\Abstracts\AbstractObjectProperty;
use Workflowable\TypeGenerator\Contracts\ObjectPropertyContract;

class InlineEnumObjectProperty extends AbstractObjectProperty implements ObjectPropertyContract
{
    /**
     * @param  string  $name  The name of the property
     * @param  bool  $isNullable  Whether the property is nullable
     * @param  array  $cases  The cases of the enum
     */
    public function __construct(public string $name, public array $cases = [], public bool $isNullable = false)
    {
        parent::__construct($name, $isNullable);
    }
}
