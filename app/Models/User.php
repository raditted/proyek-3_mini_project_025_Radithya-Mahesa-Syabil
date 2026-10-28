<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    // ini untuk bagian relasional
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
    public function reportStatusLogs(): HasMany
    {
        return $this->hasMany(ReportStatusLog::class, 'changed_by');
    }
    public function reportForwardings(): HasMany
    {
        return $this->hasMany(ReportForwarding::class, 'recorded_by');
    }
    // sistem untuk membantu pengecekan role
    public function isKoordinator(): bool
    {
        return $this->role === UserRole::Koordinator;
    }
    public function isPemantau(): bool
    {
        return $this->role === UserRole::Pemantau;
    }
    public function isPelapor(): bool
    {
        return $this->role === UserRole::Pelapor;
    }
}
