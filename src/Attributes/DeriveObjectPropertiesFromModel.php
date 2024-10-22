<?php

namespace Workflowable\TypeGenerator\Attributes;

use Attribute;

/**
 * Allows for a developer to derive object properties from a model.
 */
#[Attribute]
class DeriveObjectPropertiesFromModel
{
    /**
     * @param string       $model      The model to derive the properties from.
     * @param string|array $properties The properties to derive from the model. If '*' is provided, all properties will be derived.
     */
    public function __construct(public string $model, public string|array $properties = '*') {}
}
