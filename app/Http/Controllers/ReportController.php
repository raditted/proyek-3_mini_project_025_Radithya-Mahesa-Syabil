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

    public function store(StoreReportRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['status']  = ReportStatus::Dikirim;

        if ($request->hasFile('evidence')) {
            $data['evidence_path'] = $request->file('evidence')->store('evidence', 'local');
        }

        unset($data['evidence']);
        Report::create($data);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(Report $report): View
    {
        $this->authorize('view', $report);

        $report->load(['school', 'distribution', 'forwardings.recorder', 'statusLogs.changedBy']);

        return view('reports.show', compact('report'));
    }

    public function edit(Report $report): View
    {
        $this->authorize('update', $report);

        $schools = School::orderBy('name')->get();

        return view('reports.edit', compact('report', 'schools'));
    }

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
