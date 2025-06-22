<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        \Filament\Http\Responses\Auth\Contracts\LoginResponse::class => \App\Http\Responses\LoginResponse::class,
        \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class => \App\Http\Responses\LogoutResponse::class,
    ];

    public function register()
    {
        //
    }

    public function boot()
    {
        // Register model observers
        \App\Models\Kriteria::observe(\App\Observers\KriteriaObserver::class);

        // Add custom CSS for smooth sidebar animation
        \Filament\Support\Facades\FilamentView::registerRenderHook(
            'panels::head.end',
            fn() => new \Illuminate\Support\HtmlString('
                <style>
                    /* Smooth sidebar animation */
                    .fi-sidebar {
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                        transform-origin: left center !important;
                    }
                    
                    .fi-sidebar.fi-sidebar-collapsed {
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                    }
                    
                    .fi-sidebar-nav {
                        transition: opacity 0.2s ease-in-out !important;
                    }
                    
                    .fi-sidebar-collapsed .fi-sidebar-nav {
                        opacity: 0 !important;
                        transition: opacity 0.15s ease-in-out !important;
                    }
                    
                    /* Smooth content transition */
                    .fi-main {
                        transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                    }
                    
                    /* Enhanced sidebar toggle button */
                    .fi-sidebar-close-btn,
                    .fi-sidebar-open-btn {
                        transition: all 0.2s ease-in-out !important;
                    }
                    
                    .fi-sidebar-close-btn:hover,
                    .fi-sidebar-open-btn:hover {
                        transform: scale(1.1) !important;
                        background-color: rgba(0, 0, 0, 0.05) !important;
                    }
                </style>
            ')
        );
    }
}
