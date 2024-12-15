<?php

declare(strict_types=1);

namespace Database\Factories\Helpers;

class BlockFactoryHandler
{
    public static array $callbacks = [];

    public function register(callable $callable): void
    {
        static::$callbacks[] = $callable;
    }

    public function execute(): void
    {
        foreach (static::$callbacks as $callback) {
            $callback();
        }
    }
}
