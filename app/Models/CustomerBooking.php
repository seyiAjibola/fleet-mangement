<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerBooking extends Model
{
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'pickup_location',
        'dropoff_location',
        'pickup_time',
        'dropoff_time',
        'vehicle_category',
        'booking_source',
        'assigned_vehicle',
        'assigned_driver',
        'status',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'assigned_vehicle', 'vehicle_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'assigned_driver', 'driver_id');
    }
}
