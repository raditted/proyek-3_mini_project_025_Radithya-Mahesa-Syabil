<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Http\Requests\StoreForwardingRequest;
use App\Models\Report;
use App\Models\ReportForwarding;
use App\Services\ReportWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportForwardingController extends Controller
{
    public function __construct(
        protected ReportWorkflowService $workflowService
    ) {}

    public function store(StoreForwardingRequest $request, Report $report): RedirectResponse
    {
        $this->authorize('forward', $report);

        if ($report->status !== ReportStatus::DitandaiUntukDiteruskan) {
            return back()->with('error', 'Hanya laporan berstatus "Ditandai untuk Diteruskan" yang dapat dicatat penerimaannya. (BR-06)');
        }

        DB::transaction(function () use ($request, $report) {
            ReportForwarding::create([
                'report_id' => $report->id,
                'recipient_type' => $request->recipient_type,
                'recipient_name' => $request->recipient_name,
                'note' => $request->note,
                'recorded_by' => auth()->id(),
                'forwarded_at' => now(),
            ]);

            $this->workflowService->transition(
                $report,
                ReportStatus::SudahDiteruskan,
                auth()->user()
            );
        });

        return back()->with('success', 'Penerusan informasi berhasil dicatat. Status laporan diperbarui.');
    }

    public function close(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('forward', $report);

        $this->workflowService->transition(
            $report,
            ReportStatus::Ditutup,
            auth()->user()
        );

        return back()->with('success', 'Laporan telah ditutup.');
    }
}
