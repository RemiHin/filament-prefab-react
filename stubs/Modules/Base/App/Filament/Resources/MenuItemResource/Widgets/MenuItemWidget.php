<?php

namespace App\Filament\Resources\MenuItemResource\Widgets;

use App\Models\MenuItem;
use Filament\Notifications\Notification;
use SolutionForest\FilamentTree\Actions\Action;
use SolutionForest\FilamentTree\Actions\ActionGroup;
use SolutionForest\FilamentTree\Actions\DeleteAction;
use SolutionForest\FilamentTree\Actions\EditAction;
use SolutionForest\FilamentTree\Actions\ViewAction;
use SolutionForest\FilamentTree\Widgets\Tree as BaseWidget;

class MenuItemWidget extends BaseWidget
{
    protected static string $model = MenuItem::class;

    protected static int $maxDepth = 2;

    protected ?string $treeTitle = 'MenuItemWidget';

    protected bool $enableTreeTitle = true;

    protected function getFormSchema(): array
    {
        return [
            //
        ];
    }
}
