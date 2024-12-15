<?php

declare(strict_types=1);

namespace App\Filament\Plugins\Blocks;

use Filament\Forms;
use App\Filament\Plugins\BaseBlock;

class TextWithTitleBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'text-with-title';
    }

    public static function getLabel(): string
    {
        return __('Text with title');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->label(__('Title'))
                ->required()
                ->string()
                ->maxLength(255),

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
            'title' => fake()->words(5, true),
            'text' => sprintf('<p>%s</p>', fake()->paragraph()),
        ];
    }
}
