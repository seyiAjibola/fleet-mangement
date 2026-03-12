<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
