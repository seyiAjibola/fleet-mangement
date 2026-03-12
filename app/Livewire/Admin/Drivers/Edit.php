<?php

namespace App\Livewire\Admin\Drivers;

use App\Models\Driver;
use App\Models\Supplier;
use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Edit Driver')]
class Edit extends Component
{
    public Driver $driver;
    public int $supplier_id = 0;
    public int $vehicle_id = 0;
    public string $driver_name = '';
    public string $phone_number = '';
    public string $license_number = '';
    public int $years_experience = 0;
    public string $languages = '';
    public ?string $professional_experience = null;
    public string $status = 'active';

    public function mount(Driver $driver): void
    {
        $this->driver = $driver;
        $this->supplier_id = (int) $driver->supplier_id;
        $this->vehicle_id = (int) $driver->vehicle_id;
        $this->driver_name = $driver->driver_name;
        $this->phone_number = $driver->phone_number;
        $this->license_number = $driver->license_number;
        $this->years_experience = (int) $driver->years_experience;
        $this->languages = $driver->languages;
        $this->professional_experience = $driver->professional_experience;
        $this->status = $driver->status;
    }

    protected function rules(): array
    {
        return [
            'supplier_id' => ['required', 'integer', 'exists:suppliers,supplier_id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,vehicle_id'],
            'driver_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', 'unique:drivers,license_number,' . $this->driver->driver_id . ',driver_id'],
            'years_experience' => ['required', 'integer', 'min:0'],
            'languages' => ['required', 'string', 'max:255'],
            'professional_experience' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->driver->update($validated);

        $this->redirectRoute('admin.drivers.index');
    }

    public function render()
    {
        return view('livewire.admin.drivers.edit', [
            'suppliers' => Supplier::query()->orderBy('business_name')->get(['supplier_id', 'business_name']),
            'vehicles' => Vehicle::query()->orderBy('plate_number')->get(['vehicle_id', 'plate_number']),
        ]);
    }
}
