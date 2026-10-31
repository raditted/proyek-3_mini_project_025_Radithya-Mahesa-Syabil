<?php

namespace App\Services;

use App\Enums\ReportStatus;
use App\Enums\UserRole;
use App\Models\Report;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ReportWorkflowService
{
    protected static array $transitions = [
        'dikirim' => [
            'dalam_peninjauan' => [UserRole::Pemantau],
        ],
        'dalam_peninjauan' => [
            'perlu_dilengkapi' => [UserRole::Pemantau],
            'ditandai_untuk_diteruskan' => [UserRole::Pemantau],
            'ditutup' => [UserRole::Pemantau],
        ],
        'perlu_dilengkapi' => [
            'dikirim' => [UserRole::Pelapor],
        ],
        'ditandai_untuk_diteruskan' => [
            'sudah_diteruskan' => [UserRole::Koordinator],
        ],
        'sudah_diteruskan' => [
            'ditutup' => [UserRole::Koordinator],
        ],
    ];

    public function transition(Report $report, ReportStatus $to, User $actor, array $data = []): Report
    {
        $fromStatus = $report->status->value;
        $toStatus   = $to->value;

        if (! isset(self::$transitions[$fromStatus][$toStatus])) {
            throw ValidationException::withMessages([
                'status' => "Transisi status dari '{$report->status->label()}' ke '{$to->label()}' tidak diperbolehkan.",
            ]);
        }

        $allowedRoles = self::$transitions[$fromStatus][$toStatus];
        if (! in_array($actor->role, $allowedRoles)) {
            throw ValidationException::withMessages([
                'status' => "Peran Anda ({$actor->role->value}) tidak memiliki wewenang untuk mengubah status ini.",
            ]);
        }

        if ($actor->role === UserRole::Pelapor && $report->user_id !== $actor->id) {
            throw ValidationException::withMessages([
                'status' => "Hanya pemilik laporan yang dapat memperbarui laporan ini.",
            ]);
        }

        if (in_array($to, [ReportStatus::PerluDilengkapi, ReportStatus::DitandaiUntukDiteruskan])) {
            if (empty($data['review_note'])) {
                throw ValidationException::withMessages([
                    'review_note' => "Catatan pemantau wajib diisi untuk tindakan ini.",
                ]);
            }
            $report->review_note = $data['review_note'];
        }

        $report->status = $to;
        $report->save();

        return $report;
    }
}
