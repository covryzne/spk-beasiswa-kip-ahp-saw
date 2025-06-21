<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->role === 'admin',
            'user' => $this->role === 'user',
            default => false,
        };
    }

    // Tambah buat cek admin (buat LoginResponse)
    public function is_admin(): bool
    {
        return $this->role === 'admin';
    }

    // Getter property untuk compatibility
    public function getIsAdminAttribute(): bool
    {
        return $this->is_admin();
    }
}
