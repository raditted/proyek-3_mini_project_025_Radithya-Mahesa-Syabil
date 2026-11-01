<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\Distribution;
use App\Models\Report;
use App\Models\School;
use App\Services\ReportWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportReviewController extends Controller
{
    public function __construct(
        protected ReportWorkflowService $workflowService
    ) {}

    // ac-10: search/filter - pencarian deskripsi dan filter status, sekolah, atau distribusi
    // ac-11: pagination - perpindahan halaman mempertahankan filter aktif dengan withquerystring()
    public function index(Request $request): View
    {
        $schools = School::orderBy('name')->get();
        $statuses = ReportStatus::cases();

        $selectedDistribution = null;
        if ($request->filled('distribution_id')) {
            $selectedDistribution = Distribution::with('school')->find($request->distribution_id);
        }

        $reports = Report::query()
            ->with(['school', 'distribution', 'user'])
            ->when(
                $request->filled('school_id'),
                fn($q) =>
                $q->where('school_id', $request->school_id)
            )
            ->when(
                $request->filled('status'),
                fn($q) =>
                $q->where('status', $request->status)
            )
            ->when(
                $request->filled('distribution_id'),
                fn($q) =>
                $q->where('distribution_id', $request->distribution_id)
            )
            ->when(
                $request->filled('q'),
                fn($q) =>
                $q->where('description', 'like', '%' . $request->q . '%')
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('review.index', compact('reports', 'schools', 'statuses', 'selectedDistribution'));
    }

    // ac-06: proses laporan - transisi status dari dikirim menjadi dalam_peninjauan
    // ac-07: authorization - otorisasi policy review hanya mengizinkan pemantau komunitas
    public function startReview(Report $report): RedirectResponse
    {
        $this->authorize('review', $report);

        $this->workflowService->transition(
            $report,
            ReportStatus::DalamPeninjauan,
            auth()->user()
        );

        return back()->with('success', 'Laporan sekarang berstatus Dalam Peninjauan.');
    }

    // ac-06: proses laporan - transisi status ke perlu_dilengkapi dengan catatan pemantau
    public function requestCompletion(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('review', $report);

        $validated = $request->validate([
            'review_note' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'review_note.required' => 'Catatan untuk pelapor wajib diisi.',
        ]);

        $this->workflowService->transition(
            $report,
            ReportStatus::PerluDilengkapi,
            auth()->user(),
            $validated
        );

        return back()->with('success', 'Permintaan pelengkapan data telah dikirimkan ke pelapor.');
    }

    // ac-06: proses laporan - transisi status ke ditandai_untuk_diteruskan dengan rekomendasi pemantau
    public function markForward(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('review', $report);

        $validated = $request->validate([
            'review_note' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'review_note.required' => 'Catatan rekomendasi penerusan wajib diisi.',
        ]);

        $this->workflowService->transition(
            $report,
            ReportStatus::DitandaiUntukDiteruskan,
            auth()->user(),
            $validated
        );

        return back()->with('success', 'Laporan berhasil ditandai untuk diteruskan ke pihak terkait.');
    }

    // ac-06: proses laporan - pemantau menutup laporan dari status dalam_peninjauan
    public function close(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('review', $report);

        $validated = $request->validate([
            'review_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->workflowService->transition(
            $report,
            ReportStatus::Ditutup,
            auth()->user(),
            $validated
        );

        return back()->with('success', 'Penanganan laporan telah ditutup.');
    }
}
