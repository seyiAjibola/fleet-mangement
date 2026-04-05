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
        abort_unless(
            Supplier::query()->visibleTo(auth()->user())->whereKey($validated['supplier_id'])->exists(),
            403
        );
        abort_unless(
            Vehicle::query()->visibleTo(auth()->user())->whereKey($validated['vehicle_id'])->exists(),
            403
        );
        $validated['created_by_user_id'] = auth()->id();

        Driver::query()->create($validated);

        $this->redirectRoute('admin.drivers.index');
    }

    public function render()
    {
        $suppliersQuery = Supplier::query()
            ->visibleTo(auth()->user())
            ->orderBy('business_name');

        $vehiclesQuery = Vehicle::query()
            ->visibleTo(auth()->user())
            ->orderBy('plate_number');

        return view('livewire.admin.drivers.create', [
            'suppliers' => $suppliersQuery->get(['supplier_id', 'business_name']),
            'vehicles' => $vehiclesQuery->get(['vehicle_id', 'plate_number']),
        ]);
    }
}
