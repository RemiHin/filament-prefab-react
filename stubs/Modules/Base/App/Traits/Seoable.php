<?php

declare(strict_types=1);


namespace App\Traits;

use App\Models\Seo;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;

trait Seoable
{
    public static function bootSeoable(): void
    {
        static::resolveRelationUsing('seo', function (Model $model) {
            return $model->morphOne(Seo::class, 'seoable');
        });
    }

    public static function seoFields(): Group
    {
        return Group::make([
            Fieldset::make('seo')
                ->label(__('SEO'))
                ->relationship(
                    name: 'seo',
                )
                ->columns(1)
                ->schema([
                    Forms\Components\TextInput::make('seo_title')
                        ->label(__('SEO title'))
                        ->string()
                        ->maxLength(250)
                        ->nullable()
                        ->helperText(__('The recommended length is between :min and :max characters', [
                            'min' => 50,
                            'max' => 60,
                        ])),

                    Forms\Components\Textarea::make('description')
                        ->label(__('SEO description'))
                        ->string()
                        ->maxLength(250)
                        ->nullable()
                        ->helperText(__('The recommended length is between :min and :max characters', [
                            'min' => 120,
                            'max' => 170,
                        ])),

                    Forms\Components\Toggle::make('noindex')
                        ->label(__('Allow index'))
                        ->default(true),

                    Forms\Components\Toggle::make('nofollow')
                        ->label(__('Allow follow'))
                        ->default(false)
                        ->helperText(__('Allow search engines to follow links on this resource')),
                ]),

            Forms\Components\Fieldset::make('og')
                ->label(__('Social media'))
                ->relationship(
                    name: 'seo',
                )
                ->columns(1)
                ->schema([
                    Forms\Components\TextInput::make('og_title')
                        ->label(__('Title'))
                        ->string()
                        ->maxLength(250)
                        ->nullable()
                        ->helperText(__('This title will be used when sharing on social media platforms')),

                    CuratorPicker::make('image_id')
                        ->buttonLabel(__('Add image'))
                        ->label(__('Image'))
                        ->nullable()
                        ->helperText(__('This image will be used when sharing on social media platforms. An image with the dimensions of :width by :height is recommended for the best results.', ['width' => 1200, 'height' => 630])),
                ]),
        ]);
    }
}
