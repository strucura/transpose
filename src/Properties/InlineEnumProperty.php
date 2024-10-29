<?php

namespace Strucura\Transpose\Properties;

use Strucura\Transpose\Abstracts\AbstractProperty;
use Strucura\Transpose\Contracts\PropertyContract;

class InlineEnumProperty extends AbstractProperty implements PropertyContract
{
    public array $cases = [];

    public function __construct(string $name, array $cases = [])
    {
        $this->name = $name;
        $this->cases = $cases;
    }

    final public static function make(string $name, array $cases = []): InlineEnumProperty
    {
        return new InlineEnumProperty($name, $cases);
    }
}
