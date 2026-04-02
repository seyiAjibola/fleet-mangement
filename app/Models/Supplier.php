<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'business_name',
        'business_type',
        'contact_person',
        'phone_number',
        'cac_no',
        'tin',
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

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'supplier_id', 'supplier_id');
    }
}
