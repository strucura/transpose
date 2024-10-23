<?php

namespace Workflowable\TypeGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;
use Spatie\StructureDiscoverer\Discover;
use Workflowable\TypeGenerator\Builders\BundleBuilder;
use Workflowable\TypeGenerator\Contracts\DataTypeTransformerContract;

/**
 * Command to generate type definitions for discovered classes into a language of choice.
 */
class TypeGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'types:generate {bundle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate type definitions for discovered classes into a language of your choice.';

    /**
     * Execute the console command.
     *
     * @throws ReflectionException
     */
    public function handle(): void
    {
        $bundles = config('type-generator.bundles');
        $bundleKey = $this->getBundleKey($bundles);

        if (! $this->isValidBundle($bundles, $bundleKey)) {
            $this->error('Invalid bundle selected');

            return;
        }

        $selectedBundle = $bundles[$bundleKey];
        $types = $this->discoverAndTransformTypes($selectedBundle);
        $this->writeTypes($types, $selectedBundle);
    }

    /**
     * Get the key of the selected bundle.
     */
    private function getBundleKey(array $bundles): string
    {
        return empty($this->argument('bundle'))
            ? $this->choice('Select bundles', array_keys($bundles), 0)
            : $this->argument('bundle');
    }

    /**
     * Check if the selected bundle is valid.
     */
    private function isValidBundle(array $bundleBuilders, string $bundleKey): bool
    {
        return isset($bundleBuilders[$bundleKey]);
    }

    /**
     * Discover and transform types based on the bundle configuration.
     *
     * @throws ReflectionException
     */
    private function discoverAndTransformTypes(BundleBuilder $bundleBuilder): Collection
    {
        $types = collect();

        foreach ($bundleBuilder->paths as $path) {
            $discoverableItems = Discover::in($path)->any(...$bundleBuilder->conditions)->get();

            foreach ($discoverableItems as $discoverableItem) {
                $reflectedItem = new ReflectionClass($discoverableItem);
                $transformer = $this->getTransformerForClass($reflectedItem, $bundleBuilder);

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
    private function writeTypes(Collection $types, BundleBuilder $bundleBuilder): void
    {
        $writer = new $bundleBuilder->writer;
        file_put_contents($bundleBuilder->writeTo, $writer->write($types));
    }

    /**
     * Get the appropriate transformer for a given class.
     */
    private function getTransformerForClass(ReflectionClass $class, BundleBuilder $bundleBuilder): ?DataTypeTransformerContract
    {
        foreach ($bundleBuilder->transformers as $transformerClass) {
            $transformer = new $transformerClass;
            if ($transformer->canTransform($class)) {
                return $transformer;
            }
        }

        return null;
    }
}
