<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecapController extends Controller
{
    public function index(Request $request): View
    {
        $schools  = School::orderBy('name')->get();
        $statuses = ReportStatus::cases();

        // Base query dengan filter
        $baseQuery = Report::query()
            ->when(
                $request->filled('school_id'),
                fn($q) =>
                $q->where('school_id', $request->school_id)
            )
            ->when(
                $request->filled('status'),
                fn($q) =>
                $q->where('status', $request->status)
            );

        // 3 kartu ringkasan
        $totalReports  = (clone $baseQuery)->count();
        $openReports   = (clone $baseQuery)->where('status', '!=', ReportStatus::Ditutup->value)->count();
        $closedReports = (clone $baseQuery)->where('status', ReportStatus::Ditutup->value)->count();

        // Tabel rekap per sekolah
        $schoolRecap = School::query()
            ->withCount([
                'reports as total_reports' => function ($q) use ($request) {
                    $q->when(
                        $request->filled('school_id'),
                        fn($q2) =>
                        $q2->where('school_id', $request->school_id)
                    )->when(
                        $request->filled('status'),
                        fn($q2) =>
                        $q2->where('status', $request->status)
                    );
                },
                'reports as open_reports' => function ($q) use ($request) {
                    $q->where('status', '!=', ReportStatus::Ditutup->value)
                        ->when(
                            $request->filled('school_id'),
                            fn($q2) =>
                            $q2->where('school_id', $request->school_id)
                        )->when($request->filled('status'), fn($q2) =>
                        $q2->where('status', $request->status));
                },
                'reports as closed_reports' => function ($q) use ($request) {
                    $q->where('status', ReportStatus::Ditutup->value)
                        ->when(
                            $request->filled('school_id'),
                            fn($q2) =>
                            $q2->where('school_id', $request->school_id)
                        );
                },
            ])
            ->orderBy('name')
            ->get();

        return view('recap.index', compact(
            'schools',
            'statuses',
            'totalReports',
            'openReports',
            'closedReports',
            'schoolRecap'
        ));
    }
}
