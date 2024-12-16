<?php

declare(strict_types=1);

namespace RemiHin\FilamentPrefabReactStubs\Modules\Blog;

use RemiHin\FilamentPrefabReact\Console\PrefabCommand;

class ModuleActions
{
    public function execute(): void
    {
        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        $prefabCommand = app(PrefabCommand::class);

        $after = <<< 'AFTER'
// RouteDefinitions
AFTER;

        $new = <<< 'NEW'
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
NEW;

        $prefabCommand->addToExistingFile(base_path('routes/web.php'), $new, $after);

        $importBefore = <<< 'IMPORT'
use Illuminate\Support\Facades\Route;
IMPORT;
        $insert = <<< 'INSERT'
use App\Http\Controllers\BlogController;
INSERT;

        $prefabCommand->addToExistingFile(base_path('routes/web.php'), $insert, $importBefore, positionToReference: 'before');




    }
}
