<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Bookings\Create;
use App\Livewire\Admin\Bookings\Edit;
use App\Models\CustomerBooking;
use App\Models\Driver;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingConflictTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_booking_rejects_conflicting_vehicle_and_driver_assignments(): void
    {
        $user = User::factory()->create();
        $supplier = $this->createSupplier();
        $vehicle = $this->createVehicle($supplier->supplier_id, 'ABC-123');
        $driver = $this->createDriver($supplier->supplier_id, $vehicle->vehicle_id, 'LIC-001');

        CustomerBooking::query()->create([
            'customer_name' => 'Existing Customer',
            'customer_phone' => '08000000001',
            'pickup_location' => 'Airport',
            'dropoff_location' => 'Hotel',
            'pickup_time' => '2026-04-10 09:00:00',
            'dropoff_time' => '2026-04-10 11:00:00',
            'vehicle_category' => 'SUV',
            'booking_source' => 'phone',
            'assigned_vehicle' => $vehicle->vehicle_id,
            'assigned_driver' => $driver->driver_id,
            'status' => 'confirmed',
        ]);

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('customer_name', 'New Customer')
            ->set('customer_phone', '08000000002')
            ->set('pickup_location', 'Airport')
            ->set('dropoff_location', 'Office')
            ->set('pickup_time', '2026-04-10T10:00')
            ->set('dropoff_time', '2026-04-10T12:00')
            ->set('vehicle_category', 'SUV')
            ->set('booking_source', 'website')
            ->set('assigned_vehicle', $vehicle->vehicle_id)
            ->set('assigned_driver', $driver->driver_id)
            ->set('status', 'pending')
            ->call('save')
            ->assertHasErrors([
                'assigned_vehicle' => [],
                'assigned_driver' => [],
            ]);

        $this->assertSame(1, CustomerBooking::query()->count());
    }

    public function test_edit_booking_rejects_conflicting_vehicle_and_driver_assignments(): void
    {
        $user = User::factory()->create();
        $supplier = $this->createSupplier();
        $vehicleOne = $this->createVehicle($supplier->supplier_id, 'ABC-123');
        $vehicleTwo = $this->createVehicle($supplier->supplier_id, 'XYZ-789');
        $driverOne = $this->createDriver($supplier->supplier_id, $vehicleOne->vehicle_id, 'LIC-001');
        $driverTwo = $this->createDriver($supplier->supplier_id, $vehicleTwo->vehicle_id, 'LIC-002');

        CustomerBooking::query()->create([
            'customer_name' => 'Existing Customer',
            'customer_phone' => '08000000001',
            'pickup_location' => 'Airport',
            'dropoff_location' => 'Hotel',
            'pickup_time' => '2026-04-10 09:00:00',
            'dropoff_time' => '2026-04-10 11:00:00',
            'vehicle_category' => 'SUV',
            'booking_source' => 'phone',
            'assigned_vehicle' => $vehicleOne->vehicle_id,
            'assigned_driver' => $driverOne->driver_id,
            'status' => 'confirmed',
        ]);

        $bookingToEdit = CustomerBooking::query()->create([
            'customer_name' => 'Another Customer',
            'customer_phone' => '08000000003',
            'pickup_location' => 'Office',
            'dropoff_location' => 'Home',
            'pickup_time' => '2026-04-10 12:30:00',
            'dropoff_time' => '2026-04-10 14:00:00',
            'vehicle_category' => 'SUV',
            'booking_source' => 'website',
            'assigned_vehicle' => $vehicleTwo->vehicle_id,
            'assigned_driver' => $driverTwo->driver_id,
            'status' => 'pending',
        ]);

        $this->actingAs($user);

        Livewire::test(Edit::class, ['booking' => $bookingToEdit])
            ->set('pickup_time', '2026-04-10T10:00')
            ->set('dropoff_time', '2026-04-10T12:00')
            ->set('assigned_vehicle', $vehicleOne->vehicle_id)
            ->set('assigned_driver', $driverOne->driver_id)
            ->call('save')
            ->assertHasErrors([
                'assigned_vehicle' => [],
                'assigned_driver' => [],
            ]);

        $bookingToEdit->refresh();

        $this->assertSame($vehicleTwo->vehicle_id, (int) $bookingToEdit->assigned_vehicle);
        $this->assertSame($driverTwo->driver_id, (int) $bookingToEdit->assigned_driver);
    }

    private function createSupplier(): Supplier
    {
        return Supplier::query()->create([
            'business_name' => 'Supplier One',
            'business_type' => 'Fleet',
            'contact_person' => 'Alice',
            'phone_number' => '08000000000',
            'email' => 'supplier@example.com',
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

    private function createDriver(int $supplierId, int $vehicleId, string $licenseNumber): Driver
    {
        return Driver::query()->create([
            'supplier_id' => $supplierId,
            'vehicle_id' => $vehicleId,
            'driver_name' => 'Driver ' . $licenseNumber,
            'phone_number' => '08000000009',
            'license_number' => $licenseNumber,
            'years_experience' => 6,
            'languages' => 'English',
            'professional_experience' => 'Airport transfers',
            'status' => 'active',
        ]);
    }
}
