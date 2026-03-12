<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'business_name',
        'business_type',
        'contact_person',
        'phone_number',
        'email',
        'city',
        'business_address',
        'years_in_business',
        'instagram_page',
        'website',
        'status',
        'supplier_score',
        'supplier_tier',
    ];
}
