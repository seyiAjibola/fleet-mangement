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

    public string $companyName = '';
    public string $contactPerson = '';
    public string $contactNumber = '';
    public string $locationAddress = '';
    public string $cacNo = '';
    public string $tin = '';
    public string $numberOfCars = '';
    public string $status = '';

    protected $queryString = [
        'companyName' => ['except' => ''],
        'contactPerson' => ['except' => ''],
        'contactNumber' => ['except' => ''],
        'locationAddress' => ['except' => ''],
        'cacNo' => ['except' => ''],
        'tin' => ['except' => ''],
        'numberOfCars' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatedCompanyName(): void
    {
        $this->resetPage();
    }

    public function updatedContactPerson(): void
    {
        $this->resetPage();
    }

    public function updatedContactNumber(): void
    {
        $this->resetPage();
    }

    public function updatedLocationAddress(): void
    {
        $this->resetPage();
    }

    public function updatedCacNo(): void
    {
        $this->resetPage();
    }

    public function updatedTin(): void
    {
        $this->resetPage();
    }

    public function updatedNumberOfCars(): void
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
            'companyName',
            'contactPerson',
            'contactNumber',
            'locationAddress',
            'cacNo',
            'tin',
            'numberOfCars',
            'status',
        ]);

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
                ->withCount('vehicles')
                ->when($this->companyName !== '', fn ($query) => $query->where('business_name', 'like', '%' . $this->companyName . '%'))
                ->when($this->contactPerson !== '', fn ($query) => $query->where('contact_person', 'like', '%' . $this->contactPerson . '%'))
                ->when($this->contactNumber !== '', fn ($query) => $query->where('phone_number', 'like', '%' . $this->contactNumber . '%'))
                ->when($this->locationAddress !== '', function ($query) {
                    $query->where(function ($inner) {
                        $inner->where('city', 'like', '%' . $this->locationAddress . '%')
                            ->orWhere('business_address', 'like', '%' . $this->locationAddress . '%');
                    });
                })
                ->when($this->cacNo !== '', fn ($query) => $query->where('cac_no', 'like', '%' . $this->cacNo . '%'))
                ->when($this->tin !== '', fn ($query) => $query->where('tin', 'like', '%' . $this->tin . '%'))
                ->when($this->numberOfCars !== '', fn ($query) => $query->having('vehicles_count', '=', (int) $this->numberOfCars))
                ->when($this->status !== '', function ($query) {
                    $query->where('status', $this->status);
                })
                ->latest('supplier_id')
                ->paginate(10),
        ]);
    }
}
