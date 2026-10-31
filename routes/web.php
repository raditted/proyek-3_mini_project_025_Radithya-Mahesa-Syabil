<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportReviewController;
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

    Route::middleware('role:pelapor')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    });

    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/evidence', [EvidenceController::class, 'show'])->name('reports.evidence');

    Route::middleware('role:pemantau,koordinator')->group(function () {
        Route::get('/review/reports', [ReportReviewController::class, 'index'])->name('review.reports.index');
    });

    Route::middleware('role:pemantau')->group(function () {
        Route::post('/review/reports/{report}/start', [ReportReviewController::class, 'startReview'])->name('review.reports.start');
        Route::post('/review/reports/{report}/request-completion', [ReportReviewController::class, 'requestCompletion'])->name('review.reports.requestCompletion');
        Route::post('/review/reports/{report}/mark-forward', [ReportReviewController::class, 'markForward'])->name('review.reports.markForward');
        Route::post('/review/reports/{report}/close', [ReportReviewController::class, 'close'])->name('review.reports.close');
    });

    Route::middleware('role:koordinator')->group(function () {
        Route::resource('schools', SchoolController::class)->except(['show']);
        Route::resource('distributions', DistributionController::class)->except(['show']);
    });

    Route::get('/schools/{school}/distributions', [DistributionController::class, 'bySchool'])
        ->name('schools.distributions');

    Route::get('/recap', fn() => 'Halaman rekap (Fase 13)')->name('recap.index');
});
