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

    public string $search = '';
    public string $status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatedSearch(): void
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

    public function delete(int $id): void
    {
        try {
            Vehicle::query()->whereKey($id)->delete();
            session()->flash('success', 'Vehicle deleted.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Unable to delete vehicle.');
        }

        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.vehicles.index', [
            'vehicles' => Vehicle::query()
                ->when($this->search !== '', function ($query) {
                    $query->where(function ($inner) {
                        $inner->where('vehicle_make', 'like', '%' . $this->search . '%')
                            ->orWhere('vehicle_model', 'like', '%' . $this->search . '%')
                            ->orWhere('plate_number', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->status !== '', function ($query) {
                    $query->where('status', $this->status);
                })
                ->latest('vehicle_id')
                ->paginate(10),
        ]);
    }
}
