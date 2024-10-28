<?php

namespace Strucura\TypeGenerator\Builders;

use Spatie\StructureDiscoverer\DiscoverConditions\DiscoverCondition;
use Strucura\TypeGenerator\Contracts\DataTypeTransformerContract;
use Strucura\TypeGenerator\Contracts\WriterContract;

final class BundleBuilder
{
    /**
     * The paths to search for classes to generate types for.
     */
    public array $paths = [];

    /**
     * The conditions to use when discovering classes.
     *
     * @var array<DiscoverCondition>
     */
    public array $conditions = [];

    /**
     * The transformers to use when generating the types.
     *
     * @var array<DataTypeTransformerContract>
     */
    public array $transformers = [];

    /**
     * The writer to use when generating the types.
     */
    public ?WriterContract $writer = null;

    /**
     * The path to write the generated types to.
     */
    public string $writesTo = '';

    public static function make(): BundleBuilder
    {
        return new BundleBuilder;
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
