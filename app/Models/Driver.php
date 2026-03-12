<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $primaryKey = 'driver_id';

    protected $fillable = [
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
}
