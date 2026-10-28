<?php

namespace App\Models;

use App\Enums\ReportStatus;
use App\Observers\ReportObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(ReportObserver::class)]
class Report extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'distribution_id',
        'incident_at',
        'description',
        'affected_count',
        'evidence_path',
        'status',
        'review_note',
    ];

    protected function casts(): array
    {
        return [
            'incident_at' => 'datetime',
            'status'      => ReportStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(Distribution::class);
    }

    public function forwardings(): HasMany
    {
        return $this->hasMany(ReportForwarding::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ReportStatusLog::class);
    }
}
