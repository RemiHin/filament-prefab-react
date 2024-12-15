<?php

namespace RemiHin\FilamentPrefabReactStubs\Modules\Base\App\Filament\Pages;

use App\Settings\SocialsSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use function App\Filament\Pages\__;

class ManageSocials extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = SocialsSettings::class;
    protected static ?string $navigationGroup = 'settings';


    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('facebook')
                    ->inlineLabel()
                    ->helperText(__('An URL should always start with http:// or https://'))
                    ->url()
                    ->label('Facebook'),
                TextInput::make('twitter')
                    ->inlineLabel()
                    ->helperText(__('An URL should always start with http:// or https://'))
                    ->url()
                    ->label('Twitter'),
                TextInput::make('linkedin')
                    ->inlineLabel()
                    ->helperText(__('An URL should always start with http:// or https://'))
                    ->url()
                    ->label('LinkedIn'),
                TextInput::make('instagram')
                    ->inlineLabel()
                    ->helperText(__('An URL should always start with http:// or https://'))
                    ->url()
                    ->label('Instagram'),
                TextInput::make('youtube')
                    ->inlineLabel()
                    ->helperText(__('An URL should always start with http:// or https://'))
                    ->url()
                    ->label('Youtube'),
            ])
            ->columns(1);
    }
}
