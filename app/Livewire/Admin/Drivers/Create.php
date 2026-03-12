<?php

namespace App\Livewire\Admin\Drivers;

use App\Models\Driver;
use App\Models\Supplier;
use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Create Driver')]
class Create extends Component
{
    public int $supplier_id = 0;
    public int $vehicle_id = 0;
    public string $driver_name = '';
    public string $phone_number = '';
    public string $license_number = '';
    public int $years_experience = 0;
    public string $languages = '';
    public ?string $professional_experience = null;
    public string $status = 'active';

    protected function rules(): array
    {
        return [
            'supplier_id' => ['required', 'integer', 'exists:suppliers,supplier_id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,vehicle_id'],
            'driver_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', 'unique:drivers,license_number'],
            'years_experience' => ['required', 'integer', 'min:0'],
            'languages' => ['required', 'string', 'max:255'],
            'professional_experience' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        Driver::query()->create($validated);

        $this->redirectRoute('admin.drivers.index');
    }

    public function render()
    {
        return view('livewire.admin.drivers.create', [
            'suppliers' => Supplier::query()->orderBy('business_name')->get(['supplier_id', 'business_name']),
            'vehicles' => Vehicle::query()->orderBy('plate_number')->get(['vehicle_id', 'plate_number']),
        ]);
    }
}
