<?php

namespace DeJoDev\LaravelSequenceNumberGenerator\Facades;

use DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberGenerator
 * @see \DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberManager
 */
class SequenceNumber extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SequenceNumberManager::class;
    }
}
