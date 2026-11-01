<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReportController extends Controller
{
    // ac-10: search/filter - filter status laporan milik pelapor
    // ac-11: pagination - mempertahankan filter saat pindah halaman (withquerystring)
    public function index(Request $request): View
    {
        $reports = Report::query()
            ->where('user_id', auth()->id())
            ->with(['school', 'distribution'])
            ->when(
                $request->filled('status'),
                fn($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statuses = ReportStatus::cases();

        return view('reports.index', compact('reports', 'statuses'));
    }

    public function create(): View
    {
        $schools = School::orderBy('name')->get();
        return view('reports.create', compact('schools'));
    }

    // ac-02: pelaporan - pelapor membuat laporan dengan data valid terkait sekolah dan distribusi
    public function store(StoreReportRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['status']  = ReportStatus::Dikirim;

        // ac-04: simpan bukti pendukung pada media storage privat
        if ($request->hasFile('evidence')) {
            $data['evidence_path'] = $request->file('evidence')->store('evidence', 'local');
        }

        unset($data['evidence']);
        Report::create($data);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }

    // ac-05: ownership & privacy - otorisasi policy view memastikan pelapor hanya melihat laporannya sendiri
    public function show(Report $report): View
    {
        $this->authorize('view', $report);

        $report->load(['school', 'distribution', 'forwardings.recorder', 'statusLogs.changedBy']);

        return view('reports.show', compact('report'));
    }

    // br-01: pelapor hanya dapat mengubah laporan miliknya saat status dikirim atau perlu_dilengkapi
    // ac-05: ownership & privacy - otorisasi policy update menolak pelapor lain (403)
    public function edit(Report $report): View
    {
        $this->authorize('update', $report);

        $schools = School::orderBy('name')->get();

        return view('reports.edit', compact('report', 'schools'));
    }

    // br-01 & ac-05: pembaruan laporan hanya diizinkan untuk pemilik pada status yang valid
    public function update(UpdateReportRequest $request, Report $report): RedirectResponse
    {
        $this->authorize('update', $report);

        $data = $request->validated();

        if ($request->hasFile('evidence')) {
            if ($report->evidence_path) {
                Storage::disk('local')->delete($report->evidence_path);
            }
            $data['evidence_path'] = $request->file('evidence')->store('evidence', 'local');
        }

        unset($data['evidence']);

        if ($report->status === ReportStatus::PerluDilengkapi) {
            $data['status'] = ReportStatus::Dikirim;
        }

        $report->update($data);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui.');
    }
}
