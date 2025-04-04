<?php

// config for DeJoDev\LaravelSequenceNumberGenerator

return [
    'default_generator' => env('SEQUENCE_NUMBER_GENERATOR_DEFAULT', 'default'),

    'default' => [
        'sequence_type' => env('SEQUENCE_NUMBER_GENERATOR_TYPE', 'default'),
        'mask' => env('SEQUENCE_NUMBER_GENERATOR_MASK', '{#}'),
        'is_yearly' => env('SEQUENCE_NUMBER_GENERATOR_IS_YEARLY', false),
    ],

    'simple' => [
        'sequence_type' => 'simple',
        'mask' => '{#}',
        'is_yearly' => false,
    ],

    'yearly' => [
        'sequence_type' => 'yearly',
        'mask' => '{y}-{######}',
        'is_yearly' => true,
    ],
];
