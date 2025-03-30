<?php

namespace DeJoDev\LaravelSequenceNumberGenerator\Facades;

use DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberManager;
use Illuminate\Support\Facades\Facade;

/**
 * Laravel sequence number generator facade.
 *
 * @see \DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberGenerator
 * @see SequenceNumberManager
 */
class SequenceNumber extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SequenceNumberManager::class;
    }
}
