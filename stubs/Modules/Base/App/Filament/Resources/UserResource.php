<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationGroup(): string
    {
        return __('Manage');
    }

    public static function getLabel(): ?string
    {
        return __('User');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label(__('Name')),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignorable: fn ($record) => $record)
                    ->label(__('Email address')),
                Toggle::make('is_admin')
                    ->label(__('Is admin'))
                    ->disabled(fn ($record) => $record && $record->id === Auth::id())
                    ->helperText(fn ($record) => $record && $record->id === Auth::id()
                        ? __('You cannot remove your own admin privileges.')
                        : null),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(__('Name')),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label(__('Email address')),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime('M j, Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('reset password')
                        ->label(__('Reset password link'))
                        ->icon('heroicon-c-lock-closed')
                        ->action(function (User $user) {
                            $user->sendPasswordResetNotification(Password::createToken($user));
                        })
                        ->requiresConfirmation(),
                ]),
            ]);

        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
