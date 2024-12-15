<?php

declare(strict_types=1);

namespace App\Filament\Plugins\Blocks;

use App\Filament\Plugins\BaseBlock;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Models\Media;
use Database\Factories\Helpers\FactoryImage;
use Filament\Forms;

class TextWithImageBlock extends BaseBlock
{
    public static function getType(): string
    {
        return 'text-with-image';
    }

    public static function getLabel(): string
    {
        return __('Text with image');
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

            CuratorPicker::make('image')
                ->label(__('Image'))
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    $media = Media::query()->find($state[array_key_first($state)]['id']);
                    $set('thumbnail', url($media->thumbnail_url));
                    $set('medium', url($media->medium_url));
                    $set('large', url($media->large_url));
                })
                ->required(),

            Forms\Components\Radio::make('position')
                ->label(__('Position'))
                ->options([
                    'left' => __('Left'),
                    'right' => __('Right'),
                ])
                ->required()
                ->default('left'),

            Forms\Components\TextInput::make('thumbnail')
                ->hidden()
                ->dehydrated(true),
            Forms\Components\TextInput::make('medium')
                ->hidden()
                ->dehydrated(true),
            Forms\Components\TextInput::make('large')
                ->hidden()
                ->dehydrated(true),
        ];
    }

    public static function factory(): ?array
    {
        return [
            'text' => sprintf('<p>%s</p>', fake()->paragraph()),
            'image' => FactoryImage::make()->fileManagerField(),
            'position' => fake()->randomElement(['left', 'right']),
        ];
    }
}
