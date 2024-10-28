<?php

namespace Strucura\TypeGenerator\Attributes;

use Attribute;

/**
 * Allows for a developer to derive object properties from a model.
 */
#[Attribute]
class DerivePropertiesFromModel
{
    /**
     * @param  string  $model  The model to derive the properties from.
     */
    public function __construct(public string $model) {}
}
