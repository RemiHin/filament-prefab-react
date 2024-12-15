<?php

declare(strict_types=1);

return [
    'active' => [
        App\Filament\Plugins\Blocks\TextBlock::class,
        App\Filament\Plugins\Blocks\TextWithTitleBlock::class,
        App\Filament\Plugins\Blocks\TextWithImageBlock::class,
        App\Filament\Plugins\Blocks\ImageBlock::class,
        App\Filament\Plugins\Blocks\QuoteBlock::class,
        App\Filament\Plugins\Blocks\ToggleContentBlock::class,
    ],

    'toggle_content' => [
        App\Filament\Plugins\Blocks\TextBlock::class,
        App\Filament\Plugins\Blocks\TextWithTitleBlock::class,
        App\Filament\Plugins\Blocks\TextWithImageBlock::class,
        App\Filament\Plugins\Blocks\ImageBlock::class,
        App\Filament\Plugins\Blocks\QuoteBlock::class,
    ],

    'video_disable_keyboard' => false,
];
