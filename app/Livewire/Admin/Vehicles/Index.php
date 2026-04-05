<?php

namespace App\Livewire\Admin\Vehicles;

use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Admin - Vehicles')]
class Index extends Component
{
    use WithPagination;

    public string $vehicleType = '';
    public string $vehicleMake = '';
    public string $vehicleModel = '';
    public string $vehicleCondition = '';
    public string $plateNumber = '';
    public string $year = '';
    public string $fuelType = '';
    public string $vehicleColor = '';
    public string $status = '';

    protected $queryString = [
        'vehicleType' => ['except' => ''],
        'vehicleMake' => ['except' => ''],
        'vehicleModel' => ['except' => ''],
        'vehicleCondition' => ['except' => ''],
        'plateNumber' => ['except' => ''],
        'year' => ['except' => ''],
        'fuelType' => ['except' => ''],
        'vehicleColor' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatedVehicleType(): void
    {
        $this->resetPage();
    }

    public function updatedVehicleMake(): void
    {
        $this->resetPage();
    }

    public function updatedVehicleModel(): void
    {
        $this->resetPage();
    }

    public function updatedVehicleCondition(): void
    {
        $this->resetPage();
    }

    public function updatedPlateNumber(): void
    {
        $this->resetPage();
    }

    public function updatedYear(): void
    {
        $this->resetPage();
    }

    public function updatedFuelType(): void
    {
        $this->resetPage();
    }

    public function updatedVehicleColor(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function applyFilters(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'vehicleType',
            'vehicleMake',
            'vehicleModel',
            'vehicleCondition',
            'plateNumber',
            'year',
            'fuelType',
            'vehicleColor',
            'status',
        ]);

        $this->resetPage();
    }

    public function delete(int $id): void
    {
        try {
            $vehicle = Vehicle::query()->visibleTo(auth()->user())->find($id);

            if ($vehicle) {
                $supplier = $vehicle->supplier;
                $vehicle->delete();
                $supplier?->syncTier();
            }
            session()->flash('success', 'Vehicle deleted.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Unable to delete vehicle.');
        }

        $this->resetPage();
    }

    public function makeAvailable(int $id): void
    {
        $this->updateStatus($id, 'available');
    }

    public function makeUnavailable(int $id): void
    {
        $this->updateStatus($id, 'unavailable');
    }

    private function updateStatus(int $id, string $status): void
    {
        try {
            Vehicle::query()->visibleTo(auth()->user())->whereKey($id)->update(['status' => $status]);
            session()->flash('success', 'Vehicle status updated.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Unable to update vehicle status.');
        }

        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.vehicles.index', [
            'vehicles' => Vehicle::query()
                ->visibleTo(auth()->user())
                ->when($this->vehicleType !== '', fn ($query) => $query->where('vehicle_category', $this->vehicleType))
                ->when($this->vehicleMake !== '', fn ($query) => $query->where('vehicle_make', 'like', '%' . $this->vehicleMake . '%'))
                ->when($this->vehicleModel !== '', fn ($query) => $query->where('vehicle_model', 'like', '%' . $this->vehicleModel . '%'))
                ->when($this->vehicleCondition !== '', fn ($query) => $query->where('vehicle_condition', $this->vehicleCondition))
                ->when($this->plateNumber !== '', fn ($query) => $query->where('plate_number', 'like', '%' . $this->plateNumber . '%'))
                ->when($this->year !== '', fn ($query) => $query->where('vehicle_year', (int) $this->year))
                ->when($this->fuelType !== '', fn ($query) => $query->where('fuel_type', $this->fuelType))
                ->when($this->vehicleColor !== '', fn ($query) => $query->where('vehicle_color', 'like', '%' . $this->vehicleColor . '%'))
                ->when($this->status !== '', function ($query) {
                    $query->where('status', $this->status);
                })
                ->latest('vehicle_id')
                ->paginate(10),
        ]);
    }
}
