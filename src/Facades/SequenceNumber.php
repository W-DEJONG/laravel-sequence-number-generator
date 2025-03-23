<?php

namespace DeJoDev\LaravelSequenceNumberGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberGenerator
 */
class SequenceNumber extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberManager::class;
    }
}
