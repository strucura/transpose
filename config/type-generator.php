<?php

// config for Workflowable/TypeGenerator
use Spatie\StructureDiscoverer\Support\Conditions\ConditionBuilder;
use Workflowable\TypeGenerator\Transformers\BackedEnumDataTypeTransformer;
use Workflowable\TypeGenerator\Transformers\JsonResourceDataTypeTransformer;
use Workflowable\TypeGenerator\Writers\TypeScriptWriter;

return [
    /**
     * The paths to search for classes to generate types for.
     */
    'discovery' => [
        'paths' => [
            app_path(''),
        ],
        'conditions' => [
            ConditionBuilder::create()->classes(),
            ConditionBuilder::create()->enums(),
        ]
    ],

    /**
     * The transformers to use when generating standardized types.
     */
    'transformers' => [
        BackedEnumDataTypeTransformer::class,
        JsonResourceDataTypeTransformer::class,
    ],

    /**
     * Writers to use when generating finalized types
     */
    'writers' => [
        'typescript' => [
            'class' => TypeScriptWriter::class,
            'output_path' => base_path('resources/js/types.ts'),
        ],
    ],
];
