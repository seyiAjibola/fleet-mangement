<?php

namespace App\Livewire\Admin\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Admin - Suppliers')]
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
            Supplier::query()->whereKey($id)->delete();
            session()->flash('success', 'Supplier deleted.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Unable to delete supplier.');
        }

        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.suppliers.index', [
            'suppliers' => Supplier::query()
                ->when($this->search !== '', function ($query) {
                    $query->where(function ($inner) {
                        $inner->where('business_name', 'like', '%' . $this->search . '%')
                            ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orWhere('phone_number', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->status !== '', function ($query) {
                    $query->where('status', $this->status);
                })
                ->latest('supplier_id')
                ->paginate(10),
        ]);
    }
}
