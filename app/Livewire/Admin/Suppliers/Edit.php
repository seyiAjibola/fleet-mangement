<?php

namespace App\Livewire\Admin\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Edit Supplier')]
class Edit extends Component
{
    public Supplier $supplier;
    public string $business_name = '';
    public string $business_type = '';
    public string $contact_person = '';
    public string $phone_number = '';
    public string $email = '';
    public string $city = '';
    public string $business_address = '';
    public int $years_in_business = 0;
    public ?string $instagram_page = null;
    public ?string $website = null;
    public string $status = 'active';
    public int $supplier_score = 0;
    public ?string $supplier_tier = null;

    public function mount(Supplier $supplier): void
    {
        $this->supplier = $supplier;
        $this->business_name = $supplier->business_name;
        $this->business_type = $supplier->business_type;
        $this->contact_person = $supplier->contact_person;
        $this->phone_number = $supplier->phone_number;
        $this->email = $supplier->email;
        $this->city = $supplier->city;
        $this->business_address = $supplier->business_address;
        $this->years_in_business = (int) $supplier->years_in_business;
        $this->instagram_page = $supplier->instagram_page;
        $this->website = $supplier->website;
        $this->status = $supplier->status;
        $this->supplier_score = (int) $supplier->supplier_score;
        $this->supplier_tier = $supplier->supplier_tier;
    }

    protected function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:suppliers,email,' . $this->supplier->supplier_id . ',supplier_id'],
            'city' => ['required', 'string', 'max:255'],
            'business_address' => ['required', 'string'],
            'years_in_business' => ['required', 'integer', 'min:0'],
            'instagram_page' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'supplier_score' => ['required', 'integer', 'min:0'],
            'supplier_tier' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->supplier->update($validated);

        $this->redirectRoute('admin.suppliers.index');
    }

    public function render()
    {
        return view('livewire.admin.suppliers.edit');
    }
}
