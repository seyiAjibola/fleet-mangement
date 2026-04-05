<section>
    <x-admin.header pageTitle="Suppliers" pageSubTitle="Track supplier profiles and performance." />
    <x-admin.toast />

    <div class="toolbar">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: end; flex-wrap: wrap">
            <div>
                <label for="supplier-company-name">Company Name</label>
                <input id="supplier-company-name" type="search" wire:model.defer="companyName" placeholder="Company name" />
            </div>
            <div>
                <label for="supplier-contact-person">Contact Person</label>
                <input id="supplier-contact-person" type="search" wire:model.defer="contactPerson" placeholder="Contact person" />
            </div>
            <div>
                <label for="supplier-contact-number">Contact Number</label>
                <input id="supplier-contact-number" type="search" wire:model.defer="contactNumber" placeholder="Contact number" />
            </div>
            <div>
                <label for="supplier-number-of-cars">Number of Cars</label>
                <input id="supplier-number-of-cars" type="number" min="0" wire:model.defer="numberOfCars" placeholder="Cars" />
            </div>
            <div>
                <label for="supplier-location">Location / Address</label>
                <input id="supplier-location" type="search" wire:model.defer="locationAddress" placeholder="City or address" />
            </div>
            <div>
                <label for="supplier-cac-no">CAC No</label>
                <input id="supplier-cac-no" type="search" wire:model.defer="cacNo" placeholder="CAC No" />
            </div>
            <div>
                <label for="supplier-tin">TIN</label>
                <input id="supplier-tin" type="search" wire:model.defer="tin" placeholder="TIN" />
            </div>
            <div>
                <label for="supplier-status">Status</label>
                <select id="supplier-status" wire:model.defer="status">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <button class="button secondary" type="button" wire:click="applyFilters">Filter</button>
                <button class="button secondary" type="button" wire:click="resetFilters">Reset</button>
            </div>
            
        </div>
    </div>
    <div style="margin-bottom: 1rem; display: flex; justify-content: flex-end">  
        <a class="button primary" href="{{ route('admin.suppliers.create') }}">Add New Supplier</a>
    </div>
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact Person</th>
                    <th>Contact Number</th>
                    <th>Cars</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td data-label="Company">{{ $supplier->business_name }}</td>
                        <td data-label="Contact Person">{{ $supplier->contact_person }}</td>
                        <td data-label="Contact Number">{{ $supplier->phone_number }}</td>
                        <td data-label="Cars">{{ $supplier->vehicles_count }}</td>
                        <td data-label="Location">{{ $supplier->city }}</td>
                        <td data-label="Status"><span class="badge" data-status="{{ $supplier->status }}">{{ $supplier->status }}</span></td>
                        <td>
                            <div class="table-actions">
                                <a class="button secondary icon-button icon-view" href="{{ route('admin.suppliers.show', $supplier) }}" aria-label="View supplier" title="View supplier"><x-admin.icon name="view" /></a>
                                <a class="button secondary icon-button icon-edit" href="{{ route('admin.suppliers.edit', $supplier) }}" aria-label="Edit supplier" title="Edit supplier"><x-admin.icon name="edit" /></a>
                                <button class="button secondary icon-button icon-delete" type="button" wire:click="delete({{ $supplier->supplier_id }})" onclick="return confirm('Delete this supplier?')" aria-label="Delete supplier" title="Delete supplier"><x-admin.icon name="delete" /></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No suppliers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $suppliers->links() }}
    </div>
</section>
