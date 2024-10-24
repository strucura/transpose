<?php

namespace Strucura\TypeGenerator\Attributes;

use Attribute;

/**
 * Allows for a developer to manually assign properties to an object for edge cases where automated property assignment
 * is not possible.
 */
#[Attribute]
class DefineObjectProperties
{
    public function __construct(public array $properties) {}
}
