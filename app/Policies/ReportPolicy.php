<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * pemilik laporan, pemantau, dan koordinator dibuat boleh melihat detail laporan.
     * ac-05: ownership & privacy - pelapor tidak dapat melihat laporan pengguna lain (akses ditolak 403)
     */
    public function view(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            || $user->role === UserRole::Pemantau
            || $user->role === UserRole::Koordinator;
    }

    /**
     * br-01: pelapor hanya dapat mengubah laporan miliknya saat status dikirim atau perlu_dilengkapi
     * ac-05: ownership & privacy - pelapor tidak dapat mengubah laporan pengguna lain (akses ditolak 403)
     */
    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id
            && $report->status->isEditable();
    }

    /**
     * ac-07: authorization - hanya pemantau yang berwenang meninjau laporan
     */
    public function review(User $user, Report $report): bool
    {
        return $user->role === UserRole::Pemantau;
    }

    /**
     * ac-07: authorization - hanya koordinator yang berwenang mencatat penerusan
     */
    public function forward(User $user, Report $report): bool
    {
        return $user->role === UserRole::Koordinator;
    }

    /**
     * ac-04 & ac-05: unduh bukti privat - hanya pemilik, pemantau, dan koordinator yang diizinkan
     */
    public function downloadEvidence(User $user, Report $report): bool
    {
        return $this->view($user, $report);
    }
}
