<?php

namespace App\Livewire\Admin\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Create Supplier')]
class Create extends Component
{
    public string $business_name = '';
    public string $business_type = '';
    public string $contact_person = '';
    public string $phone_number = '';
    public ?string $cac_no = null;
    public ?string $tin = null;
    public string $email = '';
    public string $city = '';
    public string $business_address = '';
    public int $years_in_business = 0;
    public ?string $instagram_page = null;
    public ?string $website = null;
    public string $status = 'active';

    protected function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'cac_no' => ['nullable', 'string', 'max:255'],
            'tin' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:suppliers,email'],
            'city' => ['required', 'string', 'max:255'],
            'business_address' => ['required', 'string'],
            'years_in_business' => ['required', 'integer', 'min:0'],
            'instagram_page' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['created_by_user_id'] = auth()->id();
        $supplier = new Supplier($validated);
        $validated['supplier_score'] = $supplier->determineScore(0);
        $validated['supplier_tier'] = $supplier->determineTier(0);

        Supplier::query()->create($validated);

        $this->redirectRoute('admin.suppliers.index');
    }

    public function previewScore(): int
    {
        $supplier = new Supplier([
            'cac_no' => $this->cac_no,
            'tin' => $this->tin,
            'status' => $this->status,
            'years_in_business' => $this->years_in_business,
            'website' => $this->website,
            'instagram_page' => $this->instagram_page,
        ]);

        return $supplier->determineScore(0);
    }

    public function previewTier(): string
    {
        $supplier = new Supplier([
            'cac_no' => $this->cac_no,
            'tin' => $this->tin,
            'status' => $this->status,
            'years_in_business' => $this->years_in_business,
            'website' => $this->website,
            'instagram_page' => $this->instagram_page,
        ]);

        return $supplier->determineTier(0);
    }

    public function render()
    {
        return view('livewire.admin.suppliers.create');
    }
}
