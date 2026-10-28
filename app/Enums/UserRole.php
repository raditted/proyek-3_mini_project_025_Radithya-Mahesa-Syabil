<?php

namespace App\Enums;

enum UserRole: string
{
    case Pelapor     = 'pelapor';
    case Pemantau    = 'pemantau';
    case Koordinator = 'koordinator';
}
