<?php

namespace App\Models;

use App\Models\Concerns\OwnedByUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Driver extends Model
{
    use OwnedByUser;

    protected $primaryKey = 'driver_id';

    protected $fillable = [
        'created_by_user_id',
        'supplier_id',
        'vehicle_id',
        'driver_name',
        'phone_number',
        'license_number',
        'years_experience',
        'languages',
        'professional_experience',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Driver $driver): void {
            if (blank($driver->license_number)) {
                $driver->license_number = 'compliance-' . Str::uuid();
            }
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function complianceRecords()
    {
        return $this->morphMany(ComplianceRecord::class, 'entity');
    }
}
