<section>
    <x-admin.header pageTitle="Drivers" pageSubTitle="Roster management and licensing status." />
    <x-admin.toast />

    <div class="toolbar" style="justify-content: space-between">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center">
            <div>
                <label for="driver-search">Search</label>
                <input id="driver-search" type="search" wire:model.defer="search" placeholder="Name, phone, license" />
            </div>
            <div>
                <label for="driver-status">Status</label>
                <select id="driver-status" wire:model.defer="status">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button class="button secondary" type="button" wire:click="applyFilters">Filter</button>
        </div>
        <a class="button" href="{{ route('admin.drivers.create') }}">Create driver</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>License</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($drivers as $driver)
                    <tr>
                        <td data-label="Name">{{ $driver->driver_name }}</td>
                        <td data-label="Phone">{{ $driver->phone_number }}</td>
                        <td data-label="License">{{ $driver->license_number }}</td>
                        <td data-label="Status"><span class="badge">{{ $driver->status }}</span></td>
                        <td>
                            <div class="table-actions">
                                <a class="button secondary" href="{{ route('admin.drivers.edit', $driver) }}">Edit</a>
                                <button class="button secondary" type="button" wire:click="delete({{ $driver->driver_id }})" onclick="return confirm('Delete this driver?')">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No drivers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $drivers->links() }}
    </div>
</section>
