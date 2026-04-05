<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\CustomerBooking;
use App\Models\Driver;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Edit Booking')]
class Edit extends Component
{
    private static ?bool $hasDropoffTimeColumn = null;

    public CustomerBooking $booking;
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

    public function mount(CustomerBooking $booking): void
    {
        abort_unless($booking->isVisibleTo(auth()->user()), 403);

        $this->booking = $booking;
        $this->customer_name = $booking->customer_name;
        $this->customer_phone = $booking->customer_phone;
        $this->pickup_location = $booking->pickup_location;
        $this->dropoff_location = $booking->dropoff_location;
        $this->pickup_time = (string) $booking->pickup_time;
        $this->dropoff_time = $booking->dropoff_time ? (string) $booking->dropoff_time : null;
        $this->autoDropoff = $this->dropoff_time === null;
        $this->vehicle_category = $booking->vehicle_category;
        $this->booking_source = $booking->booking_source;
        $this->assigned_vehicle = $booking->assigned_vehicle ? (int) $booking->assigned_vehicle : null;
        $this->assigned_driver = $booking->assigned_driver ? (int) $booking->assigned_driver : null;
        $this->status = $booking->status;
    }

    protected function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:255'],
            'pickup_location' => ['required', 'string', 'max:255'],
            'dropoff_location' => ['required', 'string', 'max:255'],
            'pickup_time' => ['required', 'date'],
            'dropoff_time' => ['required', 'date', 'after_or_equal:pickup_time'],
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
        abort_unless(
            ! $validated['assigned_vehicle']
                || Vehicle::query()->visibleTo(auth()->user())->whereKey($validated['assigned_vehicle'])->exists(),
            403
        );
        abort_unless(
            ! $validated['assigned_driver']
                || Driver::query()->visibleTo(auth()->user())->whereKey($validated['assigned_driver'])->exists(),
            403
        );
        $this->ensureAssignmentsAreAvailable($validated['assigned_vehicle'], $validated['assigned_driver']);

        $this->booking->update($validated);

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

    private function ensureAssignmentsAreAvailable(?int $vehicleId, ?int $driverId): void
    {
        $window = $this->activeBookingWindow();

        if (! $window || (! $vehicleId && ! $driverId)) {
            return;
        }

        [$start, $end] = $window;
        $activeStatuses = ['pending', 'confirmed', 'in_transit'];
        $conflicts = CustomerBooking::query()
            ->whereIn('status', $activeStatuses)
            ->whereKeyNot($this->booking->getKey())
            ->where(function ($query) use ($vehicleId, $driverId) {
                if ($vehicleId) {
                    $query->orWhere('assigned_vehicle', $vehicleId);
                }

                if ($driverId) {
                    $query->orWhere('assigned_driver', $driverId);
                }
            })
            ->tap(fn (Builder $query) => $this->applyOverlapWindow($query, $start, $end))
            ->get(['assigned_vehicle', 'assigned_driver']);

        $errors = [];

        if ($vehicleId && $conflicts->contains('assigned_vehicle', $vehicleId)) {
            $errors['assigned_vehicle'] = 'The selected vehicle is already booked for this time window.';
        }

        if ($driverId && $conflicts->contains('assigned_driver', $driverId)) {
            $errors['assigned_driver'] = 'The selected driver is already booked for this time window.';
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function applyOverlapWindow(Builder $query, Carbon $start, Carbon $end): void
    {
        $query->where('pickup_time', '<=', $end);

        if ($this->hasDropoffTimeColumn()) {
            $query->whereRaw('COALESCE(dropoff_time, pickup_time) >= ?', [$start]);

            return;
        }

        $query->where('pickup_time', '>=', $start);
    }

    private function hasDropoffTimeColumn(): bool
    {
        if (self::$hasDropoffTimeColumn === null) {
            self::$hasDropoffTimeColumn = Schema::hasColumn('customer_bookings', 'dropoff_time');
        }

        return self::$hasDropoffTimeColumn;
    }

    public function render()
    {
        $window = $this->activeBookingWindow();
        $activeStatuses = ['pending', 'confirmed', 'in_transit'];

        $blockedVehicleIdsQuery = CustomerBooking::query()
            ->whereIn('status', $activeStatuses)
            ->whereNotNull('assigned_vehicle')
            ->whereKeyNot($this->booking->getKey());

        $blockedDriverIdsQuery = CustomerBooking::query()
            ->whereIn('status', $activeStatuses)
            ->whereNotNull('assigned_driver')
            ->whereKeyNot($this->booking->getKey());

        if ($window) {
            [$start, $end] = $window;

            $this->applyOverlapWindow($blockedVehicleIdsQuery, $start, $end);
            $this->applyOverlapWindow($blockedDriverIdsQuery, $start, $end);
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
            ->visibleTo(auth()->user())
            ->where('status', 'available')
            ->orderBy('plate_number');

        if ($this->vehicle_category !== '') {
            $vehiclesQuery->where('vehicle_category', $this->vehicle_category);
        }

        if (count($blockedVehicleIds) > 0) {
            $vehiclesQuery->whereNotIn('vehicle_id', $blockedVehicleIds);
        }

        $driversQuery = Driver::query()
            ->visibleTo(auth()->user())
            ->where('status', 'active')
            ->orderBy('driver_name');

        if (count($blockedDriverIds) > 0) {
            $driversQuery->whereNotIn('driver_id', $blockedDriverIds);
        }

        return view('livewire.admin.bookings.edit', [
            'vehicles' => $vehiclesQuery->get(['vehicle_id', 'plate_number']),
            'drivers' => $driversQuery->get(['driver_id', 'driver_name']),
        ]);
    }
}
