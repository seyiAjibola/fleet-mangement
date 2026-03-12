<section>
    <x-admin.header pageTitle="Suppliers" pageSubTitle="Track supplier profiles and performance." />
    <x-admin.toast />

    <div class="toolbar" style="justify-content: space-between">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center">
            <div>
                <label for="supplier-search">Search</label>
                <input id="supplier-search" type="search" wire:model.defer="search" placeholder="Business, contact, email, phone" />
            </div>
            <div>
                <label for="supplier-status">Status</label>
                <select id="supplier-status" wire:model.defer="status">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button class="button secondary" type="button" wire:click="applyFilters">Filter</button>
        </div>
        <a class="button" href="{{ route('admin.suppliers.create') }}">Create supplier</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Business</th>
                    <th>Type</th>
                    <th>City</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td data-label="Business">{{ $supplier->business_name }}</td>
                        <td data-label="Type">{{ $supplier->business_type }}</td>
                        <td data-label="City">{{ $supplier->city }}</td>
                        <td data-label="Status"><span class="badge">{{ $supplier->status }}</span></td>
                        <td>
                            <div class="table-actions">
                                <a class="button secondary" href="{{ route('admin.suppliers.edit', $supplier) }}">Edit</a>
                                <button class="button secondary" type="button" wire:click="delete({{ $supplier->supplier_id }})" onclick="return confirm('Delete this supplier?')">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No suppliers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $suppliers->links() }}
    </div>
</section>
