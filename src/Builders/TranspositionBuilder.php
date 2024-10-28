<?php

namespace Strucura\Transpose\Builders;

use Spatie\StructureDiscoverer\DiscoverConditions\DiscoverCondition;
use Strucura\Transpose\Contracts\DataTypeTransformerContract;
use Strucura\Transpose\Contracts\WriterContract;

final class TranspositionBuilder
{
    public string $name = '';

    /**
     * The paths to search for classes to perform transpositions on.
     */
    public array $paths = [];

    /**
     * The conditions to use when discovering classes.
     *
     * @var array<DiscoverCondition>
     */
    public array $conditions = [];

    /**
     * The transformers to use when transposing data structures.
     *
     * @var array<DataTypeTransformerContract>
     */
    public array $transformers = [];

    /**
     * The writer to use when transposing data structures.
     */
    public ?WriterContract $writer = null;

    /**
     * The path to write the transposed data structures.
     */
    public string $writesTo = '';

    public static function make(string $name): TranspositionBuilder
    {
        $builder = new TranspositionBuilder;
        $builder->name = $name;

        return $builder;
    }

    public function discoveryPaths(array $paths): self
    {
        $this->paths = $paths;

        return $this;
    }

    public function discoveryConditions(array $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function transformers(array $transformers): self
    {
        $this->transformers = $transformers;

        return $this;
    }

    public function writer(WriterContract $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    public function writesTo(string $path): self
    {
        $this->writesTo = $path;

        return $this;
    }
}
