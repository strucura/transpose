<?php

namespace Strucura\Transpose;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Strucura\Transpose\Commands\TransposeCommand;

class TransposeServiceProvider extends PackageServiceProvider
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
            ->hasCommand(TransposeCommand::class);
    }
}
