<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\MenuItem;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Contracts\Menuable;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\File;
use App\Filament\Resources\MenuItemResource\Pages;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-center-left';

    protected static ?int $navigationSort = 11;

    public static function getNavigationGroup(): string
    {
        return __('Modules');
    }

    public static function getLabel(): ?string
    {
        return __('Menu item');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Menu items');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('menu_id')
                    ->label(__('Menu'))
                    ->relationship('menu', 'title')
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label(__('Title'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('url_type')
                    ->label(__('Url type'))
                    ->live()
                    ->options([
                        'internal' => __('Intern'),
                        'external' => __('External url'),
                    ]),

                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('url')
                        ->label(__('Url'))
                        ->requiredIf('url_type', 'external')
                        ->url()
                        ->visible(fn(Get $get) => $get('url_type') == 'external')
                ]),

                Forms\Components\Group::make([
                    Forms\Components\Select::make('menuable_type')
                        ->label('Type')
                        ->requiredIf('url_type', 'internal')
                        ->live()
                        ->options(function () {
                            $appNamespace = Container::getInstance()->getNamespace();
                            $modelNamespace = 'Models';

                            $models = collect(File::allFiles(app_path($modelNamespace)))->map(function ($item) use ($appNamespace, $modelNamespace) {
                                $rel   = $item->getRelativePathName();
                                $class = sprintf('\%s%s%s', $appNamespace, $modelNamespace ? $modelNamespace . '\\' : '',
                                    implode('\\', explode('/', substr($rel, 0, strrpos($rel, '.')))));
                                return class_exists($class) ? $class : null;
                            })->filter();

                            $options = $models
                                ->filter(fn ($class) => (new $class) instanceof Menuable)
                                ->mapWithKeys(fn (string $class) => [ltrim($class, '\\') => $class::getResourceName()]);

                            $options['Empty'] = __('Main item without link');

                            return $options;
                        })
                        ->visible(function(Get $get) {
                            return $get('url_type') == 'internal';
                        }),

                    Forms\Components\Select::make('menuable_id')
                        ->label('Link')
                        ->requiredUnless('menuable_type', 'Empty')
                        ->dehydrateStateUsing(function (Get $get, $state) {
                            if ($get('menuable_type') == 'Empty' && is_null($state)) {
                                return 0;
                            }

                            return $state;
                        })
                        ->live()
                        ->disabled(function (Get $get) {
                            $class = $get('menuable_type');

                            if ($class && $class !== 'Empty') {
                                return ! (new $class) instanceof Menuable;
                            }

                            return true;
                        })
                        ->options(function (Get $get) {
                            $class = $get('menuable_type');

                            if (!$class ||  $class === 'Empty' || ! (new $class) instanceof Menuable) {
                                return [];
                            }

                            return $class::getMenuOptions();
                        })
                        ->visible(function(Get $get) {
                            return $get('url_type') == 'internal';
                        }),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('menu.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('menuable_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('menuable_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'view' => Pages\ViewMenuItem::route('/{record}'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
