<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportForwardingController;
use App\Http\Controllers\ReportReviewController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\RecapController;
use Illuminate\Support\Facades\Route;

// ac-01: authentication - guest diarahkan ke login, route login dan proses autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ac-01: authentication - protected function membutuhkan autentikasi (login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route(
            match (auth()->user()->role) {
                \App\Enums\UserRole::Koordinator => 'schools.index',
                \App\Enums\UserRole::Pemantau => 'review.reports.index',
                default => 'reports.index',
            }
        );
    });

    // ac-02 & ac-07: fungsi pelaporan dan otorisasi role pelapor
    Route::middleware('role:pelapor')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    });

    // ac-05: detail laporan dan bukti dilindungi policy untuk ownership dan privacy
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/evidence', [EvidenceController::class, 'show'])->name('reports.evidence');

    // ac-07 & ac-10: otorisasi role pemantau dan koordinator untuk meninjau laporan dan filter
    Route::middleware('role:pemantau,koordinator')->group(function () {
        Route::get('/review/reports', [ReportReviewController::class, 'index'])->name('review.reports.index');
    });

    // ac-06 & ac-07: alur proses laporan dan otorisasi wewenang khusus pemantau komunitas
    Route::middleware('role:pemantau')->group(function () {
        Route::post('/review/reports/{report}/start', [ReportReviewController::class, 'startReview'])->name('review.reports.start');
        Route::post('/review/reports/{report}/request-completion', [ReportReviewController::class, 'requestCompletion'])->name('review.reports.requestCompletion');
        Route::post('/review/reports/{report}/mark-forward', [ReportReviewController::class, 'markForward'])->name('review.reports.markForward');
        Route::post('/review/reports/{report}/close', [ReportReviewController::class, 'close'])->name('review.reports.close');
    });

    // ac-07 & ac-08: otorisasi wewenang koordinator untuk mencatat penerusan (br-06) dan menutup laporan
    Route::middleware('role:koordinator')->group(function () {
        Route::post('/reports/{report}/forwardings', [ReportForwardingController::class, 'store'])->name('reports.forwardings.store');
        Route::post('/reports/{report}/close', [ReportForwardingController::class, 'close'])->name('reports.close');

        Route::resource('schools', SchoolController::class)->except(['show']);
        Route::resource('distributions', DistributionController::class)->except(['show']);
    });

    // ac-09: asynchronous interaction - endpoint distribusi per sekolah untuk fetch() tanpa reload
    Route::get('/schools/{school}/distributions', [DistributionController::class, 'bySchool'])
        ->name('schools.distributions');

    // ac-13: rekap sederhana - otorisasi akses halaman rekap untuk pemantau dan koordinator
    Route::middleware('role:pemantau,koordinator')->group(function () {
        Route::get('/recap', [\App\Http\Controllers\RecapController::class, 'index'])->name('recap.index');
    });
});
