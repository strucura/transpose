<?php

namespace Workflowable\TypeGenerator\Commands;

use Illuminate\Console\Command;
use ReflectionClass;
use ReflectionException;
use Spatie\StructureDiscoverer\Discover;
use Spatie\StructureDiscoverer\Support\Conditions\ConditionBuilder;
use Workflowable\TypeGenerator\Contracts\DataTypeTransformerContract;
use Workflowable\TypeGenerator\Contracts\WriterContract;

class TypeGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'types:generate {writer}';

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
        $paths = config('type-converter.discovery');

        $types = collect();

        $writers = config('type-converter.writers');

        if (empty($this->argument('writer'))) {
            $writerKey = $this->choice('Select writer', array_keys($writers), 0);
        } else {
            $writerKey = $this->argument('writer');
        }

        if (! isset($writers[$writerKey])) {
            $this->error('Invalid writer selected');

            return;
        }

        $selectedWriter = $writers[$writerKey];

        foreach ($paths as $path) {
            $discoverableItems = Discover::in($path)->any(
                ConditionBuilder::create()->classes(),
                ConditionBuilder::create()->enums(),
            )->get();

            foreach ($discoverableItems as $discoverableItem) {
                if (in_array($discoverableItem, config('type-converter.excluded_discovery_items'))) {
                    continue;
                }

                $reflectedDiscoveredItem = new ReflectionClass($discoverableItem);

                $transformer = $this->identifyTransformerForClass($reflectedDiscoveredItem);

                if (is_null($transformer)) {
                    continue;
                }

                $types->push($transformer->transform($reflectedDiscoveredItem));
            }
        }

        /** @var WriterContract $writer */
        $writer = new $selectedWriter['class'];
        file_put_contents($selectedWriter['output_path'], $writer->write($types));
    }

    public function identifyTransformerForClass(ReflectionClass $class): ?DataTypeTransformerContract
    {
        $transformers = config('type-converter.transformers');

        foreach ($transformers as $transformer) {
            $transformer = new $transformer;
            if ($transformer->canTransform($class)) {
                return $transformer;
            }
        }

        return null;
    }
}
