<?php

namespace App\Models;

use App\Support\Compliance\ComplianceEntityMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ComplianceType extends Model
{
    protected $fillable = [
        'name',
        'entity_type',
        'expiry_required',
        'notification_days_before',
        'grace_period_days',
        'is_active',
    ];

    protected $casts = [
        'expiry_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function records()
    {
        return $this->hasMany(ComplianceRecord::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEntity(Builder $query, string $entityType): Builder
    {
        return $query->where('entity_type', ComplianceEntityMap::aliasFor($entityType));
    }
}
