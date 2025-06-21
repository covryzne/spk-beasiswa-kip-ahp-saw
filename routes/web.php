<?php

use Illuminate\Support\Facades\Route;

// Root redirect langsung ke user login (single login entry point)
Route::get('/', function () {
    return redirect('/user/login');
});

// Redirect `/login` ke user panel login (yang akan handle semua role)
Route::get('/login', function () {
    return redirect('/user/login');
});

// Block access to admin/login - redirect to user/login instead
Route::get('/admin/login', function () {
    return redirect('/user/login');
});
