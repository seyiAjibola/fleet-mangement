<?php

namespace App\Livewire\Admin\Drivers;

use App\Models\Driver;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Admin - Drivers')]
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
            Driver::query()->visibleTo(auth()->user())->whereKey($id)->delete();
            session()->flash('success', 'Driver deleted.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Unable to delete driver.');
        }

        $this->resetPage();
    }

    public function activate(int $id): void
    {
        $this->updateStatus($id, 'active');
    }

    public function deactivate(int $id): void
    {
        $this->updateStatus($id, 'inactive');
    }

    private function updateStatus(int $id, string $status): void
    {
        try {
            Driver::query()->visibleTo(auth()->user())->whereKey($id)->update(['status' => $status]);
            session()->flash('success', 'Driver status updated.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Unable to update driver status.');
        }

        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.drivers.index', [
            'drivers' => Driver::query()
                ->visibleTo(auth()->user())
                ->when($this->search !== '', function ($query) {
                    $query->where(function ($inner) {
                        $inner->where('driver_name', 'like', '%' . $this->search . '%')
                            ->orWhere('phone_number', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->status !== '', function ($query) {
                    $query->where('status', $this->status);
                })
                ->latest('driver_id')
                ->paginate(10),
        ]);
    }
}
