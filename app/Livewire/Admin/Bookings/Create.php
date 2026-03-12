<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\CustomerBooking;
use App\Models\Driver;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Create Booking')]
class Create extends Component
{
    public string $customer_name = '';
    public string $customer_phone = '';
    public string $pickup_location = '';
    public string $dropoff_location = '';
    public string $pickup_time = '';
    public ?string $dropoff_time = null;
    public string $vehicle_category = '';
    public string $booking_source = '';
    public ?int $assigned_vehicle = null;
    public ?int $assigned_driver = null;
    public string $status = 'pending';
    public bool $autoDropoff = true;

    protected function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:255'],
            'pickup_location' => ['required', 'string', 'max:255'],
            'dropoff_location' => ['required', 'string', 'max:255'],
            'pickup_time' => ['required', 'date'],
            'dropoff_time' => ['nullable', 'date', 'after_or_equal:pickup_time'],
            'vehicle_category' => ['required', 'string', 'max:255'],
            'booking_source' => ['required', 'string', 'max:255'],
            'assigned_vehicle' => ['nullable', 'integer', 'exists:vehicles,vehicle_id'],
            'assigned_driver' => ['nullable', 'integer', 'exists:drivers,driver_id'],
            'status' => ['required', 'string', 'in:pending,confirmed,in_transit,completed,cancelled,rejected'],
        ];
    }

    public function save(): void
    {
        if ($this->dropoff_time === null || $this->dropoff_time === '') {
            try {
                $this->dropoff_time = Carbon::parse($this->pickup_time)->addHours(2)->toDateTimeString();
            } catch (\Throwable $e) {
                $this->dropoff_time = null;
            }
        }

        $validated = $this->validate();

        CustomerBooking::query()->create($validated);

        $this->redirectRoute('admin.bookings.index');
    }

    private function activeBookingWindow(): ?array
    {
        if ($this->pickup_time === '') {
            return null;
        }

        try {
            $start = now()->parse($this->pickup_time);
            $end = $this->dropoff_time ? now()->parse($this->dropoff_time) : $start->copy()->addHours(2);
        } catch (\Throwable $e) {
            return null;
        }

        if ($end->lt($start)) {
            [$start, $end] = [$end->copy(), $start->copy()];
        }

        return [$start, $end];
    }

    public function updatedPickupTime(): void
    {
        if (!$this->autoDropoff || $this->pickup_time === '') {
            return;
        }

        try {
            $this->dropoff_time = Carbon::parse($this->pickup_time)->addHours(2)->toDateTimeString();
        } catch (\Throwable $e) {
            $this->dropoff_time = null;
        }
    }

    public function updatedDropoffTime(): void
    {
        $this->autoDropoff = $this->dropoff_time === null || $this->dropoff_time === '';
    }

    public function render()
    {
        $window = $this->activeBookingWindow();
        $activeStatuses = ['pending', 'confirmed', 'in_transit'];

        $blockedVehicleIdsQuery = CustomerBooking::query()
            ->whereIn('status', $activeStatuses)
            ->whereNotNull('assigned_vehicle');

        $blockedDriverIdsQuery = CustomerBooking::query()
            ->whereIn('status', $activeStatuses)
            ->whereNotNull('assigned_driver');

        if ($window) {
            [$start, $end] = $window;

            $blockedVehicleIdsQuery->where('pickup_time', '<=', $end)
                ->whereRaw('COALESCE(dropoff_time, pickup_time) >= ?', [$start]);

            $blockedDriverIdsQuery->where('pickup_time', '<=', $end)
                ->whereRaw('COALESCE(dropoff_time, pickup_time) >= ?', [$start]);
        }

        $blockedVehicleIds = $blockedVehicleIdsQuery
            ->pluck('assigned_vehicle')
            ->unique()
            ->values()
            ->all();

        $blockedDriverIds = $blockedDriverIdsQuery
            ->pluck('assigned_driver')
            ->unique()
            ->values()
            ->all();

        $vehiclesQuery = Vehicle::query()
            ->where('status', 'available')
            ->orderBy('plate_number');

        if ($this->vehicle_category !== '') {
            $vehiclesQuery->where('vehicle_category', $this->vehicle_category);
        }

        if (count($blockedVehicleIds) > 0) {
            $vehiclesQuery->whereNotIn('vehicle_id', $blockedVehicleIds);
        }

        $driversQuery = Driver::query()
            ->where('status', 'active')
            ->orderBy('driver_name');

        if (count($blockedDriverIds) > 0) {
            $driversQuery->whereNotIn('driver_id', $blockedDriverIds);
        }

        return view('livewire.admin.bookings.create', [
            'vehicles' => $vehiclesQuery->get(['vehicle_id', 'plate_number']),
            'drivers' => $driversQuery->get(['driver_id', 'driver_name']),
        ]);
    }
}
