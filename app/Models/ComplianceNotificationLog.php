<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceNotificationLog extends Model
{
    protected $fillable = [
        'compliance_record_id',
        'user_id',
        'notification_type',
        'status_snapshot',
        'context_key',
        'channels',
        'notified_at',
    ];

    protected $casts = [
        'channels' => 'array',
        'notified_at' => 'datetime',
    ];

    public function complianceRecord()
    {
        return $this->belongsTo(ComplianceRecord::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
