<?php

namespace DeJoDev\LaravelSequenceNumberGenerator;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

class SequenceNumberManager
{
    protected Container $container;

    protected Repository $config;

    protected array $generators = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->config = $container->make('config');
    }

    /**
     * Get the generator instance.
     *
     * @param  string|null  $name  Name of the generator
     */
    public function generator(?string $name = null): SequenceNumberGenerator
    {
        $name = $name ?: $this->getDefaultGenerator();

        if (is_null($name)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unable to resolve generator for [%s].',
                    static::class
                )
            );
        }

        if (! isset($this->generators[$name])) {
            $this->generators[$name] = $this->createGenerator($name);
        }

        return $this->generators[$name];
    }

    /**
     * Get the default generator name.
     */
    public function getDefaultGenerator(): ?string
    {
        return config('sequence-number-generator.default');
    }

    /**
     * Create a new generator instance.
     *
     * @param  string  $name  Name of the generator
     * @return SequenceNumberGenerator The generator instance
     *
     * @throws \InvalidArgumentException
     */
    public function createGenerator(string $name): SequenceNumberGenerator
    {
        $settings = $this->config->get("sequence-number-generator.$name");

        if (is_null($settings)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unable to resolve generator for [%s].',
                    static::class
                )
            );
        }

        $this->generators[$name] = new SequenceNumberGenerator(
            $settings['sequence_type'],
            $settings['mask'],
            $settings['is_yearly'] ?? true
        );

        return $this->generators[$name];
    }

    /**
     * Dynamically call the default generator instance.
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->generator()->$method(...$parameters);
    }
}
