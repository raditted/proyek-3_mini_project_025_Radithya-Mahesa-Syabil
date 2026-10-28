<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Dikirim                  = 'dikirim';
    case DalamPeninjauan          = 'dalam_peninjauan';
    case PerluDilengkapi          = 'perlu_dilengkapi';
    case DitandaiUntukDiteruskan  = 'ditandai_untuk_diteruskan';
    case SudahDiteruskan          = 'sudah_diteruskan';
    case Ditutup                  = 'ditutup';

    /**
     * berikut status yang memperbolehkan pelapor mengedit laporan (BR-01)
     */
    public function isEditable(): bool
    {
        return in_array($this, [self::Dikirim, self::PerluDilengkapi]);
    }

    public function label(): string
    {
        return match ($this) {
            self::Dikirim                 => 'Dikirim',
            self::DalamPeninjauan         => 'Dalam Peninjauan',
            self::PerluDilengkapi         => 'Perlu Dilengkapi',
            self::DitandaiUntukDiteruskan => 'Ditandai untuk Diteruskan',
            self::SudahDiteruskan         => 'Sudah Diteruskan',
            self::Ditutup                 => 'Ditutup',
        };
    }
}
