<?php

namespace Strucura\TypeGenerator\ObjectProperties;

use Strucura\TypeGenerator\Abstracts\AbstractObjectProperty;
use Strucura\TypeGenerator\Contracts\ObjectPropertyContract;

class ReferenceArrayObjectProperty extends AbstractObjectProperty implements ObjectPropertyContract
{
    /**
     * @param  string  $name  The name of the property
     * @param  string  $reference  A reference to a previously defined object
     * @param  bool  $isNullable  Whether the property is nullable
     */
    public function __construct(public string $name, public string $reference, public bool $isNullable = false)
    {
        parent::__construct($name, $isNullable);
    }
}
