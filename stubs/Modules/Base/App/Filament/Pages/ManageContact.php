<?php

namespace App\Filament\Pages;

use App\Settings\ContactSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageContact extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = ContactSettings::class;

    protected static ?string $navigationGroup = 'settings';

    protected static ?int $navigationSort = 100;

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('Contact');
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('street')
                            ->label(__('Street'))
                            ->required(),

                        Forms\Components\TextInput::make('house_number')
                            ->label(__('House Number'))
                            ->required(),
                    ]),

                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('postcode')
                            ->label(__('Postcode'))
                            ->required(),

                        Forms\Components\TextInput::make('city')
                            ->label(__('City'))
                            ->required(),
                    ]),

                Forms\Components\TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('phone')
                    ->label(__('Phone'))
                    ->required(),

                Forms\Components\Fieldset::make('contact_form')
                    ->label(__('Contact form'))
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('admin_name')
                            ->label(__('Admin name'))
                            ->required(),

                        Forms\Components\TextInput::make('admin_email')
                            ->label(__('Admin email'))
                            ->email()
                            ->required(),
                    ]),
            ]);
    }
}
