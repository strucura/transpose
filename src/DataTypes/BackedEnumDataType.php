<?php

namespace Workflowable\TypeGenerator\DataTypes;

use Workflowable\TypeGenerator\Contracts\DataTypeContract;

class BackedEnumDataType implements DataTypeContract
{
    public string $name;

    public array $cases;
}
