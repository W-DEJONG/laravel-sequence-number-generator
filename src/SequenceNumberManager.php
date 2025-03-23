<?php

namespace DeJoDev\LaravelSequenceNumberGenerator;

class SequenceNumberManager
{
    public static function make(
        string $type,
        string $mask,
        bool $yearly
    ): SequenceNumberGenerator {
        return new SequenceNumberGenerator($type, $mask, $yearly);
    }

    public function generator(?string $name = null): SequenceNumberGenerator
    {
        $name = $name ?: config('sequence-number-generator.default');
        $settings = config("sequence-number-generator.$name");

        return new SequenceNumberGenerator(
            $settings['sequence_type'],
            $settings['mask'],
            $settings['is_yearly'] ?? true
        );
    }

    public function __call($method, $parameters)
    {
        return $this->generator()->$method(...$parameters);
    }
}
