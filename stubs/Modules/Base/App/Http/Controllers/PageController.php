<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Inertia\Inertia;

class PageController extends Controller
{
    public function home(): \Inertia\Response
    {
        /** @var Page $page */
        $page = Page::query()
            ->whereHas('label', fn (MorphOne|Builder $builder) => $builder->where('label', 'home'))
            ->firstOrFail();

        return Inertia::render(
            'resources/page/home',
            [
                'page' => $page,
            ]
        );
    }

    public function show(Page $page): \Inertia\Response
    {
        return $this->getComponent($page);
    }

    protected function getComponent(Page $page): \Inertia\Response
    {
        abort_if(! $page->isVisible(), 404);

        if ($page->label?->label && file_exists(resource_path('js/Pages/resources/page/') . $page->label->label . '.jsx')) {
            return Inertia::render(
                'resources/page/'.strtolower($page->label->label),
                [
                    'page' => $page,
                ]
            );
        }

        if(file_exists(resource_path('js/Pages/resources/page/default.jsx'))) {
            return Inertia::render(
                'resources/page/default',
                [
                    'page' => $page,
                ]
            );
        }

        return Inertia::render(
            'resources/index',
            [
                'model' => $page,
            ]
        );
    }
}
