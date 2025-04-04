# Generate complex sequence numbers using DB locking to ensure each number is unique.
This package allows you to generate complex sequence numbers in Laravel applications. 
It uses database locking to ensure that each generated number is unique, even in high-concurrency environments. 
You can configure different sequence types, masks, and yearly resets to suit your application's needs.
![Preview](img/preview.png)

## Features

- Generate unique sequence numbers using database locking
- Support for custom sequence types
- Configurable sequence masks
- Yearly resets for sequences
- High-concurrency environment support
- Easy configuration via environment variables
- Simple integration with Laravel applications

## Basic Usage

To generate a sequence number, you can use the facade provided by the package:

```php
use DeJoDev\LaravelSequenceNumberGenerator\Facades\SequenceNumber;

// Generate a sequence number with the default configuration
$sequenceNumber = SequenceNumber::generate();
echo $sequenceNumber; // e.g: INV-2025-000001 

// Generate a sequence number with a specific configuration
$sequenceNumber = SequenceNumber::generator('custom')->generate();
echo $sequenceNumber; // e.g: MY-TYPE-25-000001P
```

> **Warning** This package uses database locking to ensure that each generated number is unique so you must use a database that
supports lockForUpdate() like MySQL, MariaDB or PostgreSQL.

## Installation and usage

You can install the package via Composer:
```bash
composer require dejodev/laravel-sequence-number-generator
```

Then publish run the migrations with:
```bash
php artisan vendor:publish --tag="sequence-number-generator-migrations"
php artisan migrate
```

Set the environment variables in your `.env` file:

```dotenv
SEQUENCE_NUMBER_GENERATOR_DEFAULT=default
SEQUENCE_NUMBER_GENERATOR_TYPE=default
SEQUENCE_NUMBER_GENERATOR_MASK='{#}'
SEQUENCE_NUMBER_GENERATOR_IS_YEARLY=false
```

### Environment Variables

- `SEQUENCE_NUMBER_GENERATOR_DEFAULT`: The default sequence configuration to use. Default is `default`.
- `SEQUENCE_NUMBER_GENERATOR_TYPE`: The type for the default generator. 
   Type is a string value that distinguishes between different sequence generators. Default is `default`.
- `SEQUENCE_NUMBER_GENERATOR_MASK`: The mask format string for the default generator. Default is `{#}`.
- `SEQUENCE_NUMBER_GENERATOR_IS_YEARLY`: Boolean to determine if the default generator should reset yearly. Default is `false`.

### Mask Variables

The mask format string can contain the following variables:

- `{####}`: The sequential number with leading zeros. The number of `#` characters determines the length of the padding.
- `{Y}`: The full year (e.g., 2025).
- `{y}`: The last two digits of the year (e.g., 25).
- `{T}`: The sequence type.
- `{t}`: First letter of the sequence type.

You can also publish the config file with:
```bash
php artisan vendor:publish --tag="sequence-number-generator-config"
```

Using the config file, you can define multiple sequence configurations for your application.
```php
return [
    'default_generator' => env('SEQUENCE_NUMBER_GENERATOR_DEFAULT', 'default'),

    'default' => [
        'sequence_type' => env('SEQUENCE_NUMBER_GENERATOR_TYPE', 'default'),
        'mask' => env('SEQUENCE_NUMBER_GENERATOR_MASK', '{#}'),
        'is_yearly' => env('SEQUENCE_NUMBER_GENERATOR_IS_YEARLY', false),
    ],
    
    'customers' => [
        'sequence_type' => 'customers',
        'mask' => '{######}', // e.g: 000001
        'is_yearly' => false,
    ],
    
    'orders' => [
        'sequence_type' => 'orders',
        'mask' => 'ORD-{y}{######}', // e.g: ORD-25000001
        'is_yearly' => true,
    ],
    
    'invoices' => [
        'sequence_type' => 'INV',
        'mask' => '{T}-{Y}-{######}', // e.g: INV-2025-000001
        'is_yearly' => true,
    ],
];
```
Then use them like this:
```php
use DeJoDev\LaravelSequenceNumberGenerator\Facades\SequenceNumber;

$customerNumber = SequenceNumber::generator('customers')->generate();
echo $customerNumber; // e.g: 000001

$orderNumber = SequenceNumber::generator('orders')->generate();
echo $orderNumber; // e.g: ORD-25000001

$invoiceNumber = SequenceNumber::generator('invoices')->generate();
echo $invoiceNumber; // e.g: INV-2025-000001
```
## Advanced Usage
you can also use the `SequenceNumberGenerator` class directly to generate sequence numbers without using the facade.
Check out `SequenceNumberGenerator.php` for more advanced usage and customization options.

(c) The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
