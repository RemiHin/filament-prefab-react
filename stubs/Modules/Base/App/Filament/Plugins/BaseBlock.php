<?php

declare(strict_types=1);

namespace App\Filament\Plugins;

use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;
use stdClass;
use App\Filament\Plugins\BlockContract;
use Filament\Forms\Components\Builder\Block;

abstract class BaseBlock implements BlockContract
{
    public stdClass $data;

    public function __construct(array $data)
    {
        $this->data = (object) $data;
    }

    public static function make(): Block
    {
        return Block::make(static::getType())
            ->schema([
                ...static::getFields(),

                Hidden::make('id')
                    ->default(fn () => Str::uuid()->toString())
            ])
            ->label(static::getLabel());
    }

    public function __get($name)
    {
        return optional($this->data)->{$name};
    }
}
