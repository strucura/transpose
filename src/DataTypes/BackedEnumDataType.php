<?php

namespace Workflowable\TypeGenerator\DataTypes;

use Workflowable\TypeGenerator\Contracts\DataTypeContract;

class BackedEnumDataType implements DataTypeContract
{
    public string $name;
    public array $cases;

    /**
     * Constructor for BackedEnumDataType.
     *
     * @param string $name The name of the enum.
     * @param array $cases The cases of the enum.
     */
    public function __construct(string $name, array $cases)
    {
        $this->name = $name;
        $this->cases = $cases;
    }
}
