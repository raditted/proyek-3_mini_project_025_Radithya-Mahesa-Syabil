<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * pemilik laporan, pemantau, dan koordinator dibuat boleh melihat detail laporan.
     */
    public function view(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            || $user->role === UserRole::Pemantau
            || $user->role === UserRole::Koordinator;
    }

    /**
     * sistem dibuat hanya pemilik laporan yang boleh mengedit.
     * laporan cuma bisa diedit saat status nya dikirim atau perlu_dilengkapi
     */
    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            && $report->status->isEditable();
    }

    /**
     * hanya si pemantau yang boleh meninjau laporannya
     */
    public function review(User $user, Report $report): bool
    {
        return $user->role === UserRole::Pemantau;
    }

    /**
     * hanya Koordinator yang boleh mencatat penerusan
     */
    public function forward(User $user, Report $report): bool
    {
        return $user->role === UserRole::Koordinator;
    }

    /**
     * hanya pemilik, pemantau, dan koordinator yang boleh mengunduh bukti.
     */
    public function downloadEvidence(User $user, Report $report): bool
    {
        return $this->view($user, $report);
    }
}
