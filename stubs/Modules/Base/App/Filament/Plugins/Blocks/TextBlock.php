<?php

declare(strict_types=1);

namespace App\Filament\Plugins\Blocks;

use Filament\Forms;
use App\Filament\Plugins\BaseBlock;
use Filament\Forms\Components\Builder\Block;

class TextBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'text';
    }

    public static function getLabel(): string
    {
        return __('Text');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\RichEditor::make('text')
                ->label(__('Text'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->string()
                ->maxLength(65535)
                ->required(),
        ];
    }

    public static function factory(): ?array
    {
        return [
            'text' => sprintf('<p>%s</p>', fake()->paragraph()),
        ];
    }
}
