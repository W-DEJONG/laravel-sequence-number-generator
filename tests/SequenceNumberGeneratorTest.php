<?php

use DeJoDev\LaravelSequenceNumberGenerator\Models\SequenceNumber;
use DeJoDev\LaravelSequenceNumberGenerator\SequenceNumberGenerator;
use Illuminate\Support\Facades\Date;

it('can initialize', function () {
    $number = SequenceNumber::where('type', 'INV')->first();
    expect($number)->toBeNull();
    Date::setTestNow('2025-01-01 00:00:00');
    $generator = new SequenceNumberGenerator(type: 'INV', mask: '{T}-{Y}-{######}', yearly: true);
    $generator->initialize();
    $number = SequenceNumber::where('type', 'INV')->first();
    expect($number)->not()->toBeNull();
});

it('cannot initialize twice', function () {
    $number = SequenceNumber::where('type', 'INV')->first();
    expect($number)->toBeNull();
    Date::setTestNow('2025-01-01 00:00:00');
    $generator = new SequenceNumberGenerator(type: 'INV', mask: '{T}-{Y}-{######}', yearly: true);
    $generator->initialize();
    expect($generator->generate())->toBe('INV-2025-000001');
    $generator->initialize();
})->throws(Exception::class);

it('can initialize twice if recreate is set', function () {
    $number = SequenceNumber::where('type', 'INV')->first();
    expect($number)->toBeNull();
    Date::setTestNow('2025-01-01 00:00:00');
    $generator = new SequenceNumberGenerator(type: 'INV', mask: '{T}-{Y}-{######}', yearly: true);
    $generator->initialize();
    expect($generator->generate())->toBe('INV-2025-000001');
    $generator->initialize(number: 100, recreate: true);
    expect($generator->generate())->toBe('INV-2025-000101');
});

it('formats numbers', function () {
    $generator = new SequenceNumberGenerator;
    $number = $generator->formatNumber('{T}-{Y}-{######}', 2021, 1, 'INV');
    expect($number)->toBe('INV-2021-000001');

    $number = $generator->formatNumber('INV-{Y}-{####}', 2021, 1, 'default');
    expect($number)->toBe('INV-2021-0001');

    $number = $generator->formatNumber('{t}-{Y}-{####}', 2021, 1, 'Invoice');
    expect($number)->toBe('I-2021-0001');
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

it('returns the last generated number for this generator', function () {
    Date::setTestNow('2025-01-01 00:00:00');
    $generator = new SequenceNumberGenerator(type: 'INV', mask: '{T}-{Y}-{######}', yearly: true);
    expect($generator->generate())->toBe('INV-2025-000001')
        ->and($generator->last())->toBe('INV-2025-000001');

    $generator->generate();
    $generator->generate();
    expect($generator->last())->toBe('INV-2025-000003');
});

it('Returns the last globally generated number', function () {
    Date::setTestNow('2025-01-01 00:00:00');
    $generator = new SequenceNumberGenerator(type: 'INV', mask: '{T}-{Y}-{######}', yearly: true);
    expect($generator->generate())->toBe('INV-2025-000001')
        ->and($generator->lastGenerated())->toBe('INV-2025-000001');

    $generator->generate();
    $generator->generate();

    $newGenerator = new SequenceNumberGenerator(type: 'INV', mask: '{T}-{Y}-{######}', yearly: true);
    expect($newGenerator->last())->toBeNull()
        ->and($newGenerator->lastGenerated())->toBe('INV-2025-000003');
});

it('Rotates numbering over years', function () {
    Date::setTestNow('2025-01-01 00:00:00');
    $generator = new SequenceNumberGenerator(type: 'INV', mask: '{T}-{Y}-{######}', yearly: true);
    expect($generator->generate())->toBe('INV-2025-000001')
        ->and($generator->generate())->toBe('INV-2025-000002');

    Date::setTestNow('2026-01-01 00:00:00');
    expect($generator->generate())->toBe('INV-2026-000001')
        ->and($generator->last())->toBe('INV-2026-000001');
});

it('Returns null for last generated number if not generated', function () {
    Date::setTestNow('2025-01-01 00:00:00');
    $generator = new SequenceNumberGenerator(type: 'INV', mask: '{T}-{Y}-{######}', yearly: true);
    expect($generator->lastGenerated())->toBeNull()
        ->and($generator->last())->toBeNull();
});
