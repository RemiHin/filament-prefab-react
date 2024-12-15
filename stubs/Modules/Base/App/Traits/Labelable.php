<?php

declare(strict_types=1);


namespace App\Traits;

use App\Models\Label;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;

trait Labelable
{
    public static function bootLabelable(): void
    {
        static::resolveRelationUsing('label', function (Model $model) {
            return $model->morphOne(Label::class, 'labelable');
        });
    }

    // In order to implement these fields add `static::$model::labelableFields(),` to your fields like any other field
    public static function labelableFields(): Forms\Components\Fieldset
    {
        return
            Forms\Components\Fieldset::make('label')
                ->hidden(fn() => !auth()->user()->isSuperAdmin())
                ->relationship(
                    name: 'label',
                    condition: fn (?array $state): bool => filled($state['label']),
                )
                ->schema([
                    Forms\Components\TextInput::make('label'),
                ])
                ->columns(1);
    }
}
