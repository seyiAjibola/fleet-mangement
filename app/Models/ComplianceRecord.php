<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Carbon\Carbon;

class ComplianceRecord extends Model
{
    protected static function booted(): void
    {
        static::saving(function (self $record): void {
            $record->status = $record->calculateStatus();
        });
    }

    protected $fillable = [
        'compliance_type_id',
        'entity_type',
        'entity_id',
        'document_number',
        'issued_date',
        'expiry_date',
        'status',
        'last_notified_at',
        'resolved_at',
        'created_by',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
        'last_notified_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function complianceType()
    {
        return $this->belongsTo(ComplianceType::class);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function documents()
    {
        return $this->hasMany(ComplianceDocument::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notificationLogs()
    {
        return $this->hasMany(ComplianceNotificationLog::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(ComplianceAuditLog::class)->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Status Logic
    |--------------------------------------------------------------------------
    */

    public function calculateStatus(): string
    {
        $type = $this->relationLoaded('complianceType')
            ? $this->complianceType
            : $this->complianceType()->first();

        if (! $type || ! $this->expiry_date || ! $type->expiry_required) {
            return 'valid';
        }

        $today = now();
        $expiry = Carbon::parse($this->expiry_date);

        $notifyBefore = $type->notification_days_before;
        $grace = $type->grace_period_days;

        if ($today->lt($expiry->copy()->subDays($notifyBefore))) {
            return 'valid';
        }

        if ($today->between(
            $expiry->copy()->subDays($notifyBefore),
            $expiry
        )) {
            return 'expiring';
        }

        if ($today->gt($expiry) && $today->lte($expiry->copy()->addDays($grace))) {
            return 'expired';
        }

        return 'non_compliant';
    }

    public function refreshStatus(): void
    {
        $status = $this->calculateStatus();

        if ($this->status === $status) {
            return;
        }

        $this->forceFill(['status' => $status])->saveQuietly();
        $this->status = $status;
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
