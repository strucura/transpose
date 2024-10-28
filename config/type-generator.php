<?php

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\StructureDiscoverer\Support\Conditions\ConditionBuilder;
use Strucura\TypeGenerator\Builders\BundleBuilder;
use Strucura\TypeGenerator\Transformers\BackedEnumDataTypeTransformer;
use Strucura\TypeGenerator\Transformers\JsonResourceDataTypeTransformer;
use Strucura\TypeGenerator\Writers\TypeScriptWriter;

return [
    'bundles' => [
        /**
         * This bundle will generate TypeScript types for all json resources and enums in the app directory.
         */
        'typescript' => BundleBuilder::make()
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
