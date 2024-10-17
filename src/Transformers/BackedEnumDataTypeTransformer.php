<?php

namespace Workflowable\TypeGenerator\Transformers;

use BackedEnum;
use Illuminate\Support\Str;
use ReflectionClass;
use Workflowable\TypeGenerator\Contracts\DataTypeTransformerContract;
use Workflowable\TypeGenerator\DataTypes\BackedEnumDataType;

class BackedEnumDataTypeTransformer implements DataTypeTransformerContract
{
    public function canTransform(ReflectionClass $class): bool
    {
        return $class->isEnum();
    }

    public function transform(ReflectionClass $class): BackedEnumDataType
    {
        $typeScriptEnum = new BackedEnumDataType;

        $typeScriptEnum->name = $class->getShortName();

        $cases = [];
        foreach ($class->getConstants() as $name => $enumCaseValue) {
            if ($enumCaseValue instanceof BackedEnum) {
                $enumCaseValue = $enumCaseValue->value;
            }
            $cases[Str::of($name)->upper()->toString()] = $enumCaseValue;
        }

        $typeScriptEnum->cases = $cases;

        return $typeScriptEnum;
    }
}
