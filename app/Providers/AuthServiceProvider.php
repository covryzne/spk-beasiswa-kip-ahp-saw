<?php

// AuthServiceProvider.php
use App\Models\User;
use App\Policies\FilamentUserPolicy;

class AuthServiceProvider
{
    protected $policies = [
        User::class => FilamentUserPolicy::class,
    ];
}
