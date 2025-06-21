<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllowLoginForAllRoles
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user sudah login dan bukan role 'user', redirect ke panel yang sesuai
        if (Auth::check() && Auth::user()->role !== 'user') {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->is_admin()) {
                return redirect('/admin');
            }
        }

        return $next($request);
    }
}
