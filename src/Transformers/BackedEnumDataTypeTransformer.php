<?php

namespace Workflowable\TypeGenerator\Transformers;

use BackedEnum;
use Illuminate\Support\Str;
use ReflectionClass;
use Workflowable\TypeGenerator\Contracts\DataTypeTransformerContract;
use Workflowable\TypeGenerator\DataTypes\BackedEnumDataType;

class BackedEnumDataTypeTransformer implements DataTypeTransformerContract
{
    /**
     * Determines if the given class can be transformed.
     *
     * @param ReflectionClass $class The class to check.
     * @return bool True if the class is an enum, false otherwise.
     */
    public function canTransform(ReflectionClass $class): bool
    {
        return $class->isEnum();
    }

    /**
     * Transforms the given enum class into a BackedEnumDataType.
     *
     * @param ReflectionClass $class The enum class to transform.
     * @return BackedEnumDataType The transformed data type.
     */
    public function transform(ReflectionClass $class): BackedEnumDataType
    {
        return new BackedEnumDataType(
            $class->getShortName(),
            $this->transformEnumCases($class)
        );
    }

    /**
     * Transforms the enum cases of the given class.
     *
     * @param ReflectionClass $class The enum class.
     * @return array The transformed enum cases.
     */
    private function transformEnumCases(ReflectionClass $class): array
    {
        $cases = [];
        foreach ($class->getConstants() as $name => $enumCaseValue) {
            if ($enumCaseValue instanceof BackedEnum) {
                $enumCaseValue = $enumCaseValue->value;
            }
            $cases[Str::of($name)->upper()->toString()] = $enumCaseValue;
        }

        return $cases;
    }
}
