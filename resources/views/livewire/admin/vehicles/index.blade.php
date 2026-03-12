<section>
    <x-admin.header pageTitle="Vehicles" pageSubTitle="Fleet inventory, capacity, and availability." />
    <x-admin.toast />

    <div class="toolbar" style="justify-content: space-between">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center">
            <div>
                <label for="vehicle-search">Search</label>
                <input id="vehicle-search" type="search" wire:model.defer="search" placeholder="Make, model, plate" />
            </div>
            <div>
                <label for="vehicle-status">Status</label>
                <select id="vehicle-status" wire:model.defer="status">
                    <option value="">All</option>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
            </div>
            <button class="button secondary" type="button" wire:click="applyFilters">Filter</button>
        </div>
        <a class="button" href="{{ route('admin.vehicles.create') }}">Create vehicle</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Plate</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr>
                        <td data-label="Make">{{ $vehicle->vehicle_make }}</td>
                        <td data-label="Model">{{ $vehicle->vehicle_model }}</td>
                        <td data-label="Year">{{ $vehicle->vehicle_year }}</td>
                        <td data-label="Plate">{{ $vehicle->plate_number }}</td>
                        <td data-label="Status"><span class="badge">{{ $vehicle->status }}</span></td>
                        <td>
                            <div class="table-actions">
                                <a class="button secondary" href="{{ route('admin.vehicles.edit', $vehicle) }}">Edit</a>
                                <button class="button secondary" type="button" wire:click="delete({{ $vehicle->vehicle_id }})" onclick="return confirm('Delete this vehicle?')">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No vehicles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $vehicles->links() }}
    </div>
</section>
