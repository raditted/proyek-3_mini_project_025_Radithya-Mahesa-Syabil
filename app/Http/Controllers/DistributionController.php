<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributionRequest;
use App\Http\Requests\UpdateDistributionRequest;
use App\Models\Distribution;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DistributionController extends Controller
{
    public function index(Request $request): View
    {
        $schools = School::orderBy('name')->get();

        $distributions = Distribution::query()
            ->with('school')
            ->when(
                $request->filled('school_id'),
                fn($query) =>
                $query->where('school_id', $request->school_id)
            )
            ->when(
                $request->filled('q'),
                fn($query) =>
                $query->where('menu_description', 'like', '%' . $request->q . '%')
            )
            ->latest('distribution_date')
            ->paginate(10)
            ->withQueryString();

        return view('distributions.index', compact('distributions', 'schools'));
    }

    public function create(): View
    {
        $schools = School::orderBy('name')->get();
        return view('distributions.create', compact('schools'));
    }

    public function store(StoreDistributionRequest $request): RedirectResponse
    {
        Distribution::create($request->validated());

        return redirect()->route('distributions.index')
            ->with('success', 'Distribusi berhasil ditambahkan.');
    }

    public function edit(Distribution $distribution): View
    {
        $schools = School::orderBy('name')->get();
        return view('distributions.edit', compact('distribution', 'schools'));
    }

    public function update(UpdateDistributionRequest $request, Distribution $distribution): RedirectResponse
    {
        $distribution->update($request->validated());

        return redirect()->route('distributions.index')
            ->with('success', 'Data distribusi berhasil diperbarui.');
    }

    public function destroy(Distribution $distribution): RedirectResponse
    {
        if ($distribution->reports()->exists()) {
            return back()->with('error', 'Distribusi tidak dapat dihapus karena sudah memiliki laporan terkait.');
        }

        $distribution->delete();

        return redirect()->route('distributions.index')
            ->with('success', 'Distribusi berhasil dihapus.');
    }

    /**
     * endpoint JSON buat fetch distribusi per sekolah (nanti).
     * dipakainya oleh form laporan saat memilih sekolah.
     */
    public function bySchool(School $school): JsonResponse
    {
        $distributions = $school->distributions()
            ->orderBy('distribution_date', 'desc')
            ->get(['id', 'distribution_date', 'menu_description']);

        return response()->json($distributions);
    }
}
