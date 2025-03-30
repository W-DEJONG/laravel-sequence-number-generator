<?php

namespace DeJoDev\LaravelSequenceNumberGenerator;

use DeJoDev\LaravelSequenceNumberGenerator\Models\SequenceNumber;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Throwable;

class SequenceNumberGenerator
{
    /**
     * The last generated sequence number.
     */
    protected ?string $last = null;

    /**
     * Constructor for the SequenceNumberGenerator class.
     *
     * @param  string  $type  The type of the sequence number. Default is 'default'.
     * @param  string  $mask  The mask to format the sequence number. Default is '{y}{######}'.
     * @param  bool  $yearly  Whether the sequence number should reset yearly. Default is true.
     */
    public function __construct(
        private string $type = 'default',
        private string $mask = '{y}{######}',
        private bool $yearly = true
    ) {}

    /**
     * Returns the type of the sequence number.
     *
     * @return string The type of the sequence number.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of the sequence number.
     *
     * @param  string  $type  The type of the sequence number.
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Sets the mask for the sequence number.
     *
     * @param  string  $mask  The mask to format the sequence number.
     */
    public function setMask(string $mask): self
    {
        $this->mask = $mask;

        return $this;
    }

    /**
     * Returns the mask for the sequence number.
     *
     * @return string The mask to format the sequence number.
     */
    public function getMask(): string
    {
        return $this->mask;
    }

    /**
     * Sets whether the sequence number should reset yearly.
     *
     * @param  bool  $yearly  Whether the sequence number should reset yearly.
     */
    public function setYearly(bool $yearly): self
    {
        $this->yearly = $yearly;

        return $this;
    }

    /**
     * Returns whether the sequence number should reset yearly.
     *
     * @return bool Whether the sequence number should reset yearly.
     */
    public function isYearly(): bool
    {
        return $this->yearly;
    }

    /**
     * Initializes the sequence number generator.
     * This method creates a new sequence number in the database if it doesn't exist.
     * If $recreate is true, it will recreate the sequence number. Otherwise, it will fail if the sequence number already exists.
     *
     * @param  int  $sequenceNumber  The initial sequence number to set.
     * @param  bool  $recreate  Whether to recreate the sequence number.
     */
    public function initialize(int $sequenceNumber = 0, bool $recreate = false): void
    {
        if ($recreate) {
            $sequenceNumber = SequenceNumber::where('type', $this->getType())->first();
        }

        if (! $sequenceNumber) {
            $sequenceNumber = new SequenceNumber;
        }

        $sequenceNumber->fill([
            'type',
            $this->getType(),
            'year' => Date::now()->year,
            'last_number' => $sequenceNumber,
        ]);
        $sequenceNumber->save();
    }

    /**
     * Generates a new global sequence number using database locking.
     *
     * @return string The newly generated sequence number.
     *
     * @throws Throwable
     */
    public function generate(): string
    {
        return DB::transaction(function () {
            $sequenceNumber = SequenceNumber::where('type', $this->getType())->lockForUpdate()->first();
            if ($sequenceNumber) {
                if ($this->isYearly() && $sequenceNumber->year != Date::now()->year) {
                    $sequenceNumber->year = Date::now()->year;
                    $sequenceNumber->last_number = 0;
                }
                $sequenceNumber->last_number = $sequenceNumber->last_number + 1;
            } else {
                $sequenceNumber = new SequenceNumber([
                    'type' => $this->type,
                    'year' => Date::now()->year,
                    'last_number' => 1,
                ]);
            }
            $sequenceNumber->save();

            $this->last = static::formatNumber(
                $this->getMask(),
                $sequenceNumber->year,
                $sequenceNumber->last_number,
                $this->getType()
            );

            return $this->last;
        });
    }

    /**
     * Returns the last generated sequence number in this session.
     * This method does not increment the sequence number.
     */
    public function lastReturned(): ?string
    {
        return $this->last;
    }

    /**
     * Returns the last globally generated sequence number.
     * This method does not increment the sequence number.
     */
    public function lastGenerated(): ?string
    {
        $sequenceNumber = SequenceNumber::where('type', $this->getType())->first();
        if (! $sequenceNumber) {
            return null;
        }

        return static::formatNumber(
            $this->getMask(),
            $sequenceNumber->year,
            $sequenceNumber->last_number,
            $this->getType()
        );
    }

    /**
     * Formats the sequence number according to the given mask.
     *
     * @param  string  $mask  The mask to format the sequence number. It can contain the following placeholders:
     *                        <ul>
     *                        <li>{y} - 2-digit year</li>
     *                        <li>{Y} - 4-digit year</li>
     *                        <li>{t} - 1st letter of the type</li>
     *                        <li>{T} - type</li>
     *                        <li>{#} - sequence number, you can use multiple hash characters to specify the number of positions.
     *                        Shorter numbers will be padded with 0s. For example `42` with `#####` gives `0042`.</li>
     *                        </ul>
     * @param  int  $year  The year to include in the formatted string.
     * @param  int  $number  The sequence number to format.
     * @param  string  $type  The type of the sequence number.
     * @return string The formatted sequence number.
     */
    public static function formatNumber(string $mask, int $year, int $number, string $type): string
    {
        return preg_replace_callback('/\{(.*?)}/', function ($matches) use ($year, $number, $type) {
            return match ($matches[1]) {
                'Y' => (string) $year,
                'y' => substr((string) $year, 2, 2),
                'T' => $type,
                't' => substr($type, 0, 1),
                default => preg_match('/^#+$/', $matches[1]) === 1
                    ? str_pad((string) $number, strlen($matches[1]), '0', STR_PAD_LEFT)
                    : $matches[0],
            };
        }, $mask);
    }
}
