<?php

namespace App\Core\Models\Traits;

use App\Core\Database\Factories\Factory;

trait HasFactory
{
    /**
     * Link the factory class.
     */
    public static function resolveFactory(): string
    {
        return str_replace('Models', 'Database\\Factories', self::class).'Factory';
    }

    /**
     * Get a new factory instance for the model.
     *
     * @param mixed $parameters
     */
    public static function factory(...$parameters): Factory
    {
        if (class_exists(static::resolveFactory())) {
            Factory::guessFactoryNamesUsing(fn () => static::resolveFactory());
        }

        return Factory::factoryForModel(get_called_class())
            ->count(is_numeric($parameters[0] ?? null) ? $parameters[0] : null)
            ->state(is_array($parameters[0] ?? null) ? $parameters[0] : ($parameters[1] ?? []));
    }
}
