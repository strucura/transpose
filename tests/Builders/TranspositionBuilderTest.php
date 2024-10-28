<?php

use Spatie\StructureDiscoverer\DiscoverConditions\DiscoverCondition;
use Strucura\Transpose\Builders\TranspositionBuilder;
use Strucura\Transpose\Contracts\DataTypeTransformerContract;
use Strucura\Transpose\Contracts\WriterContract;

it('initializes correctly', function () {
    $builder = TranspositionBuilder::make('Test');

    expect($builder->paths)->toBeArray()->toBeEmpty()
        ->and($builder->name)->toBe('Test')
        ->and($builder->conditions)->toBeArray()->toBeEmpty()
        ->and($builder->transformers)->toBeArray()->toBeEmpty()
        ->and($builder->writer)->toBeEmpty()
        ->and($builder->writesTo)->toBeEmpty();
});

it('sets discovery paths correctly', function () {
    $paths = ['/path/to/discover'];
    $builder = TranspositionBuilder::make('Test')->discoveryPaths($paths);

    expect($builder->paths)->toBe($paths);
});

it('sets discovery conditions correctly', function () {
    $condition = Mockery::mock(DiscoverCondition::class);
    $conditions = [$condition];
    $builder = TranspositionBuilder::make('Test')->discoveryConditions($conditions);

    expect($builder->conditions)->toBe($conditions);
});

it('sets transformers correctly', function () {
    $transformers = [Mockery::mock(DataTypeTransformerContract::class)];
    $builder = TranspositionBuilder::make('Test')->transformers($transformers);

    expect($builder->transformers)->toBe($transformers);
});

it('sets writer correctly', function () {
    $writer = Mockery::mock(WriterContract::class);
    $builder = TranspositionBuilder::make('Test')->writer($writer);

    expect($builder->writer)->toBe($writer);
});

it('sets writesTo path correctly', function () {
    $path = '/path/to/write';
    $builder = TranspositionBuilder::make('Test')->writesTo($path);

    expect($builder->writesTo)->toBe($path);
});
