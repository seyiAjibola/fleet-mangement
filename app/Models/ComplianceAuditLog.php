<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceAuditLog extends Model
{
    protected $fillable = [
        'compliance_record_id',
        'actor_id',
        'action',
        'summary',
        'old_values',
        'new_values',
        'meta',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'meta' => 'array',
    ];

    public function complianceRecord()
    {
        return $this->belongsTo(ComplianceRecord::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
