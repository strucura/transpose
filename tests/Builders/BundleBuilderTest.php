<?php

use Spatie\StructureDiscoverer\DiscoverConditions\DiscoverCondition;
use Strucura\TypeGenerator\Builders\BundleBuilder;
use Strucura\TypeGenerator\Contracts\DataTypeTransformerContract;
use Strucura\TypeGenerator\Contracts\WriterContract;

it('initializes correctly', function () {
    $builder = BundleBuilder::make();

    expect($builder->paths)->toBeArray()->toBeEmpty()
        ->and($builder->conditions)->toBeArray()->toBeEmpty()
        ->and($builder->transformers)->toBeArray()->toBeEmpty()
        ->and($builder->writer)->toBeEmpty()
        ->and($builder->writesTo)->toBeEmpty();
});

it('sets discovery paths correctly', function () {
    $paths = ['/path/to/discover'];
    $builder = BundleBuilder::make()->discoveryPaths($paths);

    expect($builder->paths)->toBe($paths);
});

it('sets discovery conditions correctly', function () {
    $condition = Mockery::mock(DiscoverCondition::class);
    $conditions = [$condition];
    $builder = BundleBuilder::make()->discoveryConditions($conditions);

    expect($builder->conditions)->toBe($conditions);
});

it('sets transformers correctly', function () {
    $transformers = [Mockery::mock(DataTypeTransformerContract::class)];
    $builder = BundleBuilder::make()->transformers($transformers);

    expect($builder->transformers)->toBe($transformers);
});

it('sets writer correctly', function () {
    $writer = Mockery::mock(WriterContract::class);
    $builder = BundleBuilder::make()->writer($writer);

    expect($builder->writer)->toBe($writer);
});

it('sets writesTo path correctly', function () {
    $path = '/path/to/write';
    $builder = BundleBuilder::make()->writesTo($path);

    expect($builder->writesTo)->toBe($path);
});
