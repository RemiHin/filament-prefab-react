<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuItemResource;
use App\Models\Menu;
use Filament\Pages\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use SolutionForest\FilamentTree\Actions;
use SolutionForest\FilamentTree\Concern;
use SolutionForest\FilamentTree\Resources\Pages\TreePage as BasePage;
use SolutionForest\FilamentTree\Support\Utils;

class MenuItemTree extends BasePage
{
    protected static string $resource = MenuItemResource::class;
    public Menu $menu;

    public function mount(int | string $record): void
    {
        $this->menu = Menu::find($record);
    }

    protected function getTreeQuery(): Builder
    {
        return $this->getModel()::query()->where('menu_id', $this->menu->id);
    }

    protected static int $maxDepth = 2;

    protected function getActions(): array
    {
        return [
            $this->getCreateAction(),
        ];
    }

    protected function hasDeleteAction(): bool
    {
        return true;
    }

    protected function hasEditAction(): bool
    {
        return true;
    }

    protected function hasViewAction(): bool
    {
        return false;
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public static function getMaxDepth(): int
    {
        return 2;
    }
}
