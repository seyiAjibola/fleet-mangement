<?php

namespace App\Livewire\Admin\Vehicles;

use App\Models\Supplier;
use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Edit Vehicle')]
class Edit extends Component
{
    public Vehicle $vehicle;
    public int $supplier_id = 0;
    public string $vehicle_make = '';
    public string $vehicle_model = '';
    public int $vehicle_year = 2020;
    public string $vehicle_color = '';
    public string $plate_number = '';
    public string $vehicle_category = 'SUV';
    public int $passenger_capacity = 4;
    public string $vehicle_condition = 'standard';
    public string $fuel_type = 'gas';
    public bool $air_condition = true;
    public string $vehicle_location = '';
    public string $status = 'available';

    public function mount(Vehicle $vehicle): void
    {
        abort_unless($vehicle->isVisibleTo(auth()->user()), 403);

        $this->vehicle = $vehicle;
        $this->supplier_id = (int) $vehicle->supplier_id;
        $this->vehicle_make = $vehicle->vehicle_make;
        $this->vehicle_model = $vehicle->vehicle_model;
        $this->vehicle_year = (int) $vehicle->vehicle_year;
        $this->vehicle_color = $vehicle->vehicle_color;
        $this->plate_number = $vehicle->plate_number;
        $this->vehicle_category = $vehicle->vehicle_category;
        $this->passenger_capacity = (int) $vehicle->passenger_capacity;
        $this->vehicle_condition = $vehicle->vehicle_condition;
        $this->fuel_type = $vehicle->fuel_type ?? 'gas';
        $this->air_condition = (bool) $vehicle->air_condition;
        $this->vehicle_location = $vehicle->vehicle_location;
        $this->status = $vehicle->status;
    }

    protected function rules(): array
    {
        return [
            'supplier_id' => ['required', 'integer', 'exists:suppliers,supplier_id'],
            'vehicle_make' => ['required', 'string', 'max:255'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'vehicle_year' => ['required', 'integer', 'min:2010', 'max:2027'],
            'vehicle_color' => ['required', 'string', 'max:255'],
            'plate_number' => ['required', 'string', 'max:255', 'unique:vehicles,plate_number,' . $this->vehicle->vehicle_id . ',vehicle_id'],
            'vehicle_category' => ['required', 'string', 'in:SUV,SEDAN,TRUCK,VAN'],
            'passenger_capacity' => ['required', 'integer', 'min:1'],
            'vehicle_condition' => ['required', 'string', 'in:standard,average,excellent'],
            'fuel_type' => ['required', 'string', 'in:gas,diesel'],
            'air_condition' => ['required', 'boolean'],
            'vehicle_location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:available,unavailable'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        abort_unless(
            Supplier::query()->visibleTo(auth()->user())->whereKey($validated['supplier_id'])->exists(),
            403
        );
        $previousSupplierId = (int) $this->vehicle->supplier_id;

        $this->vehicle->update($validated);
        $this->vehicle->refresh();
        $this->vehicle->supplier?->syncTier();

        if ($previousSupplierId !== (int) $this->vehicle->supplier_id) {
            Supplier::query()->find($previousSupplierId)?->syncTier();
        }

        $this->redirectRoute('admin.vehicles.index');
    }

    public function render()
    {
        $suppliersQuery = Supplier::query()
            ->visibleTo(auth()->user())
            ->orderBy('business_name');

        return view('livewire.admin.vehicles.edit', [
            'suppliers' => $suppliersQuery->get(['supplier_id', 'business_name']),
        ]);
    }
}
