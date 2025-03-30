<?php

use DeJoDev\LaravelSequenceNumberGenerator\Facades\SequenceNumber as SequenceNumberGeneratorFacade;
use DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberGenerator;
use Illuminate\Support\Facades\Date;

it('formats numbers', function () {
    $generator = new SequenceNumberGenerator;
    $number = $generator->formatNumber('INV-{Y}-{######}', 2021, 1);
    expect($number)->toBe('INV-2021-000001');

    $number = $generator->formatNumber('INV-{Y}-{####}', 2021, 1);
    expect($number)->toBe('INV-2021-0001');
});

it('generates new number', function () {
    //    Date::ShouldReceive('now')->times(3)->andReturn(Carbon::create(2025, 1, 1, 0, 0, 0));
    Date::setTestNow('2025-01-01 00:00:00');
    $generator = (new SequenceNumberGenerator)
        ->setType('INV')
        ->setMask('{T}-{Y}-{######}')
        ->setYearly(true);

    $number = $generator->generate();
    expect($number)->toBe('INV-2025-000001');

    $number = $generator->generate();
    expect($number)->toBe('INV-2025-000002');

    $number = $generator->generate();
    expect($number)->toBe('INV-2025-000003');
});

it('Works through facade', function () {
    $generator = SequenceNumberGeneratorFacade::generator()
        ->setType('INV')
        ->setMask('{T}-{Y}-{######}')
        ->setYearly(true);

    expect($generator)->toBeInstanceOf(SequenceNumberGenerator::class);
    $number = $generator->generateNewNumber();
    expect($number)->toBe('INV-2025-000001');
});

it('Works through facade with make', function () {
    $generator = SequenceNumberGeneratorFacade::make(
        'INV',
        '{T}-{Y}-{######}',
        true
    );

    expect($generator)->toBeInstanceOf(SequenceNumberGenerator::class);
    $number = $generator->generateNewNumber();
    expect($number)->toBe('INV-2025-000001');
});

it('Works through facade with default generator', function () {
    $number = SequenceNumberGeneratorFacade::generate();
    expect($number)->toBe('1');
    $number = SequenceNumberGeneratorFacade::generate();
    expect($number)->toBe('2');
    $number = SequenceNumberGeneratorFacade::generate();
    expect($number)->toBe('3');
});
