<?php

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\StructureDiscoverer\Support\Conditions\ConditionBuilder;
use Strucura\Transpose\Builders\TranspositionBuilder;
use Strucura\Transpose\Transformers\BackedEnumDataTypeTransformer;
use Strucura\Transpose\Transformers\JsonResourceDataTypeTransformer;
use Strucura\Transpose\Writers\TypeScriptWriter;

return [
    'transpositions' => [
        /**
         * This bundle will generate TypeScript types for all json resources and enums in the app directory.
         */
        TranspositionBuilder::make('TypeScript')
            ->discoveryPaths([
                app_path(''),
            ])
            ->discoveryConditions([
                // Find all enums
                ConditionBuilder::create()->enums(),
                // Find all classes extending JsonResource
                ConditionBuilder::create()
                    ->classes()
                    ->extending(JsonResource::class),

            ])
            ->transformers([
                BackedEnumDataTypeTransformer::class,
                JsonResourceDataTypeTransformer::class,
            ])
            ->writer(new TypeScriptWriter)
            ->writesTo(base_path('resources/js/types.ts')),
    ],
];
