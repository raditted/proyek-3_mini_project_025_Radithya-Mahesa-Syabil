<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    // ac-04 & ac-05: unduh berkas bukti privat, pelapor lain ditolak 403
    public function show(Report $report): Response
    {
        $this->authorize('downloadEvidence', $report);

        if (! $report->evidence_path || ! Storage::disk('local')->exists($report->evidence_path)) {
            abort(404, 'File bukti tidak ditemukan.');
        }

        $file     = Storage::disk('local')->get($report->evidence_path);
        $mimeType = Storage::disk('local')->mimeType($report->evidence_path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
