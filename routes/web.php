<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

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

// PDF Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/pdf/data-mahasiswa/{dataMahasiswa}', [PdfController::class, 'dataMahasiswa'])->name('data-mahasiswa.pdf');
    Route::get('/pdf/data-mahasiswa-bulk', [PdfController::class, 'dataMahasiswaBulk'])->name('data-mahasiswa.bulk-pdf');
    Route::get('/pdf/data-mahasiswa-print-all', [PdfController::class, 'dataMahasiswaPrintAll'])->name('data-mahasiswa.print-all');

    Route::get('/pdf/calon-mahasiswa/{calonMahasiswa}', [PdfController::class, 'calonMahasiswa'])->name('calon-mahasiswa.pdf');
    Route::get('/pdf/calon-mahasiswa-bulk', [PdfController::class, 'calonMahasiswaBulk'])->name('calon-mahasiswa.bulk-pdf');
    Route::get('/pdf/calon-mahasiswa-print-all', [PdfController::class, 'calonMahasiswaPrintAll'])->name('calon-mahasiswa.print-all');
});
