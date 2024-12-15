<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RestrictAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        // Is authorize check required?
        if (! config('settings.authorized_only')) {
            return $next($request);
        }

        // Is user logged in?
        if (Filament::auth()->user()) {
            return $next($request);
        }

        // Is admin page?
        $path = str_replace(config('app.url'), '', $_SERVER['HTTP_REFERER'] ?? $_SERVER['REQUEST_URI']);
        $adminPath = Filament::getCurrentPanel()->getPath();

        if ($path === '/' . $adminPath || str($path)->startsWith('/' . $adminPath . '/')) {
            return $next($request);
        }

        throw new AccessDeniedHttpException();
    }
}
