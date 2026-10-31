<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\ReportStatusLog;

class ReportObserver
{
    /**
     *  observer ini mencatat status awal saat laporan baru dibuat.
     */
    public function created(Report $report): void
    {
        ReportStatusLog::create([
            'report_id'   => $report->id,
            'from_status' => null,
            'to_status'   => $report->status->value,
            'changed_by'  => auth()->id(),
            'changed_at'  => now(),
        ]);
    }

    /**
     * catat perubahan status saat laporan di-update.
     * cuma dicatat jika kolom status benar-benar berubah
     */
    public function updated(Report $report): void
    {
        if (! $report->wasChanged('status')) {
            return;
        }

        ReportStatusLog::create([
            'report_id'   => $report->id,
            'from_status' => $report->getOriginal('status'),
            'to_status'   => $report->status->value,
            'changed_by'  => auth()->id(),
            'changed_at'  => now(),
        ]);
    }
}
