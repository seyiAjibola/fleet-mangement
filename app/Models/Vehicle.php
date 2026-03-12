<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'supplier_id',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'vehicle_color',
        'plate_number',
        'vehicle_category',
        'passenger_capacity',
        'vehicle_condition',
        'air_condition',
        'vehicle_location',
        'status',
    ];
}
