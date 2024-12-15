<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuItemResource\Widgets\MenuItemWidget;
use App\Filament\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMenu extends ViewRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MenuItemWidget::class,
        ];
    }
}
