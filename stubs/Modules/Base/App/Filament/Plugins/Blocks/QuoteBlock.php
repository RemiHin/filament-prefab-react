<?php

declare(strict_types=1);

namespace App\Filament\Plugins\Blocks;

use Filament\Forms;
use App\Filament\Plugins\BaseBlock;

class QuoteBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'quote';
    }

    public static function getLabel(): string
    {
        return __('Quote');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\Textarea::make('quote')
                ->label(__('Quote'))
                ->string()
                ->maxLength(65535)
                ->required(),
        ];
    }

    public static function factory(): ?array
    {
        return [
            'quote' => fake()->sentence(),
        ];
    }
}
