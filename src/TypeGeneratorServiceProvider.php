<?php

namespace Workflowable\TypeGenerator;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Workflowable\TypeGenerator\Commands\TypeGeneratorCommand;

class TypeGeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('type-generator')
            ->hasConfigFile()
            ->hasCommand(TypeGeneratorCommand::class);
    }
}
