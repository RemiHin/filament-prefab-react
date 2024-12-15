<?php

namespace App\Filament\Plugins\Blocks;

use Filament\Forms;
use App\Filament\Plugins\BaseBlock;
use App\Filament\Plugins\BlockModule;

class ToggleContentBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'toggle-content';
    }

    public static function getLabel(): string
    {
        return __('Toggle content');
    }

    public static function getFields(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->label(__('Title'))
                ->required()
                ->string()
                ->maxLength(255),

            BlockModule::make('content', 'toggle_content'),
        ];
    }

    public static function factory(): ?array
    {
        return null;
    }
}
