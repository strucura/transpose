<?php

// config for Workflowable/TypeGenerator
use Workflowable\TypeGenerator\Transformers\BackedEnumDataTypeTransformer;
use Workflowable\TypeGenerator\Writers\TypeScriptWriter;

return [
    /**
     * The paths to search for classes to generate types for.
     */
    'discovery' => [
        app_path(''),
    ],

    /**
     * The transformers to use when generating standardized types.
     */
    'transformers' => [
        BackedEnumDataTypeTransformer::class,
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
