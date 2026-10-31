<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function index(Request $request): View
    {
        $schools = School::query()
            ->when(
                $request->filled('q'),
                fn($query) =>
                $query->where('name', 'like', '%' . $request->q . '%')
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('schools.index', compact('schools'));
    }

    public function create(): View
    {
        return view('schools.create');
    }

    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        School::create($request->validated());

        return redirect()->route('schools.index')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function edit(School $school): View
    {
        return view('schools.edit', compact('school'));
    }

    public function update(UpdateSchoolRequest $request, School $school): RedirectResponse
    {
        $school->update($request->validated());

        return redirect()->route('schools.index')
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(School $school): RedirectResponse
    {
        if ($school->reports()->exists()) {
            return back()->with('error', 'Sekolah tidak dapat dihapus karena sudah memiliki laporan terkait.');
        }

        if ($school->distributions()->exists()) {
            return back()->with('error', 'Sekolah tidak dapat dihapus karena masih memiliki distribusi. Hapus distribusi terlebih dahulu.');
        }

        $school->delete();

        return redirect()->route('schools.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }
}
