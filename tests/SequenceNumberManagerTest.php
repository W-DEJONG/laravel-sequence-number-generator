<?php

use DeJoDev\LaravelSequenceNumberGenerator\Facades\SequenceNumber;
use DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberGenerator;
use Illuminate\Support\Facades\Date;

it('Works through facade', function () {
    $generator = SequenceNumber::generator()
        ->setType('INV')
        ->setMask('{T}-{Y}-{######}')
        ->setYearly(true);

    expect($generator)->toBeInstanceOf(SequenceNumberGenerator::class);
    $number = $generator->generate();
    expect($number)->toBe('INV-2025-000001');
});

it('Works through facade with default generator', function () {
    $number = SequenceNumber::generate();
    expect($number)->toBe('1');
    $number = SequenceNumber::generate();
    expect($number)->toBe('2');
    $number = SequenceNumber::generate();
    expect($number)->toBe('3');
});

it('Works through facade with named generator', function () {
    Date::setTestNow('2025-01-01 00:00:00');
    $number = SequenceNumber::generator('yearly')->generate();
    expect($number)->toBe('25-000001');
    $number = SequenceNumber::generator('yearly')->generate();
    expect($number)->toBe('25-000002');
});

it('Throws exception when no default generator is found', function () {
    Date::setTestNow('2025-01-01 00:00:00');
    Config::set('sequence-number-generator.default_generator');
    SequenceNumber::generator()->generate();
})->throws(InvalidArgumentException::class);

it('Throws exception when generator not found', function () {
    Date::setTestNow('2025-01-01 00:00:00');
    Config::set('sequence-number-generator.default_generator');
    SequenceNumber::generator('non-existing')->generate();
})->throws(InvalidArgumentException::class);
