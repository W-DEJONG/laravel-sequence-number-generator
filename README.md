# Generate complex sequence numbers using DB locking to ensure each number is unique.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dejodev/laravel-sequence-number-generator.svg?style=flat-square)](https://packagist.org/packages/dejodev/laravel-sequence-number-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/dejodev/laravel-sequence-number-generator/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/dejodev/laravel-sequence-number-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/dejodev/laravel-sequence-number-generator/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/dejodev/laravel-sequence-number-generator/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/dejodev/laravel-sequence-number-generator.svg?style=flat-square)](https://packagist.org/packages/dejodev/laravel-sequence-number-generator)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-sequence-number-generator.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-sequence-number-generator)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require dejodev/laravel-sequence-number-generator
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-sequence-number-generator-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-sequence-number-generator-config"
```

This is the contents of the published config file:

```php
return [
    'default' => env('SEQUENCE_NUMBER_GENERATOR_DEFAULT', 'custom'),
    'custom' => [
        'sequence_type' => env('SEQUENCE_NUMBER_GENERATOR_TYPE', 'custom'),
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
```

## Usage

```php
$laravelSequenceNumberGenerator = new DeJoDev\LaravelSequenceNumberGenerator();
echo $laravelSequenceNumberGenerator->echoPhrase('Hello, DeJoDev!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Wouter de Jong](https://github.com/W-DEJONG)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
