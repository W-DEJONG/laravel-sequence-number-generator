<?php

namespace DeJoDev\LaravelSequenceNumberGenerator;

use DeJoDev\LaravelSequenceNumberGenerator\Models\SequenceNumber;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Throwable;

class SequenceNumberGenerator
{
    public function __construct(private string $type = 'default', private string $mask = '{y}{######}', private bool $yearly = true) {}

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setMask(string $mask): self
    {
        $this->mask = $mask;

        return $this;
    }

    public function getMask(): string
    {
        return $this->mask;
    }

    public function setYearly(bool $yearly): self
    {
        $this->yearly = $yearly;

        return $this;
    }

    public function isYearly(): bool
    {
        return $this->yearly;
    }

    /**
     * @throws Throwable
     */
    public function generateNewNumber(): string
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

            return static::formatNumber(
                $this->getMask(),
                $sequenceNumber->year,
                $sequenceNumber->last_number,
                $this->getType()
            );
        });
    }

    public static function formatNumber(string $mask, int $year, int $number, string $type = 'default'): string
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
