<?php

namespace Tests\Feature\Admin;

use App\Models\ComplianceRecord;
use App\Models\ComplianceType;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_page_shows_compliance_sections_and_supplier_ranking(): void
    {
        $admin = User::factory()->admin()->create();

        $type = ComplianceType::query()->create([
            'name' => 'Vehicle Insurance',
            'entity_type' => 'vehicle',
            'expiry_required' => true,
            'notification_days_before' => 3,
            'grace_period_days' => 5,
            'is_active' => true,
        ]);

        $alpha = $this->createSupplier('Alpha Fleet');
        $alphaVehicle = $this->createVehicle($alpha->supplier_id, 'ALP-101');
        ComplianceRecord::query()->create([
            'compliance_type_id' => $type->id,
            'entity_type' => 'vehicle',
            'entity_id' => $alphaVehicle->vehicle_id,
            'document_number' => 'ALPHA-INS',
            'expiry_date' => now()->addDays(10)->toDateString(),
        ]);

        $beta = $this->createSupplier('Beta Fleet');
        $betaVehicle = $this->createVehicle($beta->supplier_id, 'BET-202');
        ComplianceRecord::query()->create([
            'compliance_type_id' => $type->id,
            'entity_type' => 'vehicle',
            'entity_id' => $betaVehicle->vehicle_id,
            'document_number' => 'BETA-INS',
            'expiry_date' => now()->subDays(20)->toDateString(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/reports');

        $response->assertOk();
        $response->assertSeeText('Compliance Summary');
        $response->assertSeeText('Supplier Compliance Ranking');
        $response->assertSeeText('Compliance Exceptions');
        $response->assertSeeTextInOrder(['Alpha Fleet', 'Beta Fleet']);
        $response->assertSeeText('BETA-INS');
    }

    private function createSupplier(string $name): Supplier
    {
        return Supplier::query()->create([
            'business_name' => $name,
            'business_type' => 'Fleet',
            'contact_person' => 'Alice',
            'phone_number' => '08000000000',
            'email' => strtolower(str_replace(' ', '', $name)) . '@example.com',
            'city' => 'Lagos',
            'business_address' => '123 Fleet Street',
            'years_in_business' => 5,
            'status' => 'active',
            'supplier_score' => 80,
            'supplier_tier' => 'gold',
        ]);
    }

    private function createVehicle(int $supplierId, string $plateNumber): Vehicle
    {
        return Vehicle::query()->create([
            'supplier_id' => $supplierId,
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Highlander',
            'vehicle_year' => 2024,
            'vehicle_color' => 'Black',
            'plate_number' => $plateNumber,
            'vehicle_category' => 'SUV',
            'passenger_capacity' => 4,
            'vehicle_condition' => 'excellent',
            'air_condition' => true,
            'vehicle_location' => 'Lagos',
            'status' => 'available',
        ]);
    }
}
