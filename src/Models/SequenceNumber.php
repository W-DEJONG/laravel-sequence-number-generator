<?php

namespace DeJoDev\LaravelSequenceNumberGenerator\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LaravelSequenceNumber
 *
 * @property string $type
 * @property int $year
 * @property int $last_number
 */
class SequenceNumber extends Model
{
    use HasTimestamps;

    protected $fillable = ['type', 'year', 'last_number'];
}
