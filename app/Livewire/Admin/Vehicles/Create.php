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
    public string $vehicle_category = '';
    public int $passenger_capacity = 4;
    public string $vehicle_condition = '';
    public bool $air_condition = true;
    public string $vehicle_location = '';
    public string $status = 'available';

    protected function rules(): array
    {
        return [
            'supplier_id' => ['required', 'integer', 'exists:suppliers,supplier_id'],
            'vehicle_make' => ['required', 'string', 'max:255'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'vehicle_year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'vehicle_color' => ['required', 'string', 'max:255'],
            'plate_number' => ['required', 'string', 'max:255', 'unique:vehicles,plate_number'],
            'vehicle_category' => ['required', 'string', 'max:255'],
            'passenger_capacity' => ['required', 'integer', 'min:1'],
            'vehicle_condition' => ['required', 'string', 'max:255'],
            'air_condition' => ['required', 'boolean'],
            'vehicle_location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        Vehicle::query()->create($validated);

        $this->redirectRoute('admin.vehicles.index');
    }

    public function render()
    {
        return view('livewire.admin.vehicles.create', [
            'suppliers' => Supplier::query()->orderBy('business_name')->get(['supplier_id', 'business_name']),
        ]);
    }
}
