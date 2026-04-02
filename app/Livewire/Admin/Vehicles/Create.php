<?php

namespace App\Livewire\Admin\Vehicles;

use App\Models\Supplier;
use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Create Vehicle')]
class Create extends Component
{
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

    protected function rules(): array
    {
        return [
            'supplier_id' => ['required', 'integer', 'exists:suppliers,supplier_id'],
            'vehicle_make' => ['required', 'string', 'max:255'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'vehicle_year' => ['required', 'integer', 'min:2010', 'max:2027'],
            'vehicle_color' => ['required', 'string', 'max:255'],
            'plate_number' => ['required', 'string', 'max:255', 'unique:vehicles,plate_number'],
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

        $vehicle = Vehicle::query()->create($validated);
        session()->flash('open_vehicle_upload_modal', true);

        $this->redirectRoute('admin.vehicles.show', ['vehicle' => $vehicle]);
    }

    public function render()
    {
        return view('livewire.admin.vehicles.create', [
            'suppliers' => Supplier::query()->orderBy('business_name')->get(['supplier_id', 'business_name']),
        ]);
    }
}
