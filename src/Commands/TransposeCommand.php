<?php

namespace Strucura\Transpose\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;
use Spatie\StructureDiscoverer\Discover;
use Strucura\Transpose\Builders\TranspositionBuilder;
use Strucura\Transpose\Contracts\DataTypeTransformerContract;

/**
 * Command to perform transpositions on data structures for discovered classes into a language of choice.
 */
class TransposeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transpose {transposition}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform transpositions for discovered classes into a language of your choice.';

    /**
     * Execute the console command.
     *
     * @throws ReflectionException
     */
    public function handle(): void
    {
        $transpositionBuilders = config('transpose.transpositions');
        $transpositionKey = $this->getTranspositionKey($transpositionBuilders);

        if (! $this->isValidTransposition($transpositionBuilders, $transpositionKey)) {
            $this->error('Invalid transposition selected');

            return;
        }

        $selectedTransposition = $transpositionBuilders[$transpositionKey];
        $types = $this->discoverAndTransformTypes($selectedTransposition);
        $this->writeTypes($types, $selectedTransposition);
    }

    /**
     * Get the key of the selected bundle.
     */
    private function getTranspositionKey(array $transpositions): string
    {
        return empty($this->argument('transposition'))
            ? $this->choice('Select transposition', array_keys($transpositions), 0)
            : $this->argument('transposition');
    }

    /**
     * Check if the selected transposition is valid.
     */
    private function isValidTransposition(array $transpositionBuilders, string $transpositionKey): bool
    {
        return isset($transpositionBuilders[$transpositionKey]);
    }

    /**
     * Discover and transform data structures based on the transpose configuration.
     *
     * @throws ReflectionException
     */
    private function discoverAndTransformTypes(TranspositionBuilder $transpositionBuilder): Collection
    {
        $types = collect();

        foreach ($transpositionBuilder->paths as $path) {
            $discoverableItems = Discover::in($path)->any(...$transpositionBuilder->conditions)->get();

            foreach ($discoverableItems as $discoverableItem) {
                $reflectedItem = new ReflectionClass($discoverableItem);
                $transformer = $this->getTransformerForClass($reflectedItem, $transpositionBuilder);

                if ($transformer) {
                    $types->push($transformer->transform($reflectedItem));
                }
            }
        }

        return $types;
    }

    /**
     * Write the transformed types to the specified path.
     */
    private function writeTypes(Collection $types, TranspositionBuilder $bundleBuilder): void
    {
        $writer = new $bundleBuilder->writer;
        file_put_contents($bundleBuilder->writesTo, $writer->write($types));
    }

    /**
     * Get the appropriate transformer for a given class.
     */
    private function getTransformerForClass(ReflectionClass $class, TranspositionBuilder $transpositionBuilder): ?DataTypeTransformerContract
    {
        foreach ($transpositionBuilder->transformers as $transformerClass) {
            $transformer = new $transformerClass;
            if ($transformer->canTransform($class)) {
                return $transformer;
            }
        }

        return null;
    }
}
