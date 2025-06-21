<?php

namespace App\Http\Responses;

use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;

class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Jika admin, redirect ke admin panel
        if ($user->is_admin()) {
            return redirect()->intended(Filament::getPanel('admin')->getUrl());
        }

        // Jika user biasa, redirect ke user panel
        return redirect()->intended(Filament::getPanel('user')->getUrl());
    }
}
