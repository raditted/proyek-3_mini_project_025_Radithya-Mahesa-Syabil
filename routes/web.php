<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route(
            match (auth()->user()->role) {
                \App\Enums\UserRole::Koordinator => 'schools.index',
                \App\Enums\UserRole::Pemantau    => 'review.reports.index',
                default                          => 'reports.index',
            }
        );
    });

    Route::get('/reports', fn() => 'Halaman laporan saya (nanti fase 5)')->name('reports.index');
    Route::get('/reports/create', fn() => 'Form buat laporan (Fase 5)')->name('reports.create');
    Route::get('/review/reports', fn() => 'Halaman review (Fase 8)')->name('review.reports.index');
    Route::get('/recap', fn() => 'Halaman rekap (Fase 13)')->name('recap.index');

    Route::middleware('role:koordinator')->group(function () {
        Route::resource('schools', SchoolController::class)->except(['show']);
        Route::resource('distributions', DistributionController::class)->except(['show']);
    });

    Route::get('/schools/{school}/distributions', [DistributionController::class, 'bySchool'])
        ->name('schools.distributions');
});
