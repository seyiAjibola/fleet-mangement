<?php

namespace Database\Seeders;

use App\Models\ComplianceType;
use Illuminate\Database\Seeder;

class ComplianceTypesSeeder extends Seeder
{
    /**
     * Seed sample compliance types for each supported entity.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Vehicle Insurance', 'entity_type' => 'vehicle', 'expiry_required' => true, 'notification_days_before' => 7, 'grace_period_days' => 3, 'is_active' => true],
            ['name' => 'Road Worthiness', 'entity_type' => 'vehicle', 'expiry_required' => true, 'notification_days_before' => 14, 'grace_period_days' => 7, 'is_active' => true],
            ['name' => 'Vehicle License', 'entity_type' => 'vehicle', 'expiry_required' => true, 'notification_days_before' => 14, 'grace_period_days' => 7, 'is_active' => true],
            ['name' => 'Hackney Permit', 'entity_type' => 'vehicle', 'expiry_required' => true, 'notification_days_before' => 14, 'grace_period_days' => 7, 'is_active' => true],
            ['name' => 'Driver License', 'entity_type' => 'driver', 'expiry_required' => true, 'notification_days_before' => 30, 'grace_period_days' => 7, 'is_active' => true],
            ['name' => 'LASDRI Card', 'entity_type' => 'driver', 'expiry_required' => true, 'notification_days_before' => 30, 'grace_period_days' => 7, 'is_active' => true],
            ['name' => 'Medical Fitness Certificate', 'entity_type' => 'driver', 'expiry_required' => true, 'notification_days_before' => 30, 'grace_period_days' => 7, 'is_active' => true],
            ['name' => 'Background Check', 'entity_type' => 'driver', 'expiry_required' => false, 'notification_days_before' => 0, 'grace_period_days' => 0, 'is_active' => true],
            ['name' => 'CAC Certificate', 'entity_type' => 'supplier', 'expiry_required' => false, 'notification_days_before' => 0, 'grace_period_days' => 0, 'is_active' => true],
            ['name' => 'TIN Certificate', 'entity_type' => 'supplier', 'expiry_required' => false, 'notification_days_before' => 0, 'grace_period_days' => 0, 'is_active' => true],
            ['name' => 'Tax Clearance', 'entity_type' => 'supplier', 'expiry_required' => true, 'notification_days_before' => 30, 'grace_period_days' => 7, 'is_active' => true],
            ['name' => 'Insurance Certificate', 'entity_type' => 'supplier', 'expiry_required' => true, 'notification_days_before' => 30, 'grace_period_days' => 7, 'is_active' => true],
        ];

        foreach ($types as $type) {
            ComplianceType::query()->updateOrCreate(
                [
                    'name' => $type['name'],
                    'entity_type' => $type['entity_type'],
                ],
                $type
            );
        }
    }
}
