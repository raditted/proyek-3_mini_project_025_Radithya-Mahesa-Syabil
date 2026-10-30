<?php

use App\Http\Controllers\AuthController;
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

    Route::get('/reports', fn() => 'Halaman laporan saya (Fase 5)')->name('reports.index');
    Route::get('/review/reports', fn() => 'Halaman review (Fase 8)')->name('review.reports.index');
    Route::get('/schools', fn() => 'Halaman sekolah (Fase 4)')->name('schools.index');
});
