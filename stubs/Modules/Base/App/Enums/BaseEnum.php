<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;
use ReflectionClass;

abstract class BaseEnum
{
    /**
     * Constants cache.
     *
     * @var array
     */
    protected static $constCacheArray = [];

    /**
     * Get all of the constants defined on the class.
     *
     * @return array
     */
    protected static function getConstants(): array
    {
        $calledClass = static::class;
        if (! array_key_exists($calledClass, static::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            static::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return static::$constCacheArray[$calledClass];
    }

    /**
     * Get all of the enum keys.
     *
     * @return array
     */
    public static function getKeys(): array
    {
        return array_keys(static::getConstants());
    }

    /**
     * Get all of the enum values.
     *
     * @return array
     */
    public static function getValues(): array
    {
        return array_values(static::getConstants());
    }

    public static function collect(): EnumCollection
    {
        return new EnumCollection(static::getValues(), static::class);
    }

    public static function translate($key): string
    {
        // Replace _ with space because Str::slug removes underscores
        return __(ucwords(Str::slug(Str::replace('_', ' ', $key), ' ')));
    }
}
