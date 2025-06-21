<?php

namespace App\Filament\User\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'data.email' => 'Terlalu banyak percobaan login. Coba lagi dalam ' . ceil($exception->secondsUntilAvailable / 60) . ' menit.',
            ]);
        }

        $data = $this->form->getState();

        // Validate email format first
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'data.email' => 'Format email tidak valid. Silakan masukkan email yang benar.',
            ]);
        }

        // Check if email exists in database
        $user = \App\Models\User::where('email', $data['email'])->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'data.email' => 'Email tidak terdaftar dalam sistem. Periksa kembali email Anda.',
            ]);
        }

        // Try to authenticate
        if (! Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ], $data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.password' => 'Password yang Anda masukkan salah. Silakan coba lagi.',
            ]);
        }

        // After successful login, redirect will be handled by LoginResponse
        return app(LoginResponse::class);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/login.form.email.label'))
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->password()
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    public function getHeading(): string|Htmlable
    {
        return 'Login Sistem SPK Beasiswa KIP';
    }

    public function getSubHeading(): string|Htmlable|null
    {
        return 'Masukkan kredensial Anda untuk mengakses sistem';
    }
}
