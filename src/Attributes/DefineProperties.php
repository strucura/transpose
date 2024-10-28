<?php

namespace Strucura\Transpose\Attributes;

use Attribute;

/**
 * Allows for a developer to manually assign properties to an object for edge cases where automated property assignment
 * is not possible.
 */
#[Attribute]
class DefineProperties
{
    public function __construct(public array $properties) {}
}
