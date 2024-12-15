<?php

namespace App\Contracts;

interface Menuable
{
    public static function getMenuOptions(): array;

    public static function getResourceName(): string;

    public function getRoute(): string;
}
