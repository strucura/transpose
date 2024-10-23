<?php

namespace Workflowable\TypeGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;
use Spatie\StructureDiscoverer\Discover;
use Workflowable\TypeGenerator\Contracts\DataTypeTransformerContract;
use Workflowable\TypeGenerator\Contracts\WriterContract;

/**
 * Class TypeGeneratorCommand
 *
 * This command generates type definitions for discovered classes into a specified language.
 */
class TypeGeneratorCommand extends Command
{
    protected $signature = 'types:generate {writer}';
    protected $description = 'Generate type definitions for discovered classes into a language of your choice.';

    /**
     * Handle the command execution.
     *
     * @throws ReflectionException
     */
    public function handle(): void
    {
        $paths = config('type-generator.discovery.paths');
        $types = collect();
        $writers = config('type-generator.writers');
        $writerKey = $this->getWriterKey($writers);

        if (!isset($writers[$writerKey])) {
            $this->error('Invalid writer selected');
            return;
        }

        $selectedWriter = $writers[$writerKey];

        foreach ($paths as $path) {
            $discoverableItems = $this->discoverItems($path);

            foreach ($discoverableItems as $discoverableItem) {
                $reflectedDiscoveredItem = new ReflectionClass($discoverableItem);
                $transformer = $this->identifyTransformerForClass($reflectedDiscoveredItem);

                if (is_null($transformer)) {
                    continue;
                }

                $types->push($transformer->transform($reflectedDiscoveredItem));
            }
        }

        $this->writeTypes($types, $selectedWriter);
    }

    /**
     * Get the writer key from the command argument or prompt the user to select one.
     *
     * @param array $writers
     * @return string
     */
    private function getWriterKey(array $writers): string
    {
        return empty($this->argument('writer'))
            ? $this->choice('Select writer', array_keys($writers), 0)
            : $this->argument('writer');
    }

    /**
     * Discover items in the specified path based on the configured conditions.
     *
     * @param string $path
     * @return array
     */
    private function discoverItems(string $path): array
    {
        return Discover::in($path)->any(...config('type-generator.discovery.conditions'))->get();
    }

    /**
     * Write the types to the specified writer.
     *
     * @param Collection $types
     * @param array $selectedWriter
     */
    private function writeTypes(Collection $types, array $selectedWriter): void
    {
        /** @var WriterContract $writer */
        $writer = new $selectedWriter['class'];
        file_put_contents($selectedWriter['output_path'], $writer->write($types));
    }

    /**
     * Identify the appropriate transformer for the given class.
     *
     * @param ReflectionClass $class
     * @return DataTypeTransformerContract|null
     */
    public function identifyTransformerForClass(ReflectionClass $class): ?DataTypeTransformerContract
    {
        $transformers = config('type-generator.transformers');

        foreach ($transformers as $transformer) {
            $transformer = new $transformer;
            if ($transformer->canTransform($class)) {
                return $transformer;
            }
        }

        return null;
    }
}
