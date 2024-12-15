<?php

namespace App\Http\Middleware;

use App\Enums\MenuEnum;
use App\Models\MenuItem;
use App\Settings\ContactSettings;
use App\Settings\SocialsSettings;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $menus = [];
        $footerMenu = MenuItem::query()
            ->whereHas('menu.label', fn($builder) => $builder->where('label', MenuEnum::FOOTER))
            ->where('parent_id', -1)
            ->with(['children'])
            ->orderBy('order')
            ->get();

        $legalMenu = MenuItem::query()
            ->whereHas('menu.label', fn($builder) => $builder->where('label', MenuEnum::LEGAL_TERMS))
            ->where('parent_id', -1)
            ->with(['children'])
            ->orderBy('order')
            ->get();

        $mainMenu = MenuItem::query()
            ->whereHas('menu.label', fn($builder) => $builder->where('label', MenuEnum::MAIN))
            ->orderBy('order')
            ->where('parent_id', -1)
            ->with(['children'])
            ->get();

        $topMenu = MenuItem::query()
            ->whereHas('menu.label', fn($builder) => $builder->where('label', MenuEnum::TOP))
            ->where('parent_id', -1)
            ->with(['children'])
            ->orderBy('order')
            ->get();

        $menus['footer'] = $footerMenu;
        $menus['legal'] = $legalMenu;
        $menus['main'] = $mainMenu;
        $menus['top'] = $topMenu;

        return array_merge(parent::share($request), [
            'appName' => config('app.name'),
            'contact' => app(ContactSettings::class),
            'socials' => app(SocialsSettings::class),
            'menus' => $menus,
        ]);
    }
}
