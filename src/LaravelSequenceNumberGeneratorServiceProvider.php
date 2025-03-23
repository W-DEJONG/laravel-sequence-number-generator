<?php

namespace DeJoDev\LaravelSequenceNumberGenerator;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSequenceNumberGeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-sequence-number-generator')
            ->hasConfigFile()
            ->hasMigration('create_sequence_numbers_table');
    }
}
