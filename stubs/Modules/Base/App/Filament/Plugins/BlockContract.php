<?php

declare(strict_types=1);

namespace App\Filament\Plugins;

use Filament\Forms\Components\Builder\Block;

interface BlockContract
{
    public static function getType(): string;

    public static function getLabel(): string;

    public static function getFields(): array;

    public static function factory(): ?array;
}
