<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Dashboard;

class RedirectToProperPanelMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->is_admin()) {
                return redirect()->to(Dashboard::getUrl(panel: 'admin'));
            }
        }

        return $next($request);
    }
}
