<?php

namespace App\Models;

use App\Models\Concerns\OwnedByUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use OwnedByUser;

    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'created_by_user_id',
        'supplier_id',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'vehicle_color',
        'plate_number',
        'vehicle_category',
        'passenger_capacity',
        'vehicle_condition',
        'fuel_type',
        'air_condition',
        'vehicle_location',
        'status',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class, 'vehicle_id', 'vehicle_id')
            ->orderByDesc('is_primary')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'vehicle_id', 'vehicle_id');
    }
}
