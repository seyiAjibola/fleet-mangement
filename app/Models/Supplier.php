<?php

namespace App\Models;

use App\Models\Concerns\OwnedByUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Supplier extends Model
{
    use OwnedByUser;

    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'created_by_user_id',
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

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'supplier_id', 'supplier_id');
    }

    public function complianceRecords(): MorphMany
    {
        return $this->morphMany(ComplianceRecord::class, 'entity');
    }

    public function determineScore(?int $vehicleCount = null): int
    {
        $fleetSize = $vehicleCount ?? $this->vehicles()->count();
        $score = 0;

        if ($this->status === 'active') {
            $score += 20;
        }

        if (filled($this->cac_no)) {
            $score += 15;
        }

        if (filled($this->tin)) {
            $score += 15;
        }

        if (filled($this->website)) {
            $score += 10;
        }

        if (filled($this->instagram_page)) {
            $score += 5;
        }

        if ($this->years_in_business >= 10) {
            $score += 20;
        } elseif ($this->years_in_business >= 5) {
            $score += 15;
        } elseif ($this->years_in_business >= 2) {
            $score += 10;
        } elseif ($this->years_in_business >= 1) {
            $score += 5;
        }

        if ($fleetSize >= 10) {
            $score += 15;
        } elseif ($fleetSize >= 5) {
            $score += 10;
        } elseif ($fleetSize >= 3) {
            $score += 8;
        } elseif ($fleetSize >= 1) {
            $score += 5;
        }

        return min(100, $score);
    }

    public function determineTier(?int $vehicleCount = null): string
    {
        $fleetSize = $vehicleCount ?? $this->vehicles()->count();
        $score = $this->determineScore($vehicleCount);
        $hasCac = filled($this->cac_no);
        $hasTin = filled($this->tin);
        $isActive = $this->status === 'active';

        if (
            $isActive
            && $score >= 80
            && $this->years_in_business >= 5
            && $hasCac
            && $hasTin
            && $fleetSize >= 3
        ) {
            return 'gold';
        }

        if (
            $isActive
            && $score >= 50
            && $this->years_in_business >= 2
            && ($hasCac || $hasTin)
        ) {
            return 'silver';
        }

        return 'bronze';
    }

    public function syncTier(?int $vehicleCount = null): void
    {
        $score = $this->determineScore($vehicleCount);
        $tier = $this->determineTier($vehicleCount);

        if ($this->supplier_tier !== $tier || (int) $this->supplier_score !== $score) {
            $this->forceFill([
                'supplier_score' => $score,
                'supplier_tier' => $tier,
            ])->saveQuietly();
        }
    }
}
